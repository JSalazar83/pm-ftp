<?php
/*
	Este script, mueve "archivos JS ESPECIFICOS" a cada uno de los dominios.
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


		// Files to replicate.
		$files = array(


			"../dev/pm/assets/scripts/" => "/public_html/js/app-especifico.js",
		);

		foreach($domains as $domain){
			$ftp_server = $domain[0];
			$ftp_user_name = $domain[1];
			$ftp_user_pass = $domain[2];
			$nombre_js = $domain[3];
			// Connect to FTP.
			$conn_id = ftp_connect($ftp_server);

			// Login with username and password.
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);



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

				//$rename = ftp_rename($conn_id, $destination_file, $destination_file . '_bk' . date('Y-m-d_h:ia'));
				$upload = ftp_put($conn_id, $destination_file, $source_file.$nombre_js, FTP_BINARY);

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


