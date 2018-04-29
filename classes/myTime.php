<?php
	/*
	 * Creates and returns the time in Unix Time Stamp format
	 * created in order to prepare for easier "2038 PHP bug"
	 * transition in the future, in case if any problems arise.
	 */
class myTime {
	/**
	 * Connects to the database server and selects a database
	 * PHP4 compatibility layer for calling the PHP5 constructor.
	 * @uses CateringDatabase::__construct()
	 */
	function myTime($zone="Europe/London") {
		date_default_timezone_set($zone);
		return $this->__construct();
	}
	/** constructor
	*/
	public function __construct(){
		$this->h = 60 * $this->m; // 1 houre = 60 min
		$this->d = 24 * $this->h; // 1 day = 24 houres
		$this->w = 7 * $this->d; // 1 week = 7 days
	}
	/** Class fields
	*/
	public $m = 60; // 1 minute = 60 sec
	public $h = 3600; // 1 houre = 60 min
	public $d = ''; // 1 day = 24 houres
	public $w = ''; // 1 week = 7 day`s

	/**
	* Generates current date and time
	* @param $time either Unix time stamp or string containing time
	* @param int v modifier to affect output of the function
	* @return $v=0 Unix timestamp
	* @return $v=1 returns formated Unix timestamp
	* @return $v=2 returns formated date from provided Unix timestamp
	* @return $v=3 returns Unix timestamp from provided string containing time
	*/
	public static function getMyTime($v=0, $time=0) {
		date_default_timezone_set("Europe/London");
		//echo 'v: '.$v.' provided time: --  '.$time.' date formated-- '.date("Y-m-d H:i:s",$time).'  unix-- '.strtotime($time).'<br/>';
		//echo 'v: '.$v.' current:   '.time().' -- '.date("Y-m-d H:i:s",time()).'<br/>';
		if($v == 0){
			return (time()); // returns Unix timestamp
		}elseif($v == 1){
			return date("Y-m-d H:i:s",time()); // returns formated Unix timestamp
		}elseif($v == 2){
			return date("Y-m-d H:i:s",$time);// returns formated date from provided Unix timestamp
		}elseif($v == 3){
			return strtotime($time);// returns Unix timestamp from provided string containing time
		}
	}
	/**
	 * creates and returns current hour and minutes
	 * (where 100 == 60min) of the day
	 * out of the time stamp from getMyTime function
	 */
	function curHur(){
		$t = $this->getMyTime();
		$temp = (($t%$this->d)/$this->h);
		return (int)$temp+1;
	}
	/**
	 * @return int representation of begining of a current day in seconds(unix timestamp)
	 */
	function getCurDay(){
		$t = $this->getMyTime();
		$temp = $t%$this->d;
		$temp = $t - $temp;
		return $temp;
	}
	/**
	 * @return int representation of the hour in seconds
	 */
	function getD(){
		return $this->d;
	}
}
?>
