<html>
<head>
	<title>Call_log</title>
	<link rel="stylesheet" type="text/css" href="style.css">	
</head>
<body>

<?php
	
 	#Кладём файлы в массивы
	$statistics = file('calls.txt');

	#Счётчики
	$count_life = 0;
	$count_ks = 0;
	$count_city = 0;

 	$statistics_count = count($statistics);

 	#Инициализация массива для подсчёта количества вхождений конкретного сектора
 	for ($i=1; $i<=31; $i++) {

 		$life[$i] = 0;
 		$ks[$i] = 0;
 		$city[$i] = 0;
 		
 	}
 		
 	#Перебираем все строки
	foreach($statistics as $call)
	{
	   	

		if (preg_match('/Астел/', $call) != false) {

		   			$count_life++;
					
					for ($i=1; $i<=31; $i++) {
						
						if ($i<10) {
							$pref = '0';
						}
						else {
							$pref = "";
						}

						if (preg_match("/$pref$i.10.2015/", $call) != false) {
							
							$life[$i]++;

						}
						
					}
	
	
		}

		if (preg_match('/встар/', $call) != false) {

		   			$count_ks++;
					
					for ($i=1; $i<=31; $i++) {
						
						if ($i<10) {
							$pref = '0';
						}
						else {
							$pref = "";
						}

						if (preg_match("/$pref$i.10.2015/", $call) != false) {
							
							$ks[$i]++;

						}
	
						
					}
	
		}
		 
	}
		
	

?>
	<div class="statistics">
	 	<table>
			<thead>
				<tr>
					<th>День</th>
					<th>Life</th>
					<th>Kyivstar</th>
				</tr>
			</thead>

			<tbody>
				<?php 
					
					for ($i=1; $i<=31; $i++) { 

						echo "<tr>";
							echo "<td>".$i."</td>";
							echo "<td>".$life[$i]."</td>";
							echo "<td>".$ks[$i]."</td>";
						echo "</tr>";

					}

					echo "$count_ks";
					echo "<br>";
					echo "$count_life";
					echo "<br>";
				 ?>
			</tbody>
		</table>

		
	</div>

</body>
</html>