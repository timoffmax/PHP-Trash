<?php
/**
* DB
*/
class DB
{
	private static $instance;
	private $connection;
	private $host = '95.69.245.26:14444';
	private $user = 'CallWayUser';
	private $password = 'Ghjnjnbg124';
	private $db_name = 'CallWay';


	private function __construct()
	{
		//Set params
		$dsn = "dblib:host={$this->host};dbname={$this->db_name}";
		$user = $this->user;
		$password = $this->password;

		//Create connection to DB
		$this->connection = new PDO($dsn, $user, $password);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	private function __clone() {}

	public static function getConnection()
	{
		if (!(self::$instance instanceof self)) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	public function query($sql, $placeholders = array(), $fetch = 'all')
	{
		if (is_null($this->connection)) {
			throw new Exception("Connection to database does not exists!");			
		}
		//Execute query
		$stmt = $this->connection->prepare($sql);
		$stmt->execute($placeholders);
		//How will be returned result?
		switch ($fetch) {
			case 'all':
				$result = $stmt->fetchAll();
				break;
			
			case 'row':
				$result = $stmt->fetch();
				break;
			case 'column':
				$result = $stmt->fetchAll(PDO::FETCH_COLUMN);
				break;
			case 'column_row':
				$result = $stmt->fetch(PDO::FETCH_COLUMN);
				break;
			default:
				throw new Exception("Unknown fetch value {$fetch}!");
				break;
		}
		return $result;
	}
	public function write($sql, $placeholders = array())
	{
		if (is_null($this->connection)) {
			throw new Exception("Connection to database does not exists!");			
		}
		$stmt = $this->connection->prepare($sql);
		foreach ($placeholders as $key => $value) {
			$stmt->bindValue($key, $value);
		}
		$stmt->execute();
		//Check result
		if ($stmt->rowCount()) {
			if ($this->connection->lastInsertId()) {
				return $this->connection->lastInsertId();
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
}

