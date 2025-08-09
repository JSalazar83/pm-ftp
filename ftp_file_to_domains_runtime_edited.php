<?php
/*
	Este script, mueve un archivo individual a los dominios pero antes los mofica con valores especificos del dominio en cuestión
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
		$domains = array (
			array(1,'equirentas.com.mx','equirent','%vs{O[q#rI{w', 'maps-analytics.equirentas.js'),
			array(2,'maquinaria-monterrey.com.mx','maqmonterrey','&*dLQ$UTwkxu', 'maps-analytics.monterrey.js'),
			array(3,'maquinaria-guadalupe.com.mx','maqguadalupe','B?kl5243}th3', 'maps-analytics.guadalupe.js'),
			array(10,'maquinaria-puebla.com.mx','maqpuebla','8By=,t%#hz.a', 'maps-analytics.puebla.js'),
			array(11,'maquinaria-toluca.com.mx','maqtoluca','NhE_JPS&u=B}', 'maps-analytics.toluca.js'),
			array(16,'maquinaria-guadalajara.com.mx','mguadalajara','#Jn;8wW9p_RC', 'maps-analytics.guadalajara.js'),
			array(20,'maquinaria-queretaro.com.mx','maqqueretaro','Znh((o2lLy?s', 'maps-analytics.queretaro.js'),
			array(21,'maquinaria-merida.com.mx','maqmerida','t.Tt4vpI6.~M', 'maps-analytics.merida.js'),
			array(24,'maquinaria-acapulco.com.mx','maqacapulco','KKD6{1)eP,Vi', 'maps-analytics.acapulco.js'),
			array(28,'maquinaria-morelia.com.mx','maqmorelia','~N)w}]HNUW.7', 'maps-analytics.morelia.js'),
			array(30,'serviciojake.com.mx','serviciojake','BEMM@+Fin;ET', 'maps-analytics.veracruz.js'),
			array(31,'maquinaria-villahermosa.com.mx','maqvillahermosa','~@-cTf(GhT8)', 'maps-analytics.villahermosa.js'),
			array(34,'maquinaria-cancun.com.mx','maqcancun','?rN+q[(**R^T', 'maps-analytics.cancun.js'),
			array(51,'maquinaria-loscabos.com.mx','loscabos',']v*{uCv{MY3nL#Kg', 'maps-analytics.loscabos.js'),
			array(53,'maquinaria-cdmx.com.mx','maqcdmx',']sXe6u8_!Pe8', 'maps-analytics.cdmx.js'),
			array(54,'maquinaria-oaxaca.com.mx','maqoaxaca','{wOfl@vFS=5r', 'maps-analytics.oaxaca.js'),
			array(55,'maquinaria-leon.com.mx','maqleon','HhGlS{hf=G0R', 'maps-analytics.leon.js'),
			array(59,'maquinaria-tepic.com.mx','maqtepic','#4C@A%ylhWx=', 'maps-analytics.tepic.js'),
			array(62,'maquinaria-slp.com.mx','maqslp','#4C@A%ylhWx=', 'maps-analytics.slp.js'),
			array(63,'maquinaria-klaryco.com.mx','klaryco','#4C@A%ylhWx=', 'maps-analytics.klaryco.js'),
			array(64,'maquinaria-mg.com.mx','maqmg','#4C@A%ylhWx=', 'maps-analytics.mg.js'),
			array(65,'maquinaria-cr.com.mx','maqcr','#4C@A%ylhWx=', 'maps-analytics.cr.js'),
			array(66,'mopa-maquinaria.com.mx','mopa','#4C@A%ylhWx=', 'maps-analytics.mopa.js'),
			array(69,'alfa-maquinaria.mx','alfamaq','#4C@A%ylhWx=', 'maps-analytics.alfasaltillo.js'),
			array(70,'maquinaria-tijuana.com.mx','maqtijuana','#4C@A%ylhWx=', 'maps-analytics.tijuana.js')
		);

		// Files to replicate.
		$files = array(
			//"../pm/ci/application/config/constants.php" => "/ci/application/config/constants.php",
			"../pm/assets/scripts/" => "/public_html/js/",
			
		);
		// $id_dominio = 1; 
		// $constants_id_dominio = "('ID_DOMINIO', 1)";
		foreach($domains as $domain){
			// $ftp_server = $domain[1];
			// $ftp_user_name = $domain[2];
			// $ftp_user_pass = $domain[3];

			// // Connect to FTP.
			// $conn_id = ftp_connect($ftp_server);

			// // Login with username and password.
			// $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			// // turn passive mode on
			// ftp_pasv($conn_id, true);

			// // Check connection.
			// if ((!$conn_id) || (!$login_result)) {
			// 	echo "<h3><a target='_blank' href='http://www.$ftp_server'>$ftp_server</a> <span class='label label-danger'>Failed! <span class='glyphicon glyphicon-remove' aria-hidden='true'></span></span></h3>";
			// 	echo "<br /><br />";
			// 	continue;
			// } else {
			// 	echo "<h3><a  target='_blank' href='http://www.$ftp_server'>$ftp_server</a> <span class='label label-success'>Connected! <span class='glyphicon glyphicon-ok' aria-hidden='true'></span></span></h3>";
			// }
		 
			
			// If connection ok, upload files.
			// echo "<table class='table table-striped table-hover'>\n";
			// echo "<thead><tr><th>Source File</th><th>Destination File</th><th>Copy Status</th></tr></thead>\n";
			foreach($files as $source_file => $destination_file){
		
				$str = file_get_contents($source_file . $domain[4]);
				echo "<tr class='success'>";
				$escaped = addslashes($str);
				echo " <td><xmp>insert into custom_js values ('$domain[0]', '$escaped');</xmp></td>";
				echo "</tr>";
				// $f_exists = file_exists( $source_file);
				// if (! $f_exists) 
				// {
				// 	echo "<tr class='danger'>";
				// 	echo "<td>$source_file</td><td>$destination_file</td><td><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Source File doesn´t exist</td>";
				// 	brake; 
				// }

				// // 2) Reemplazar ID_DOMINIO en constants.php				
				// $id_dominio_nuevo = $domain[0];
				
				// $str = file_get_contents($source_file);
				// $constants_id_dominio_nuevo = str_replace($id_dominio, $id_dominio_nuevo, $constants_id_dominio);
				// $str = str_replace($constants_id_dominio, $constants_id_dominio_nuevo, $str);
				// file_put_contents($source_file, $str);
				// $constants_id_dominio = $constants_id_dominio_nuevo;
				// $id_dominio = $id_dominio_nuevo;
				
				// $backup_name = $destination_file . "." . date('Y-m-d_h:ia') . ".bk";
				// $rename = ftp_rename($conn_id, $destination_file, $backup_name );
				// $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

				// // Check upload status.
				// if (!$upload) {
				// 	echo "<tr class='danger'>";
				// 	echo "<td>$source_file</td><td>$destination_file</td><td><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> FTP upload has failed!</td>";
				// 	print_r( error_get_last() );
				// }
				// else {
				// 	echo "<tr class='success'>";
				// 	echo "<td>$source_file</td><td>$destination_file</td><td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Uploaded successfully</td>";
				// }
				// echo "</tr>";
			}
			// echo "</table>";
			// // Close the FTP stream.
			// // ftp_close($conn_id);
			// echo "<br /><br />";

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


