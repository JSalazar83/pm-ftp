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
			"../pm/ci/application/views/pages/aviso_de_privacidad.php" => "/ci/application/views/pages/aviso_de_privacidad.php",
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

			//  "../pm/ci/application/controllers/Maquinaria.php" => "/ci/application/controllers/Maquinaria.php",
			// "../pm/ci/application/controllers/Parametros.php" => "/ci/application/controllers/Parametros.php",

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

		foreach($domains as $domain){
			$ftp_server = $domain[0];
			$ftp_user_name = $domain[1];
			$ftp_user_pass = $domain[2];

			// Connect to FTP.
			$conn_id = ftp_connect($ftp_server);

			// Login with username and password.
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			// turn passive mode on
			ftp_pasv($conn_id, true);

			// Check connection.
			if ((!$conn_id) || (!$login_result)) {
				echo "<h3><a target='_blank' href='http://www.$ftp_server'>$ftp_server</a> <span class='label label-danger'>Failed! <span class='glyphicon glyphicon-remove' aria-hidden='true'></span></span></h3>";
				echo "<br /><br />";
				continue;
			} else {
				echo "<h3><a  target='_blank' href='http://www.$ftp_server'>$ftp_server</a> <span class='label label-success'>Connected! <span class='glyphicon glyphicon-ok' aria-hidden='true'></span></span></h3>";
			}


			// If connection ok, upload files.
			echo "<table class='table table-striped table-hover'>\n";
			echo "<thead><tr><th>Source File</th><th>Destination File</th><th>Copy Status</th></tr></thead>\n";
			foreach($files as $source_file => $destination_file){
				// Upload the file.

				$f_exists = file_exists( $source_file);

				$backup_name = $destination_file . "." . date('Y-m-d_h:ia') . ".bk";
				$rename = ftp_rename($conn_id, $destination_file, $backup_name );
				$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

				// Check upload status.
				if (!$upload) {
					echo "<tr class='danger'>";
					echo "<td>$source_file</td><td>$destination_file</td><td><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> FTP upload has failed!</td>";

				}
				else {
					echo "<tr class='success'>";
					echo "<td>$source_file</td><td>$destination_file</td><td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Uploaded successfully</td>";
				}
				echo "</tr>";
			}
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


