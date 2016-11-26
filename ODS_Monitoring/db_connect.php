<?php 

	#Define constants
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'ODS_Monitoring');
	define('DB_LOGIN', 'root');
	define('DB_PASSWORD', '');

	#Connect to database
	$connection = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);

?>