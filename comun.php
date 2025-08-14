<?php

$sin_errores = true;
/* mover directorios completos, recursivamente */
function ftp_putAll($conn_id, $src_dir, $dst_dir)
{

	$d = dir($src_dir);
	while ($file = $d->read()) { // do this for each file in the directory
		if ($file != "." && $file != "..") { // to prevent an infinite loop
			if (is_dir($src_dir . "/" . $file)) { // do the following if it is a directory
				if (!@ftp_chdir($conn_id, $dst_dir . "/" . $file)) {
					$status = ftp_mkdir($conn_id, $dst_dir . "/" . $file); // create directories that do not yet exist
					if ($status == false)
						$sin_errores = false;
				}
				ftp_putAll($conn_id, $src_dir . "/" . $file, $dst_dir . "/" . $file); // recursive part
			} else {
				$upload = ftp_put($conn_id, $dst_dir . "/" . $file, $src_dir . "/" . $file, FTP_BINARY); // put the files
				if (!$upload)
					$sin_errores = false;
			}
		}
	}
	$d->close();
}

// Descargar directorio
// ftp_sync - Copy directory and file structure
//ya no jala
function ftp_sync($dir, $conn_id)
{
	if ($dir !== '.') {
		if (ftp_chdir($conn_id, $dir) === FALSE) {
			echo 'Change dir failed: ' . $dir . PHP_EOL;
			return;
		}
		if (!(is_dir($dir))) {
			mkdir($dir);
		}
		chdir($dir);
	}
	$contents = ftp_nlist($conn_id, '.');
	foreach ($contents as $file) {
		if ($file == '.' || $file == '..') {
			continue;
		}
		if (@ftp_chdir($conn_id, $file)) {
			ftp_chdir($conn_id, "..");
			ftp_sync($file, $conn_id);
		} else {
			ftp_get($conn_id, $file, $file, FTP_BINARY);
		}
	}
	ftp_chdir($conn_id, '..');
	chdir('..');
}



function replaceInRemoteFile(
	string $host,
	string $user,
	string $pass,
	string $remotePath,
	string $search,
	string $replace,
	int $port = 21,
	bool $useSsl = false,
	bool $passive = true
): bool {
	// 1) Conexión FTP (o FTPS si $useSsl = true)
	$conn = $useSsl ? @ftp_ssl_connect($host, $port, 30) : @ftp_connect($host, $port, 30);
	if (!$conn) {
		throw new RuntimeException("No se pudo conectar a $host:$port");
	}
	if (!@ftp_login($conn, $user, $pass)) {
		@ftp_close($conn);
		throw new RuntimeException("Login FTP fallido");
	}
	if ($passive) {
		ftp_pasv($conn, true);
	} // útil detrás de NAT/firewall

	// 2) Descargar a un stream en memoria
	$temp = fopen('php://temp', 'r+'); // memoria con overflow a disco si crece
	if (!$temp) {
		@ftp_close($conn);
		throw new RuntimeException("No se pudo abrir stream temporal");
	}
	if (!@ftp_fget($conn, $temp, $remotePath, FTP_BINARY)) {
		fclose($temp);
		@ftp_close($conn);
		throw new RuntimeException("No se pudo leer el archivo remoto: $remotePath");
	}

	// 3) Leer contenido y reemplazar
	rewind($temp);
	$contents = stream_get_contents($temp);
	if ($contents === false) {
		fclose($temp);
		@ftp_close($conn);
		throw new RuntimeException("No se pudo leer contenido del stream");
	}

	$updated = str_replace($search, $replace, $contents);
	if ($updated === $contents) {
		// Nada que cambiar; cerramos y salimos con éxito
		fclose($temp);
		@ftp_close($conn);
		return true;
	}

	// (Opcional) Crear un respaldo remoto antes de subir
	$backupPath = $remotePath . '.bak_' . date('Ymd_His');
	@ftp_rename($conn, $remotePath, $backupPath); // si falla, seguimos igual

	// 4) Subir de vuelta desde memoria
	$out = fopen('php://temp', 'r+');
	if (!$out) {
		fclose($temp);
		@ftp_close($conn);
		throw new RuntimeException("No se pudo abrir stream de salida");
	}
	fwrite($out, $updated);
	rewind($out);

	$ok = @ftp_fput($conn, $remotePath, $out, FTP_BINARY);

	fclose($out);
	fclose($temp);
	@ftp_close($conn);

	if (!$ok) {
		throw new RuntimeException("No se pudo subir el archivo actualizado a $remotePath");
	}
	return true;
}


