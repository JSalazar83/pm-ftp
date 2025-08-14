<?php
/*
	Este script, edita o modifica lineas de código de archivos remotos.
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
		require 'comun.php';
		require 'ftp_accounts.php';

		$editArray = [
			['ci/application/controllers/Maquinaria.php', 'redirect(base_url());', 'show_404();'],
			['/ci/application/config/routes.php', "['404_override'] = 'maquinaria'", "['404_override'] = 'errors/error404'"]
		];

		echo "<table class='table table-striped table-hover'>\n";
		echo "<thead><tr><th>Dominio</th><th>Archivo</th><th>Edit Status</th></tr></thead>\n";

		foreach ($domains as $domain) {
			$host = $domain[0];
			$user = $domain[1];
			$pass = $domain[2];
			$port = 21;
			$timeout = 30;
			$message = '';

			foreach ($editArray as $edit) {
				$remotePath = $edit[0];
				$search = $edit[1];
				$replace = $edit[2];

				try {
					replaceInRemoteFile(
						$host,
						$user,
						$pass,
						$remotePath,
						$search,
						$replace,
						$port,
						true
					);
					//echo "Archivo actualizado correctamente.\n";
					echo "<tr class='success'><td>$host</td><td>$remotePath</td><td>Archivo actualizado correctamente.</td></tr>";
				} catch (Throwable $e) {
					//echo "Error: " . $e->getMessage() . "\n";
					echo "<tr class='danger'><td>$host</td><td>$remotePath</td><td>". $e->getMessage() ."</td></tr>";
				}
			}


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