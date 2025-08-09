<?php
/*
	*obsoleto*
	Este script, copia "duplica",  "archivos individuales" en Jsalazar, de una carpeta a otra.
*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>File Transfer Utility</title>
	<!-- Normalize -->
	<link href="css/normalize.css" rel="stylesheet">
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="container">
		<?php
		date_default_timezone_set("America/Mexico_City");
		$date = date('Y/m/d H:i:s');
		echo "<p>Inició: $date</p>";

		set_time_limit(0);

		// Ruta de donde se obtienen las imagenes.
		$pathFrom = "public_html/maquinaria/@folder/css/img/";
		// Ruta donde se guardarán las imagenes
		$path = "public_html/maquinaria/@folder/imagenes/";

		// Ftp accounts.
		$servers = array(
			array("jsalazar.com.mx","jsalazar","46;Es)[]}@sH")
		);

		// Files to replicate.
		/*
		$files = array(
			//"../banners/renta-maquinaria.jpg" => "renta-maquinaria.jpg",
			//"../banners/renta-maquinaria2.jpg" => "renta-maquinaria2.jpg"
			"../_imagenes/c_renta_de_camiones_de_volteo.jpg" => "c_renta_de_camiones_de_volteo.jpg" ,
			"../_imagenes/c_renta_equipo_topografico.jpg" => "c_renta_equipo_topografico.jpg"
		);
		*/
		// Archivos a copiar
		$files = array(
			//"renta-maquinaria.jpg",
			//"renta-maquinaria2.jpg"
			"logo.png"
		);

		require('comun.php');

		foreach($servers as $server){

			$ftp_server = $server[0];
			$ftp_user_name = $server[1];
			$ftp_user_pass = $server[2];

			// Connect to FTP.
			$conn_id = ftp_connect($ftp_server);

			// Login with username and password.
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			// Check connection.
			if ((!$conn_id) || (!$login_result)) {
				echo "<h3>$ftp_server <span class='label label-danger'>Failed! <span class='glyphicon glyphicon-remove' aria-hidden='true'></span></span></h3>";
				echo "<br /><br />";
				continue;
			} else {
				echo "<h3>$ftp_server <span class='label label-success'>Connected! <span class='glyphicon glyphicon-ok' aria-hidden='true'></span></span></h3>";
			}

			// If connection ok, download and upload files.
			foreach($domains as $domain){
				echo "<table class='table table-striped table-hove'>\n";
				echo "<thead><tr><th>Domain</th><th>File</th><th>Copy Status</th></tr></thead>\n";
				foreach($files as $file ){
					// Descargar el archivo.
					$fileToDownload = str_replace("@folder", $domain[0], $pathFrom . $file);
					if (ftp_get($conn_id, $file, $fileToDownload, FTP_BINARY))
					{
						// Subir el archivo en diferente carpeta.
						$destination_file = str_replace("@folder", $domain[0], $path . $file);
						// Upload the file.
						$upload = ftp_put($conn_id, $destination_file, $file    , FTP_BINARY);
						// Check upload status.
						if (!$upload) {
							echo "<tr class='danger'><td><a href='http://www.$domain[0]' target='_blank'>$domain[0]</a></td>";
							echo "<td>$file</td><td><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> FTP upload has failed!</td>";
						}
						else {
							echo "<tr class='success'><td><a href='http://www.$domain[0]'  target='_blank'>$domain[0]</a></td>";
							echo "<td>$file</td><td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Copied successfully</td>";
						}
						echo "</tr>";
					}
					else
					{
						echo "<tr class='danger'><td><a href='http://www.$domain[0]' target='_blank'>$domain[0]</a></td>";
						echo "<td>$file</td><td><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> FTP download has failed!</td>";
					}
				}
				echo "</table>";
			}
			// Close the FTP stream.
			ftp_close($conn_id);
			echo "<br /><br />";

		}


		/*

		Upload Entire Directory via PHP FTP
		Once you have a connection open, uploading the contents of a directory serially is simple:

		foreach (glob("/directory/to/upload/*.*") as $filename)
			ftp_put($ftp_stream, basename($filename) , $filename, FTP_BINARY);

		*/
		 $date = date('Y/m/d H:i:s');
		echo "<p>Terminó: $date</p>";
		?>

	</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) ================= -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

	<!-- Include all compiled plugins (below) ================================== -->
	<script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>


