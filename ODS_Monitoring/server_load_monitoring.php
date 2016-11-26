#!/usr/bin/php
<?php
	#Protection against double run
	if(Exec("ps aux | grep {$_SERVER['SCRIPT_NAME']} | grep -v grep -c") > 1) {
		exit;
	}

	#Define constants
	define('DB_HOST', '192.168.20.25');
	define('DB_NAME', 'ODS_Monitoring');
	define('DB_LOGIN', 'root');
	define('DB_PASSWORD', '');
	define('REPORT_FREQUENCY', '15');

	#Connect to database
	$connection = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
	
	if (!$connection) {
    	echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    	echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    	echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    	exit;
	} else {

		while($connection) {
			#Define vars
			$ip = '192.168.20.25';
			$load_average = trim(Exec('cat /proc/loadavg | cut -d " " -f 1,2,3'));
			$cores = Exec('nproc');
			$date = Exec('date "+%d.%m.%y | %H:%M:%S"');
			$quantity_of_calls = Exec('/usr/sbin/asterisk -rx "core show calls" | grep active');
			$note = "Водоканал";

			#Execute query to database
			$query = "INSERT INTO servers_status(IP, Date, Quantity_of_calls, Load_average, Note) 
					VALUES('{$ip}', '{$date}', '{$quantity_of_calls}', '{$load_average} ({$cores} cores)', '{$note}')";
			$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
			
			sleep(REPORT_FREQUENCY);	
		}
	}

	mysqli_close($connection);
?>
