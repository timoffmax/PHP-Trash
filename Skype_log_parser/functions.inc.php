<?php 

	#Function cut date and time from skype_log
	function cut_date($string) {

		$string = substr($string, (strpos($string, ']')+2));
		return $string;

	}

	#Function cut name from skype_log
	function cut_name($string) {

		$string = substr($string, strpos($string, ':')+2);
		return $string;

	}

	function check_sector($string) {

		#Приводим символы к верхнему регистру
			$sector_upper = mb_strtoupper($string, "UTF-8");
		#Вырезаем все дефисы и пробелы, табуляции (ну а вдруг операторы знают что это)
			$cut = preg_replace('/[-:,=\s]/', '', $sector_upper);
		#Возвражаем дефмс в конструкции вида XXX-X, исключая ЖЕД как XXX
			$cut = preg_replace('/((?:(?:[0-9]{3})|(?:(?!ЖЕД))(?:[а-я]{3})))([0-9])/iu', '$1-$2', $cut);
		#Вставляем дефисы в конструкциях вида ЖЕДX-X
			$final_cut = preg_replace('/((?<=ЖЕД)([0-9])([0-9])(?!\d))/iu', '$2-$3', $cut);

		return $final_cut;		

	}

	function blacklist($file_with_blacklist, $log_file) {

		$black_list = file("$file_with_blacklist");

		foreach ($black_list as $black_string) {
			
			$black_string = substr($black_string, 0, strlen($black_string)-1);

			if (strpos($log_file, $black_string) != false) {

				$GLOBALS['$locked']++;
				
				return true;

			}

		}


	}

?>
