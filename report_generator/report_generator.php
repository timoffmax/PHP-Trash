<?php
date_default_timezone_set('Europe/Kiev');
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

require_once "classes/db.php";
require_once "classes/report.php";

$reportByHour = new Report('Callback_through_Kyiv', '2017-03-01', '2017-04-01', 'DAY');
$reportByDay = new Report('Callback_through_Kyiv', '2017-03-01', '2017-04-01', 'HOUR');
$reportByMinute = new Report('Callback_through_Kyiv', '2017-03-01', '2017-04-01', 'MINUTE');

Report::saveReportsToFile();
