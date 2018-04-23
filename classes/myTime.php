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

	public function __construct(){
		$this->h = 60 * $this->m; // 1 houre = 60 min
		$this->d = 24 * $this->h; // 1 day = 24 houres
		$this->w = 7 * $this->d; // 1 week = 7 days
		// echo 'time created <br/>';
	}

	 // Class fields
	public $m = 60; // 1 minute = 60 sec
	public $h = 3600; // 1 houre = 60 min
	public $d = ''; // 1 day = 24 houres
	public $w = ''; // 1 week = 7 day`s

	// function myTime(){
		// $this->h = 60 * $this->m; // 1 houre = 60 min
		// $this->d = 24 * $this->h; // 1 day = 24 houres
		// $this->w = 7 * $this->d; // 1 week = 7 days
		// echo 'time created';
	// }

	/*
	* Generates current date and time
	* @param $time either Unix time stamp or string containing time
	* @return $v=0 Unix timestamp
	* @return $v=1 returns formated Unix timestamp
	* @return $v=2 returns formated date from provided Unix timestamp
	* @return $v=3 returns Unix timestamp from provided string containing time
	*/
	public static function getMyTime($v=0, $time=0) {
		// date_default_timezone_set('UTC');
		date_default_timezone_set("Europe/London");

		//echo 'v: '.$v.' provided time: --  '.$time.' date formated-- '.date("Y-m-d H:i:s",$time).'  unix-- '.strtotime($time).'<br/>';
		//echo 'v: '.$v.' current:   '.time().' -- '.date("Y-m-d H:i:s",time()).'<br/>';

		if($v == 0){
			// echo 'mt h: '.$this->h;
			// echo 'time(): '.time().' <br>';
			return (time()); // returns Unix timestamp
		}elseif($v == 1){
			// echo 'getMytime(1) '.$t = getMyTime().' <br>';
			$t = time();
			return date("Y-m-d H:i:s",$t); // returns formated Unix timestamp
		}elseif($v == 2){
			return date("Y-m-d H:i:s",$time);// returns formated date from provided Unix timestamp
		}elseif($v == 3){
			// echo " temp time 3  $time ".strtotime($time)."!! <br/>";
			// echo "S_time: ".$time." getMyTime 3: ".strtotime($time);
			return strtotime($time);// returns Unix timestamp from provided string containing time
		}
	}

	/*
	 * creates and returns current hour and minutes
	 * (where 100 == 60min) of the day
	 * out of the time stamp from getMyTime function
	 */

	function curHur(){
		$t = $this->getMyTime();
		// $td = ($t%$this->d);
		// echo "time: $t<br>";
		// echo "day: $this->d<br>";
		// echo 'time % day: ' .$td.'<br>';
		// echo "hour: $this->h<br>";
		// $th = $td/$this->h;
		// echo '(time % day)/hour: '.$th.'<br>';
		// $temp = (int) number_format(($t%$this->d)/$this->h);
		$temp = (($t%$this->d)/$this->h);
		// return round($temp,0,PHP_ROUND_HALF_UP); // would return houres only
		// return $temp; // returns houres and the minutes converted where 30min = 50
		return (int)$temp+1;
	}
	/*
	 * returns representation of begining of a current day in seconds(unix timestamp)
	 */
	function getCurDay(){
		$t = $this->getMyTime();
		$temp = $t%$this->d;
		$temp = $t - $temp;
		return $temp;
	}
	/*
	 * Returns representation of the hour in seconds
	 */
	function getD(){
		return $this->d;
	}
}
?>
