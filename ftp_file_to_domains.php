<?php
/*
	Este script, mueve "archivos individuales" a cada uno de los dominios.
*/
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* theses tags -->
	<title>File Transfer Utility</title>
	<!-- Normalize -->
	<link href="css/normalize.css" rel="stylesheet">
	<!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">
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
		error_reporting(E_ALL);
		ini_set('display_errors', 1);


		echo "<p>Inició: $date</p>";

		set_time_limit(0);
		require('comun.php');
		require('ftp_accounts.php');



		// Files to replicate.
		$files = array(
			// "../pm/public/imagenes/logo.png" => "/public_html/imagenes/logo.png",

			// "../pm/public/robots.txt" => "/public_html/robots.txt",
			// "../pm/public/index.php" => "/public_html/index.php",

			// /** config */
			// "../pm/ci/application/config/routes.php" => "/ci/application/config/routes.php",
			// /** VIEWS/PAGES **/
			// "../pm/ci/application/views/componentes/marcas-carrusel.php" => "/ci/application/views/componentes/marcas-carrusel.php",
			// "../pm/ci/application/views/pages/aside_marcas_slider.php" => "/ci/application/views/pages/aside_marcas_slider.php",
			// "../pm/ci/application/views/pages/aside_requisitos.php" => "/ci/application/views/pages/aside_requisitos.php",
			//"../pm/ci/application/views/pages/aviso_de_privacidad.php" => "/ci/application/views/pages/aviso_de_privacidad.php",
			// "../pm/ci/application/views/pages/chat.php" => "/ci/application/views/pages/chat.php",
			// "../pm/ci/application/views/pages/contenedor_lateral.php" => "/ci/application/views/pages/contenedor_lateral.php",
			// "../pm/ci/application/views/pages/contenedor_lateral_menu_categorias.php" => "/ci/application/views/pages/contenedor_lateral_menu_categorias.php",
			// "../pm/ci/application/views/pages/contenedor_lateral_menu_equipos.php" => "/ci/application/views/pages/contenedor_lateral_menu_equipos.php",
			// "../pm/ci/application/views/pages/datos_estructurados.php" => "/ci/application/views/pages/datos_estructurados.php",
			// "../pm/ci/application/views/pages/footer.php" => "/ci/application/views/pages/footer.php",
			// "../pm/ci/application/views/pages/formulario-horizontal.php" => "/ci/application/views/pages/formulario-horizontal.php",
			// "../pm/ci/application/views/pages/galeria.php" => "/ci/application/views/pages/galeria.php",
			// "../pm/ci/application/views/pages/header.php" => "/ci/application/views/pages/header.php",
			// "../pm/ci/application/views/pages/lista-precios.php" => "/ci/application/views/pages/lista-precios.php",
			// "../pm/ci/application/views/pages/mapa.php" => "/ci/application/views/pages/mapa.php",
			// "../pm/ci/application/views/pages/navigation_categoria.php" => "/ci/application/views/pages/navigation_categoria.php",
			// "../pm/ci/application/views/pages/navigation_equipo.php" => "/ci/application/views/pages/navigation_equipo.php",
			// "../pm/ci/application/views/pages/navigation_index.php" => "/ci/application/views/pages/navigation_index.php",
			// "../pm/ci/application/views/pages/nosotros.php" => "/ci/application/views/pages/nosotros.php",
			// "../pm/ci/application/views/pages/pagina_categorias.php" => "/ci/application/views/pages/pagina_categorias.php",
			// "../pm/ci/application/views/pages/pagina_equipo.php" => "/ci/application/views/pages/pagina_equipo.php",
			// "../pm/ci/application/views/pages/pagina_equipos.php" => "/ci/application/views/pages/pagina_equipos.php",
			// "../pm/ci/application/views/pages/pagina_principal.php" => "/ci/application/views/pages/pagina_principal.php",
			// "../pm/ci/application/views/pages/principal_intro.php" => "/ci/application/views/pages/principal_intro.php",
			// "../pm/ci/application/views/pages/servicios.php" => "/ci/application/views/pages/servicios.php",
			// "../pm/ci/application/views/pages/sucursales.php" => "/ci/application/views/pages/sucursales.php",
			// "../pm/ci/application/views/plantillas/p_categoria.php" => "/ci/application/views/plantillas/p_categoria.php",
			// "../pm/ci/application/views/plantillas/p_equipo.php" => "/ci/application/views/plantillas/p_equipo.php",
			"../pm/ci/application/views/errors/html/error_404.php" => "/ci/application/views/errors/html/error_404.php",

			//  "../pm/ci/application/controllers/Maquinaria.php" => "/ci/application/controllers/Maquinaria.php",
			// "../pm/ci/application/controllers/Parametros.php" => "/ci/application/controllers/Parametros.php",
			"../pm/ci/application/controllers/Errors.php" => "/ci/application/controllers/Errors.php",


			// "../pm/public/js/app.js" => "/public_html/js/app.js",
			//  "../pm/public/css/bundle.css" => "/public_html/css/bundle.css",
			// "../pm/public/upload.php" => "/public_html/upload.php",
			// "../pm/public/class.upload.php" => "/public_html/class.upload.php",

			// /** Imágenes */

			// "../pm/public/imagenes/logo-blanco-cajas.png" => "/public_html/imagenes/logo-blanco-cajas.png",
			// "../pm/public/imagenes/logo-negro-cajas.png" => "/public_html/imagenes/logo-negro-cajas.png",
			// "../pm/public/imagenes/logo.png" => "/public_html/imagenes/logo.png",
			// "../pm/public/imagenes/logo_navbar.png" => "/public_html/imagenes/logo_navbar.png",
			// "../pm/public/imagenes/c-gruas.jpg" => "/public_html/imagenes/c-gruas.jpg",
			// "../pm/public/imagenes/caja1_bg.jpg" => "/public_html/imagenes/caja1_bg.jpg",
			// "../pm/public/imagenes/caja2_bg.jpg" => "/public_html/imagenes/caja2_bg.jpg",
			// "../pm/public/imagenes/caja3_bg.jpg" => "/public_html/imagenes/caja3_bg.jpg",
			// "../pm/public/imagenes/header_bg_01.jpg" => "/public_html/imagenes/header_bg_01.jpg",
			// "../pm/public/imagenes/renta-rotomartillo.jpg" => "/public_html/imagenes/renta-rotomartillo.jpg",
			// "../pm/public/imagenes/renta_maquinaria_01.jpg" => "/public_html/imagenes/renta_maquinaria_01.jpg",
			// "../pm/public/imagenes/renta_maquinaria_02.jpg" => "/public_html/imagenes/renta_maquinaria_02.jpg",
		);

		foreach ($domains as $domain) {
			$host = $domain[0];
			$user = $domain[1];
			$pass = $domain[2];
			$port = 21;
			$timeout = 30;
			$message = '';

			if (!function_exists('ftp_ssl_connect')) {
				die("ftp_ssl_connect() not available. Ensure PHP FTP+OpenSSL is enabled.");
			}

			// Connect to FTP.
			$conn = @ftp_ssl_connect($host, $port, $timeout);
			if (!$conn) {
				$message = "Could not connect securely to $host:$port";
				echo "<h3><a target='_blank' href='http://www.$host'>$message</a> <span class='label label.danger'>Failed!</h3>";
				echo "<br /><br />";
				continue;
			}

			// Login with username and password.
			$login = @ftp_login($conn, $user, $pass);
			if (!$login) {
				ftp_close($conn);
				$message = "Login failed for $host";
				echo "<h3><a target='_blank' href='http://www.$host'>$message</a> <span class='label label.danger'>Failed!</h3>";
				echo "<br /><br />";
				continue;
			}
			// After login, add these critical commands:
			ftp_raw($conn, "PBSZ 0"); // Set protection buffer size
			ftp_raw($conn, "PROT P"); // Encrypt data channel
			ftp_pasv($conn, true);     // Enable passive mode
			ftp_set_option($conn, FTP_USEPASVADDRESS, false);

			echo "<h3><a  target='_blank' href='http://www.$host'>$host</a> <span class='label label-success'>Connected!</h3>";

			// If connection ok, upload files.
			echo "<table class='table table-striped table-hover'>\n";
			echo "<thead><tr><th>Source File</th><th>Destination File</th><th>Copy Status</th></tr></thead>\n";
			foreach ($files as $source_file => $destination_file) {

				if (!file_exists($source_file)) {
					echo "<tr class='danger'>";
					echo "<td>$source_file</td><td></td><td>No existe archivo local</td></tr>";
					continue;
				}

				// If remote file exists: backup file
				// Check if the file exists
				$file_size = ftp_size($conn, $destination_file);
				if ($file_size != -1) {
					$backup_name = $destination_file . "__" . date('Y-m-d_h:ia') . "replaced.bk";
					$rename = ftp_rename($conn, $destination_file, $backup_name);
				}

				// Upload the file.
				$upload = ftp_put($conn, $destination_file, $source_file, FTP_BINARY);

				// Check upload status.
				if (!$upload) {
					echo "<tr class='danger'>";
					echo "<td>$source_file</td><td>$destination_file</td><td> FTP upload has failed!</td></tr>";

				} else {
					echo "<tr class='success'>";
					echo "<td>$source_file</td><td>$destination_file</td><td> Uploaded successfully</td></tr>";
				}
			}
			echo "</table>";

			// Close the FTP stream.
			ftp_close($conn);
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