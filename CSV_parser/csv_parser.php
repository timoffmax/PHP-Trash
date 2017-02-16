<?php
/**
* CsvParser
* For parsing Master.csv file
*/
class CsvParser
{
	public $callerid;
	public $dialednumber;
	public $date;

	public function __construct($callerid, $dialednumber, $date)
	{
		$this->callerid = $callerid;
		$this->dialednumber = $dialednumber;
		$this->date = $date;
	}

	public static function csvToArray($filename, $targetDialednumber, $startTime, $endTime)
	{
		$file = file($filename);
		$resultArray = array();

		foreach ($file as $string) {
			$columns = explode(',', $string);

			$callerid = trim($columns[1], '"');
			$dialednumber = trim($columns[2], '"');
			$date = trim($columns[11], '"');
			if ($date > $startTime && $date < $endTime && $dialednumber == $targetDialednumber && !empty($callerid)) {
				$resultArray[] = new CsvParser($callerid, $dialednumber, $date);
			}
		}

		return $resultArray;
	}

	public static function generateHtmlReport(array $calls)
	{
		ob_start();
		require 'report_generate.php';
		file_put_contents('/tmp/call_report.html', ob_get_contents());
		ob_end_clean();	
	}
	public static function generateTxtReport(array $calls)
	{
		//Delete file content
		file_put_contents('/tmp/only_numbers.txt', '');
		foreach ($calls as $call) {
			file_put_contents('/tmp/only_numbers.txt', $call->callerid . "\n", FILE_APPEND);
		}
	}
}

$calls = CsvParser::csvToArray('/var/log/asterisk/cdr-csv/Master.csv.temp', '838', '2017-02-16 06:52:00', '2017-02-16 07:22:59');
CsvParser::getStatistic($calls);
CsvParser::getOnlyNumbers($calls);