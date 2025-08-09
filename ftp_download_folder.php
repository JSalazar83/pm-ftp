<?php
/*
	Este script, descarga  carpeta completas del servidor (desgarga carpetas de dominios JIS01.COM.MX migración a HOSTGATOR USA)
	https://code-boxx.com/download-folder-php-ftp/
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

		// (C) DOWNLOAD EVERYTHING
		function ftpGetAll($path = "/")
		{
			// (C1) FTP OBJECT & DESTINATION FOLDER
			global $ftp;
			global $destination;

			// (C2) CREATE FOLDER ON LOCAL SERVER
			$saveTo = $path == "/" ? $destination : $destination . $path;
			if (!file_exists($saveTo)) {
				if (mkdir($saveTo)) {
					echo "$saveTo created\r\n";
				} else {
					echo "Error creating $saveTo\r\n";
					return false;
				}
			}

			// (C3) GET FILES
			$files = ftp_mlsd($ftp, $path);
			if (count($files) != 0) {
				foreach ($files as $f) {
					// (C4) FOLDER - RECURSIVE LOOP
					if ($f["type"] == "dir") {
						ftpGetAll($path . $f["name"] . "/");
					}

					// (C5) FILE - DOWNLOAD
					else {
						echo ftp_get($ftp, $saveTo . $f["name"], $path . $f["name"], FTP_BINARY)
							? "Saved to " . $saveTo . $f["name"] . "\r\n"
							: "Error downloading " . $path . $f["name"] . "\r\n";
					}
				}
			}
		}
		date_default_timezone_set("America/Mexico_City");
		$date = date('Y/m/d H:i:s');
		echo "<p>Inició: $date</p>";


		set_time_limit(0);

		// Ftp accounts OLD JIS01.COM.MX DOMAINS.
		// $servers = array(
		// 	array("klaryco-compresores.com.mx", "klarycompresores", "837[Oe}a@R8~", "50.87.154.140"),
		// 	array("maquinaria-aguascalientes.com.mx", "pmaguascalientes", "837[Oe}a@R8~", "50.87.154.140"),
		// 	array("maquinaria-chihuahua.com.mx", "pmchihuahua", "837[Oe}a@R8~","50.87.154.140"),
		// 	array('maquinaria-cuernavaca.com.mx','pmcuernavaca','837[Oe}a@R8~','50.87.153.94'),
		// 	array('maquinaria-culiacan.com.mx','pmculiacan','837[Oe}a@R8~','50.87.153.95'),
		// 	array('maquinaria-durango.com.mx','pmdurango','837[Oe}a@R8~','50.87.154.140'),
		// 	array('maquinaria-hermosillo.com.mx','pmhermosillo','837[Oe}a@R8~','50.87.154.140'),
		// 	array('maquinaria-irapuato.com.mx','pmirapuato','837[Oe}a@R8~','50.87.154.140'),
		// 	array('maquinaria-mexicali.com.mx','pmmexicali','837[Oe}a@R8~','50.87.154.140'),
		// 	array('maquinaria-torreon.com.mx','pmtorreon','837[Oe}a@R8~','50.87.154.140'),
		// 	array('maquinaria-tuxtla.com.mx','pmtuxtla','837[Oe}a@R8~','50.87.154.140'),
		// 	array('maquinaria-xalapa.com.mx','pmxalapa','837[Oe}a@R8~','50.87.154.140'),
		// 	array('maquinaria-zapopan.com.mx','pmzapopan','837[Oe}a@R8~','50.87.154.140'),
		// 	array('montacargas-puebla.com.mx','montpuebla','yK39px1ifD&f','50.87.154.140'),
		// 	array('plantasdeluz-cdmx.com.mx','pluzcdmx','yK39px1ifD&f','50.87.154.140'),
		// 	array('plantasdeluz-guadalajara.com.mx','pluzguadalajara','yK39px1ifD&f','50.87.154.140'),
		// 	array('plantasdeluz-puebla.com.mx','pluzpuebla','yK39px1ifD&f','50.87.154.140'),
		// 	array('plataformas-elevadoras-guadalajara.com.mx','platguadalajara','yK39px1ifD&f','50.87.153.94'),
		// 	array('plataformas-elevadoras-leon-gto.com.mx','platgto','yK39px1ifD&f','50.87.153.94'),
		// 	array('plataformas-elevadoras-monterrey.com.mx','platmonterrey','yK39px1ifD&f','50.87.153.94'),
		// 	array('retroexcavadoras-guadalajara.com.mx','retrguadalajara','yK39px1ifD&f','50.87.153.94'),
		// 	array('retroexcavadoras-tijuana.com.mx','retrtijuana','yK39px1ifD&f','50.87.153.94')
		// );

		foreach($servers as $s)
		{
			// (A) SETTINGS - CHANGE TO YOUR OWN!
			$ftphost = $s[0];
			$ftpuser = $s[1];
			$ftppass = $s[2];

			$destination = "C:/download/$ftphost/";

			if (!(is_dir($destination))) {
				mkdir($destination);
			}

			// (B) CONNECT & LOGIN TO FTP SERVER
			$ftp = ftp_connect($ftphost) or exit("Failed to connect to $ftphost");
			if (!ftp_login($ftp, $ftpuser, $ftppass)) {
				ftp_close($ftp);
				exit("Invalid user/password");
			}
			$source = "/public_html/";
			ftpGetAll($source);

			$source = "/ci/";
			ftpGetAll($source);

			// (D) CLOSE FTP CONNECTION
			ftp_close($ftp);
		}

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