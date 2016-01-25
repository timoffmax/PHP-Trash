<?php include_once('functions.inc.php') ?>

<html>
<head>
	<title>Sectors statistics</title>
	<link rel="stylesheet" type="text/css" href="style.css">	
</head>
<body>

<?php
	
 	#Счётчик
 	$counter_full = 0;
 	$GLOBALS['$locked'] = 0;

 	#var_dump($_FILES);
 	#Кладём файлы в массивы
	#$log = file('skype_new_log.txt');
	
	#Отслеживание ошибок загрузки
	if($_FILES['file_log']['error']) {

		echo "\t<div class='warnings'>\n
				\t\t<h2>Ошибка загрузки лог-файла!</h2>\n
			  \t</div>\n";

		exit();

	} elseif ($_FILES['file_black_list']['error']) {
		
		echo "\t<div class='warnings'>\n
				\t\t<h3>Файл чёрного списка не выбран! Обработка статистики будет проводиться без него.</h3>\n
			  \t</div>\n";

	}

	#Загрузка файла лога
	$log = file($_FILES['file_log']['tmp_name']);


 	#test
	
		// foreach ($black_list as $black_string) {
		
		// 	if (strpos($log_string, 'Тимофей') != false) {

		// 		$black_string = substr($black_string, 0, strlen($black_string)-1);
		// 		var_dump($black_string);
		// 		echo "<br>";

		// 	}

		// }
	

 	#Перебираем все строки
	foreach($log as $log_string)
	{
	   	

		#Проверка по списку "запрещённых" слов
		if(@blacklist('black_list.txt', $log_string)) {

			continue;

		}

		#Обрезаем дату, время и имя собеседника

		$log_string = cut_date($log_string);
		$log_string = cut_name($log_string);

		#Просто очень большой IF. Ну ведь тут и так всё очевидно.
		if (preg_match('/(?<sector_name>
							
							#ЖЕД`ы вида ЖЕД XXX-X  и обычные цифровые сектора вида XXX-X
							((ЖЕД[-\s]?)?[1-7][0-2][0-9][\D][1-9])	|
							#ЖЕД`ы вида ЖЕДX-X
							(ЖЕД[-\s]?[1-9][-\s][1-7][^-\d])	|
							#Трёхбуквенные сектора
							(\b(?!УЖЕ)[а-яI]{3}[-\s]?[1-6][^-\d])	|
							#КВ, ПБ, ПЖ
							([КП][ВБЖ][-\s]?[1-5])	|
							#ГРУШ, ХРЕЩ
							(ГРУШ[-\s]?[1-4]|ХРЕ[ЩШ][-\s]?[1-4])	|
							#Остальные сектора, не поддающиеся шаблонизации
							(ЖП|ЛИКО|(ЖБК[^\/])|Запорожье[-\s]?[1-2]|(313|314|317|105|112|70[2-57]|40[346])(?!([-\s]?[\d])))

						)/xui', $log_string, $found_string) 
						
			&& preg_match('/(крут|зах|завис|стат|нельз)/ui', $log_string)
			) 
		{

				
				#Приведение сектора к стандартизированному
				$sector = check_sector($found_string['sector_name']);
			
				#Если уже есть ячейка в массиве с именем сектора, то записываем туда. Если нет, то создаём.
				if (@$count["$sector"]) {
					@$count["$sector"]++;
				} else {
					$count["$sector"] = 1;
				}

				$counter_full++;

		}
	    

	}
	

?>
	<div class="statistics">
	 	<table>
			<thead>
				<tr>
					<th>№ сектора</th>
					<th>Крутило раз</th>
				</tr>
			</thead>

			<tbody>
				<?php 
					
					arsort($count, SORT_NUMERIC);

					$sectors_count = count($count);
					foreach ($count as $name => $volume) { 

						echo "\t<tr>\n";
							echo "\t\t<td>".$name."</td>\n";
							echo "\t\t<td>".$volume."</td>\n";
						echo "\t</tr>\n";

					}

					echo "\t<tr>\n
							\t\t<th>Секторов крутило: $sectors_count</th>\n
							\t\t<th>Всего крутило раз: $counter_full</th>\n
						  \t</tr>\n";

				 ?>
			</tbody>
		</table>
	</div>

</body>
</html>