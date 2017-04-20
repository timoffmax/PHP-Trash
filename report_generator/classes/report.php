<?php
/**
* Report
*/
class Report
{
	protected $tableName;
	protected $startDate;
	protected $endDate;
	protected $startDay;
	protected $endDay;
	protected $groupBy;
	protected static $content = null;

	public function __construct($tableName, $startDate, $endDate, $groupBy = 'HOUR')
	{
		$this->tableName = $tableName;
		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$this->groupBy = $groupBy;
		
		$startDate = DateTime::createFromFormat('Y-m-d', $startDate);
		$endDate = DateTime::createFromFormat('Y-m-d', $endDate);
		$interval = $startDate->diff($endDate);
		$this->startDay = (int)$startDate->format('d');
		$this->endDay = $startDate->format('d') + $interval->format('%a') - 1;

		$this->generateReport();
	}

	public function getReport()
	{
		//Get connection
		$db = Db::getConnection();
		
		$sql = "SELECT 
					DATEADD({$this->groupBy}, DATEDIFF({$this->groupBy}, 0, Date), 0) AS 'date',
					CASE 
			           WHEN Number LIKE '0[679]3%' THEN 'Lifecell'
			           WHEN Number LIKE '050%' THEN 'Vodafone'
			           WHEN Number LIKE '066%' THEN 'Vodafone'
			           WHEN Number LIKE '099%' THEN 'Vodafone'
			           WHEN Number LIKE '095%' THEN 'Vodafone'
			           WHEN Number LIKE '039%' THEN 'Kyivstar'
			           WHEN Number LIKE '06[78]%' THEN 'Kyivstar'
			           WHEN Number LIKE '09[678]%' THEN 'Kyivstar'
			           ELSE 'Other'
			       	END AS 'operator',
			       	COUNT(Number) AS 'quantity'
				FROM {$this->tableName}
				WHERE Date BETWEEN :startDate AND :endDate
				GROUP BY 
					CASE 
			           WHEN Number LIKE '0[679]3%' THEN 'Lifecell'
			           WHEN Number LIKE '050%' THEN 'Vodafone'
			           WHEN Number LIKE '066%' THEN 'Vodafone'
			           WHEN Number LIKE '099%' THEN 'Vodafone'
			           WHEN Number LIKE '095%' THEN 'Vodafone'
			           WHEN Number LIKE '039%' THEN 'Kyivstar'
			           WHEN Number LIKE '06[78]%' THEN 'Kyivstar'
			           WHEN Number LIKE '09[678]%' THEN 'Kyivstar'
			           ELSE 'Other'
			       	END,
			       	DATEADD({$this->groupBy}, DATEDIFF({$this->groupBy}, 0, Date), 0)
				ORDER BY date, quantity DESC";
		
		$placeholders = array(
			'startDate' => $this->startDate,
			'endDate' => $this->endDate,
		);

		//Get data from DB
		$result = $db->query($sql, $placeholders);

		//Array for report data
		$report = array();

		foreach ($result as $key => $row) {
			switch ($this->groupBy) {
				case 'DAY':
					$date = DateTime::createFromFormat('M d Y h:i:s:uA', $row['date']);
					$day = $date->format('d');
					$hour = $date->format('H');
					$operator = $row['operator'];
					$quantity = $row['quantity'];

					//Create arrays for all operators
					if (!array_key_exists($operator, $report)) {
						$report[$operator] = array();
					}

					//Create arrays for all days
					if (!array_key_exists($day, $report[$operator])) {
						$report[$operator][$day] = $quantity;
					}

					break;
				
				case 'HOUR':
					$date = DateTime::createFromFormat('M d Y h:i:s:uA', $row['date']);
					$day = $date->format('d');
					$hour = $date->format('H');
					$operator = $row['operator'];
					$quantity = $row['quantity'];

					//Create arrays for all operators
					if (!array_key_exists($operator, $report)) {
						$report[$operator] = array();
					}

					//Create arrays for all days
					if (!array_key_exists($day, $report[$operator])) {
						$report[$operator][$day] = array();
					}

					//Create arrays for all hours
					if (!array_key_exists($hour, $report[$operator][$day])) {
						$report[$operator][$day][$hour] = $quantity;
					}

					break;

				case 'MINUTE':
					$date = DateTime::createFromFormat('M d Y h:i:s:uA', $row['date']);
					$date = $date->format('d M - H:i');
					$operator = $row['operator'];
					$quantity = $row['quantity'];

					//Create arrays for all operators
					if (!array_key_exists($operator, $report)) {
						$report[$operator] = array();
					}

					//Create arrays for all days
					if (!array_key_exists($date, $report[$operator])) {
						$report[$operator][$date] = $quantity;
					}

					break;

				default:
					break;
			}
		}

		return $report;
	}

	protected function generateReport()
	{
		ob_start();

		$report = $this->getReport();

		switch ($this->groupBy) {
			case 'DAY':
				require_once(ROOT.DS.'templates/by_day.php');
				break;
			
			case 'HOUR':
				require_once (ROOT.DS.'templates/by_hour.php');
				break;

			case 'MINUTE':
				require_once(ROOT.DS.'templates/by_minute.php');
				break;

			default:
				break;
		}

		//Add to content
		if (is_null(Report::$content)) {
			Report::$content = ob_get_contents();			
		} else {
			Report::$content .= ob_get_contents();
		}

		//End of buffering output
		ob_end_clean();	
	}

	public static function saveReportsToFile()
	{
		ob_start();
		//Put array into HTML-template
		require_once(ROOT.DS.'/templates/main_template.php');
		file_put_contents('report.html', ob_get_contents());
		//End of buffering output
		ob_end_clean();
	}

	public function getStartDate() 
	{
		return $this->startDate;
	}

	public function getEndDate() 
	{
		return $this->endDate;
	}

	public function getStartDay() 
	{
		return $this->startDay;
	}

	public function endEndDay() 
	{
		return $this->ednDay;
	}	
}

