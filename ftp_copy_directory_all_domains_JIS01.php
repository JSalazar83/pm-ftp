<?php
/*
	Este script, mueve "directorios completos" a cada uno de los dominios.
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

		require('comun.php');

		// Ftp accounts OLD JIS01.COM.MX DOMAINS.
		$servers = array(
			//array("klaryco-compresores.com.mx", "klarycompresores", "837[Oe}a@R8~", "50.87.154.140"),
			// array("maquinaria-aguascalientes.com.mx", "pmaguascalientes", "837[Oe}a@R8~", "50.87.154.140"),
			// array("maquinaria-chihuahua.com.mx", "pmchihuahua", "837[Oe}a@R8~","50.87.154.140"),
			// array('maquinaria-cuernavaca.com.mx','pmcuernavaca','837[Oe}a@R8~','50.87.153.94'),
			// array('maquinaria-culiacan.com.mx','pmculiacan','837[Oe}a@R8~','50.87.153.95'),
			// array('maquinaria-durango.com.mx','pmdurango','837[Oe}a@R8~','50.87.154.140'),
			// array('maquinaria-hermosillo.com.mx','pmhermosillo','837[Oe}a@R8~','50.87.154.140'),
			// array('maquinaria-irapuato.com.mx','pmirapuato','837[Oe}a@R8~','50.87.154.140'),
			array('maquinaria-mexicali.com.mx','pmmexicali','837[Oe}a@R8~','maquinaria-mexicali.com.mx'),
			// array('maquinaria-torreon.com.mx','pmtorreon','837[Oe}a@R8~','50.87.154.140'),
			// array('maquinaria-tuxtla.com.mx','pmtuxtla','837[Oe}a@R8~','50.87.154.140'),
			// array('maquinaria-xalapa.com.mx','pmxalapa','837[Oe}a@R8~','50.87.154.140'),
			// array('maquinaria-zapopan.com.mx','pmzapopan','837[Oe}a@R8~','50.87.154.140'),
			// array('montacargas-puebla.com.mx','montpuebla','yK39px1ifD&f','50.87.154.140'),
			// array('plantasdeluz-cdmx.com.mx','pluzcdmx','yK39px1ifD&f','50.87.154.140'),
			// array('plantasdeluz-guadalajara.com.mx','pluzguadalajara','yK39px1ifD&f','50.87.154.140'),
			// array('plantasdeluz-puebla.com.mx','pluzpuebla','yK39px1ifD&f','50.87.154.140'),
			// array('plataformas-elevadoras-guadalajara.com.mx','platguadalajara','yK39px1ifD&f','50.87.153.94'),
			// array('plataformas-elevadoras-leon-gto.com.mx','platgto','yK39px1ifD&f','50.87.153.94'),
			// array('plataformas-elevadoras-monterrey.com.mx','platmonterrey','yK39px1ifD&f','50.87.153.94'),
			// array('retroexcavadoras-guadalajara.com.mx','retrguadalajara','yK39px1ifD&f','50.87.153.94'),
			// array('retroexcavadoras-tijuana.com.mx','retrtijuana','yK39px1ifD&f','50.87.153.94')
		);

		// Directorios a replicar.
		// Debe entencerse como "lo que este dentro del directorio 'temp' copialo y ponlo
		// dentro del directorio 'public_html'."

		$directories = array(
			// "temp/" => "/public_html/"
			// "temp/ci/" => "/",
			// "temp/fonts/" => "/public_html/"
			// "temp/" => "/ci/application/views/",
			// "temp/imagenes_dominios/@dominio/" => "/public_html/",
		);

		foreach($servers as $server){
			$ftp_server = $server[3];
			$ftp_user_name = $server[1];
			$ftp_user_pass = $server[2];
			$domain = $server[0];
			$source = "C:/download/$domain/";
			$destination = "/";

			// Connect to FTP.
			$conn_id = ftp_connect($ftp_server);

			// Login with username and password.
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			// Reinicializar bandera de errores.
			$sin_errores = true;

			// Check connection.
			if ((!$conn_id) || (!$login_result)) {
				echo "<h3>$ftp_server <span class='label label-danger'>Failed! <span class='glyphicon glyphicon-remove' aria-hidden='true'></span></span></h3>";
				echo "<br /><br />";
				continue;
			} else {
				echo "<h3>$ftp_server <span class='label label-success'>Connected! <span class='glyphicon glyphicon-ok' aria-hidden='true'></span></span></h3>";
			}

			// If connection ok, upload files.
			echo "<table class='table table-striped table-hover'>\n";
			echo "<thead><tr><th>Source File</th><th>Destination File</th><th>Copy Status</th></tr></thead>\n";


			ftp_putAll($conn_id, $source, $destination);


			// Check upload status.
			if (!$sin_errores) {
				echo "<tr class='danger'>";
				echo "<td>$source_file</td><td>$destination_file</td><td><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> FTP upload has failed!</td>";
			}
			else {
				echo "<tr class='success'>";
				echo "<td>$source_file</td><td>$destination_file</td><td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Uploaded successfully</td>";
			}
			echo "</tr>";

			echo "</table>";
			// Close the FTP stream.
			ftp_close($conn_id);
			echo "<br /><br />";

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


