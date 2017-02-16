<?php
date_default_timezone_set('Europe/Kiev');
/**
* Ata
*/
class Ata
{
	protected $peer;
	protected $ip;
	private $state;
	private $date;
	const NORMAL_STATE = 'Reachable';

	public function __construct($peer, $ip)
	{
		$this->peer = $peer;
		$this->ip = $ip;
		$this->date = date('Y-m-d H:i:s');
	}

	protected static function getAllAta()
	{
		//Define arrays for data processing
		$ata_peer_list = array();
		$list_of_ata = array();

		//Get all peers of ATA into array
		exec('/usr/sbin/asterisk -rx \'sip show peers\' | awk \'{print $1"|"$2"\n"}\' | grep -P "^\d{4}/"', $ata_peer_list);

		//Create object for each ATA and put all objects to array
		if (!empty($ata_peer_list)) {
			foreach ($ata_peer_list as $ata) {
				$ata_info = explode('|', $ata);
				$ata_peer = $ata_info[0];
				$ata_ip = $ata_info[1];

				$list_of_ata[] = new Ata($ata_peer, $ata_ip); 
			}
		}

		return $list_of_ata;
	}
	//If peer don't has IP - this is the problem
	protected static function defineAtaState(Ata $ata)
	{
		return preg_match("/^(\d{1,3}\.){3}\d{1,3}$/", $ata->ip) ? self::NORMAL_STATE : $ata->ip;
	}
	//Select all peers without ip
	protected static function getAllProblemAta()
	{
		$ata_list = static::getAllAta();
		if (!empty($ata_list)) {
			$problem_ata = array();
			foreach ($ata_list as $ata) {
				$ata->state = static::defineAtaState($ata);
				if ($ata->state !== self::NORMAL_STATE) {
					$problem_ata[] = $ata;
				}
			}
			return $problem_ata;	
		}
		return false;	
	}

	//
	public static function reportGenerate() {
		//Get array of problem ATA
		$ata_list = static::getAllProblemAta();
		//Start of buffering output
		ob_start();
		//Put array into HTML-template
		require 'report_generate.php';
		file_put_contents('/tmp/ata_report.html', ob_get_contents());
		//End of buffering output
		ob_end_clean();	
	}

	//Function for send report
	public static function sendReport() {
		//Get content from file
		$mail_text = file_put_contents('/tmp/ata_report.html');
		//Mark content type as text/html and send mail
		$headers = "Content-type: text/html; charset=utf-8\r\n";
		mail("support@ukrods.com.ua", "ATA Status Report", $mail_text, $headers); 
	}
}

//Generate and send report
Ata::reportGenerate();
Ata::sendReport();
