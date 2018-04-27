<?php
/* calculates current meal based on global variable set in comgig.php file
 * @return array where 0 is the meal, 1 date of the meal, 2 development output
 */
 function getCurrentMeal(){
	 $b = '</b>';
	 $mt = new myTime();
	 $t =  $mt->getMyTime();
	 $curDay = $mt->getCurDay();
	 $t1 = $mt->curHur();
	 $menuday = '';
	 $meal = '';
	 $dev = '';
	 if($t1 < TIME_BREAKFAST){
	 	$menuday = date('l', strtotime('today'));
	 	$dev .=  "next meal : ".$meal ='breakfast'; $dev .=  $b;
	 }elseif($t1 < TIME_LUNCH){
	 	$menuday = date('l', strtotime('today'));
	 	$dev .=  "next meal : ".$meal = 'lunch'; $dev .=  $b;
	 }elseif($t1 < TIME_SUPPER){
	 	$menuday = date('l', strtotime('today'));
	 	$dev .=  "next meal : ".$meal = 'supper'; $dev .=  $b;
	 }else {
	 	$menuday = date('l', strtotime('tomorrow'));
	 	$dev .=  "next meal : ".'next day breakfast'; $dev .=  $b;
	 	$meal = 'breakfast';
	 	$t += $d;
	 	$curDay += $d;
	 }
	 return array($meal, $menuday, $dev);
 }
/* sets qookie for the footer in index.php
 */
function footerLinkUpdate(){
	setcookie('catering[bed]', $_POST['h_beds'], time()+60*60*24*365, '', '', '', true);
}
/* formats location info for footer
 * @return array where 0 is location string and 1 location test
 */
function footerLocation($logged, $pid){
	// footer location and navigation
	$l = getLocation(); $n = 0;
	$lo = '<div class="foot2"><b>Location:</b> ';
	if($l['cookie_set'] === true){
		$lo .= "<b>hospital:</b>";
		if($l['hospital_set'] === true){
			$lo .= " $l[hospital_name] "; $n += 1;
		}else $lo .= " not set ";
		$lo .= "<b>ward:</b>";
		if($l['ward_set'] === true){
			$lo .= " $l[ward_name] "; $n += 3;
		}else $lo .= " not set ";
		$lo .= "<b>bed:</b>";
		if($l['bed_set'] === true){
			$lo .= " $l[bed_name] "; $n += 5;
		}else $lo .= " not set ";
	}else $lo .= 'is not set...';
	$lo .= '</div>';
	if($logged == 'logged' && $n>4 && $pid=='home'){
		// echo "footloc if $pid $logged ".$n.'<br>';
		$db = new myDB();
		$sql = "select b_id, b_name, b_id_ward from	beds	where b_id = $l[bed_id]";
		$res = $db->myQuery($sql);
		$res = $res->fetch_assoc();
		// echo "id ward: $res[b_id_ward]<br>";
		$sql = "select b_id, b_name from beds
		join wards on (b_id_ward = w_id) where w_id = $res[b_id_ward]";
		$res = $db->myQuery($sql);
		$lo .= '<form action="index.php" method="post" role="form">';
		$lo .= "<select name=\"h_beds\" id=\"h_beds\">";
			while($row = $res->fetch_assoc()){
				$lo .= "<option value=\"$row[b_id] $row[b_name]\">$row[b_name]</option>";
			}
		$lo .= '<input type="submit" name="h_foot" id="h_foot" value="Change"/>';
		$lo .= '</select></form>';
	}// else echo "footloc else $pid $logged ".$n.'<br>';
	return array($lo, $n);
}
/* generates navigations based no users rights
*  @param $access access level from 0 to 10
*  $return html formated links acording to access level
*/
function navigation($access){
	$nav = '';
	$nava = array(
		'menu' => '<li><a href="index.php">[+m1+]</a></li>',
		'items' => '<li><a href="index.php?page=items">[+m2+]</a></li>',
		'menus' => '<li><a href="index.php?page=menus">[+m3+]</a></li>',
		'menu_items' => '<li><a href="index.php?page=menu_items">[+m13+]</a></li>',
		'menu_sets' => '<li><a href="index.php?page=menu_sets">[+m14+]</a></li>',
		'orders' => '<li><a href="index.php?page=orders">[+m4+]</a></li>',
		'hospitals' => '<li><a href="index.php?page=hospitals">[+m5+]</a></li>',
		'wards' => '<li><a href="index.php?page=wards">[+m6+]</a></li>',
		'beds' => '<li><a href="index.php?page=beds">[+m7+]</a></li>',
		'patients' => '<li><a href="index.php?page=patients">[+m8+]</a></li>',
		'users' => '<li><a href="index.php?page=users">[+m9+]</a></li>',
		'pat_bed' => '<li><a href="index.php?page=pat_bed">[+m10+]</a></li>',
		'pat_diet' => '<li><a href="index.php?page=pat_diet">[+m11+]</a></li>',
		'set_location' => '<li><a href="index.php?page=set_location">[+m15+]</a></li>',
		'bed_pat_diet' => '<li><a href="index.php?page=bed_pat_diet">[+m16+]</a></li>',
		'view_order' => '<li><a href="index.php?page=view_order">[+m17+]</a></li>',
		'logs' => '<li><a href="index.php?page=logs">[+m18+]</a></li>',
		'login' => '<li><a href="index.php?page=login">[+m19+]</a></li>'
	);
	if($access >= 0){ // for not confirmed registred user
		$nav = $nava['menu'];
	}
	if($access >= 1){ // for confiremd registred user with minimum access level like Ward Host
		$nav .= $nava['set_location']; // $nav .= $nava['bed_pat_diet'];
		$nav .= $nava['view_order'];
	}
	if($access >= 2){ // for confiremd registred user with minimum access level like Health Care Assistant
		$nav .= $nava['bed_pat_diet']; // $nav .= $nava['users'];
	}
	if($access >= 3){
		$nav .= $nava['items'];
		$nav .= $nava['menus'];
		$nav .= $nava['menu_sets'];
		$nav .= $nava['menu_items'];
	}
	if($access >= 4){
		$nav .= $nava['hospitals'];
		$nav .= $nava['wards'];
		$nav .= $nava['beds'];
		$nav .= $nava['patients'];
		$nav .= $nava['logs'];
	}
	if($access >= 5){
		$nav .= $nava['users'];
	}
	if($access >= 6){
		$nav .= $nava['orders'];
		$nav .= $nava['pat_bed'];
		$nav .= $nava['pat_diet'];
	}
	$nav .= $nava['login'];
	return $nav;
}
/** displays php errors and wornings
*/
function displayErroros(){
	if(ERR){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}else{
		error_reporting(0);
		ini_set('display_errors', 0);
	}
}
// returns an array with current location set in cookie
function getLocation(){
	$cookie = array(); $b = '<br/>';
	$out = array();
	if(isset($_COOKIE['catering'])){
		$out['msg'] = "cookie catering exists".$b;
		$cookie = $_COOKIE['catering'];
		$out['cookie_set'] = true;
		if(isset($cookie['hospital'])){
			$out['hospital_id'] = explode(' ',$cookie['hospital'])[0];
			$out['hospital_name'] = explode(' ',$cookie['hospital'],2)[1];
			$out['hospital_set'] = true;
		}else $out['hospital_set'] = false;
		if(isset($cookie['ward'])){
			$out['ward_id'] = explode(' ',$cookie['ward'])[0];
			$out['ward_name'] = explode(' ',$cookie['ward'],2)[1];
			$out['ward_set'] = true;
		}else $out['ward_set'] = false;
		if(isset($cookie['bed'])){
			$out['bed_id'] = explode(' ',$cookie['bed'])[0];
			$out['bed_name'] = explode(' ',$cookie['bed'],2)[1];
			$out['bed_set'] = true;
		}else $out['bed_set'] = false;
	}else{
		$out['msg'] = "cookie catering does not exists".$b;
		$out['cookie_set'] = false;
	}
	return $out;
}
// checks given string for allowed alpha-numeric and additional characters
// returns true if string contain allowed characters false otherwise
function sanitise($in){

	$repl = array('@', ' ', '.', ',', '?', '!',':', '-', '&', '_'); // allowed characters in sanitise functions
	$temp = str_replace($repl, '', $in);
	if(ctype_alnum($temp)){
		return true; // if contains allowed characters
	}else{
		return false; // if contains not allowed characters
	}
}
// checks given string for allowed alpha-numeric and additional characters
// function ready to be used with input from forms operating on database tables
function sanitiseInput($post, $arr, $req=null){

	$dev = "sanitise function".'<br/>';
	// $clean array fields: error, title, description

	$repl = array('@', ' ', '.', ',', '?', '!',':', '-', '&', '_');// allowed characters in sanitise functions
	$len = count($arr);
	$clean = array($len);
	$msg = '<br/>1<br/>';

	for($i = 1; $i < $len; $i++){
		$msg .= "2...$arr[$i]<br/>";
		if(isset($post[$arr[$i]])){
			$msg .= '3<br/>';
			$temp = str_replace($repl, '', $post[$arr[$i]]);
			// sanitisation
			if(ctype_alnum($temp)){
				$msg .= '4<br/>';
				$clean[$i] = $post[$arr[$i]];
				$clean[0]=false;
			}else {
				$msg .= '5<br/>';
				$clean[1] = $msg;
				if(strlen($temp) == 0 && $req[$i-1]==1){
					$msg .= '6<br/>';
					$clean[0] = 'fu_notAllProvided';
					return $clean;
				} elseif(strlen($temp) == 0){
					$msg .= '7<br/>';
					// added for bed_pat_diet compactibility it should allowed to accept empty strings
					$clean[0] = false;
					continue;
				}
				$msg .= '8<br/>';
				$clean[0] = 'fu_notAllProvided';
				return $clean;
			}
		}else {
			$msg .= '9<br/>';
			$clean[0] = 'fu_notAllProvided';
			$clean[1] = $msg;
			//return $clean;
		}
	}
	 $clean[1] = $msg;
	return $clean;
}

// to work with templates. needs file path and an array with key and value
// $readFile if set ot 1 $file is a file path
// if set ot "0" $file is a file content
 function tpl($readFile, $file, $arr, $tarea=null){

	if($readFile == 1){
		$out=file_get_contents($file);

		foreach ($arr as $key => $value){
			$out = str_replace($key, $value, $out);
		}

		//echo "tpl function read file";
		return $out;
	}elseif($readFile == 2) {
		$i = 0;
		foreach ($arr as $key => $value){
			// echo "key: $key -- value: $value<br/>";
			if($tarea[$i] == 0){
				$file = str_replace('name="'.$key.'"', ' name="'.$key.'" '.'value="'.$value.'"', $file);
			}elseif($tarea[$i] == 1) {
				$file = str_replace($key.'"></t', $key.'">'.$value.'</t', $file);
			}elseif($tarea[$i] == 2) {
				$file = str_replace('name="'.$key.'"', ' name="'.$key.'" '.'value="'.myTime::getMyTime(2,$value).'"', $file);
			}
			$i++;
		}
		//echo "tpl function no red file nessesery";
		return $file;
	}elseif($readFile == 3){
		foreach ($arr as $key => $value){
			// echo "key: $key -- value: $value<br/>";
			$file = str_replace($key, $value, $file);
		}
		//echo "tpl function read file";
		return $file;
	}
}
// becarefule where you calling this function from.
function autoloader(){
	spl_autoload_register('myAutoloader');
}
function myAutoloader($class){
	include 'classes/'.$class.'.php';// my need to adjust path acordingly
}
/**
 * function to return a language array
 * $param
 * $return
 */
function arr_lang($words){

	global $lang, $config, $dir;

	$len = count($words);
	$f = 'f_';
	$p = '[+';
	$s = '+]';
	$a_lang = array();

	for($i=0; $i<$len; $i++){

		$a_lang[$p.$f.$words[$i].$s] = $lang[$f.$words[$i]];
	}

	return $a_lang;
}
/**
 * function will add prefixes to the elements of the array
 * @param $arr array of text elements for witch prefixes will be added
 * @param $pref prefix to be added to the elements of the array
 * @return an array with elemens starting with the prefix
 */
function addPrefix($arr, $pref){
	$ar = array(count($arr));
	for($i=0; $i<count($arr); $i++){
		$ar[$i] = $pref.$arr[$i];
	}
	return $ar;
}
/**
* Functon generating a form given following parameters:
* @param $data[0] table prefix
* @param $data[1] page name
* @param $data[2] form fileds list
* @param $data[3] button names list
* @param $data[4] fields type: input/textarea 0/1
*/
function makeForm($data){
	$prefix1 = 'f_';
	$prefix2 = $data[0];
	$page = $data[1];
	$fields = $data[2];
	$buttons = $data[3];
	$fieldType = $data[4];
	$flen1 = count($fields);
	$flen2 = count($buttons);
	$content = '';

	$content .= '<form enctype="multipart/form-data" action="index.php?page='.$page.'" method="post" role="form">';
	$content .= '<fieldset>';
	$content .= '<legend>[+'.$prefix1.$prefix2.'legend+]</legend>';

	for($i=0; $i<$flen1; $i++){
		$content .= '<div class="controlgroup">';
		if($fieldType[$i]==0){
			$content .= '<label for="'.$prefix2.$fields[$i].'">[+'.$prefix1.$fields[$i].'+]</label>';
			$content .= '<input type="text" id="'.$prefix2.$fields[$i].'" name="'.$prefix2.$fields[$i].'" />';
		}elseif($fieldType[$i] == 1){
			$content .= '<label for="'.$prefix2.$fields[$i].'">[+'.$prefix1.$fields[$i].'+]</label>';
			$content .= '<textarea type="text" placeholder="'.$fields[$i].'" id="'.$prefix2.$fields[$i].'" name="'.$prefix2.$fields[$i].'"></textarea>';
		}elseif($fieldType[$i]==2){
			$content .= '<label for="'.$prefix2.$fields[$i].'">[+'.$prefix1.$fields[$i].'+]</label>';
			$content .= '<input type="text" id="'.$prefix2.$fields[$i].'" name="'.$prefix2.$fields[$i].'" value="'.myTime::getMyTime(1).'" />';
		}
		$content .= '</div>';
	}

	for($i=0; $i<$flen2; $i++){
		if($buttons[$i] == 'reset'){
			$content .= '<input type="reset" value="[+'.$prefix1.$buttons[$i].'+]"/>';
			continue;
		}
		$content .= '<input type="submit" value="[+'.$prefix1.$buttons[$i].'+]" name="'.$buttons[$i].'" />';
	}
	$content .= '<br/><small>[+f_msg+]</small>';
	$content .= '</fieldset></form>';
	return $content;
}

/*
* Generates and returns SQL query for returning data of a record given ID
* @param $to_clean an array of data to be checked agains database data
* @param $required an array of intigers where 1 stands for required field and 0 not required
* @param $dbwhere a database's table name to be quered
* @param $id database's table's targeted ID field
* @return an arrey consisting of (0)dev output, (1)normal output, (2)SQL querry
*/
function formFetch($to_clean, $required, $dbwhere, $id){
		$msg = 'Fetching !!';
		$msge = '';
		$sql = '';

		$clean = array(2);
		$clean = sanitiseInput(array_slice($_POST,0,1), array_slice($to_clean,0,2), array(1));

		if($clean[0]==false){
			$msg .= 'clean !!<br/>';
			if(isset($_POST[$id]) && strlen($_POST[$id])!=0){
				unset($_POST['fetch']);
				$sql = 'select '.implode(',',array_keys($_POST)).
						" from $dbwhere where $id=".$_POST[$id];
				$msg .= $sql;
				//$result = $db->myQuery($sql)->fetch_assoc();
				$msg .= $clean[1];
				//print_r($result);
				// $form = tpl(2, $form, $result, $txtField);
				//$result->free();
			}else $msg .= $msge .= 'id not supplayed or incorret. pls, try again !';

		}else {
			$msg .= 'not clean !!<br/>';
			$msg .= $clean[0];
			$msg .= $lang[$clean[0]];
			$msge .= $lang[$clean[0]];
			$msg .= $clean[1];
		}
		return array($msg, $msge, $sql);
}
/*
* Generates and returns SQL query for deleting a record with given ID
* @param $to_clean an array of data to be checked agains database data
* @param $required an array of intigers where 1 stands for required field and 0 not required
* @param $dbwhere a database's table name to be quered
* @param $id database's table's targeted ID field
* @return an arrey consisting of (0)dev output, (1)normal output, (2)SQL querry
*/
function formDelete($to_clean, $required, $dbwhere, $id){
	$dir = dirname(dirname(__FILE__));

	require $dir.'/lang/'.LANGUAGE.'.php';
	$msg = 'Deleting !!';
	$msge = '';
	$sql = '';

	$clean = array();
	$clean = sanitiseInput(array_slice($_POST,0,1), array_slice($to_clean,0,2), array(1));

	if($clean[0]==false){
		$msg .= 'clean !!<br/>';
		if(isset($_POST[$id]) && strlen($_POST[$id])!=0){
			$sql = "DELETE FROM $dbwhere WHERE $dbwhere.`$id` = $_POST[$id]";
			// $msg .= $db->myQuery($sql);
			$msg .= $sql;
		}else $msg .= $msge .= 'id not supplayed or incorret. pls, try again !';
	}else {
		$msg .= 'not clean !!<br/>';
		$msg .= $clean[0];
		$msg .= $lang[$clean[0]];
		$msge .= $lang[$clean[0]];
		$msg .= $clean[1];
	}
	return array($msg, $msge, $sql);
}
/*
* Generates and returns SQL query for deleting a record with given ID
* @param $to_clean an array of data to be checked agains database data
* @param $required an array of intigers where 1 stands for required field and 0 not required
* @param $dbwhere a database's table name to be quered
* @param $id database's table's targeted ID field
* @param $fieldType
* @return an arrey consisting of (0)dev output, (1)normal output, (2)SQL querry
*/
function formAdd($to_clean, $required, $dbwhere, $id, $fieldType=null){
	$dir = dirname(dirname(__FILE__));

	require $dir.'/lang/'.LANGUAGE.'.php';

	$msg = 'Adding !!';
	$msge = '';
	$sql = '';

	$clean = array();
	// TODO cleaned falues not used, using $_POST unsanitized values later
	$clean = sanitiseInput($_POST, $to_clean, $required);

	if($fieldType != 0){
		for($i=0;$i<count($fieldType);$i++){
			if($fieldType[$i] == 2) {
				//echo "the time submited: ".$clean[$i+1].'<br/>';
				$clean[$i+1] = myTime::getMyTime(3,$clean[$i+1]);
			}
		}
	}
	if($clean[0] == false){
		$msg .= 'is clean !!<br/>';

		if(isset($_POST[$id]) && strlen($_POST[$id])!=0){
// todo: adding with provided id,
// only for development, shuld be distabled in production system
			$msg .= " is set !! post id lenght: ".strlen($_POST[$id]).'<br/>';
		}else {
			$msg .= " unsetting !! ";
			unset($_POST[$id]);
		}
		unset($_POST['add']);
		$sql = sprintf(
				'insert into '.$dbwhere.' (%s) values ("%s")',
				implode(',',array_keys($_POST)),
				implode('","',array_values($_POST)) );
		$msg .= $sql;
		// $msg .= $db->myQuery($sql);
		$msg .= $clean[1];

	}else {
		$msg .= 'not clean !!<br/>';
		$msg .= $clean[0];
		$msg .= $lang[$clean[0]];
		$msge .= $lang[$clean[0]];
		$msg .= $clean[1];
	}
	return array($msg, $msge, $sql);
}
/*
* Generates and returns SQL query for deleting a record with given ID
* @param $to_clean an array of data to be checked agains database data
* @param $required an array of intigers where 1 stands for required field and 0 not required
* @param $dbwhere a database's table name to be quered
* @param $id database's table's targeted ID field
* @return an arrey consisting of (0)dev output, (1)normal output, (2)SQL querry
*/
function formEdit($to_clean, $required, $dbwhere, $id, $fieldType=null){
	$dir = dirname(dirname(__FILE__));

	require $dir.'/lang/'.LANGUAGE.'.php';

	$msg = 'Editing !!';
	$msge = '';
	$sql = '';
	$df = 0;

	$clean = array();
	$clean = sanitiseInput($_POST, $to_clean, $required);

	if($clean[0]==false){

		$ftlen = count($fieldType);
		// echo 'field type length:  '.$ftlen.'<br/>';

		if($fieldType != null){
			for($i=0;$i<$ftlen;$i++){
				// echo '327 functions, to_clean[i+1]: '.$_POST[$to_clean[$i+1]].'<br/>';
				if($fieldType[$i] == 2) {
					//echo "the time submited: ".$clean[$i+1].'<br/>';
					$df = $to_clean[$i+1];
					$_POST[$df] = myTime::getMyTime(3,$_POST[$df]);
				}
			}
		}

		$msg .= "clean !!! ";
		$h_id = $_POST[$id];
		unset($_POST[$id]);
		unset($_POST['edit']);

		$sql = 'update '.$dbwhere.' set ';
		foreach($_POST as $key => $val){
			$sql .= $key.'="'.$val.'", ';
		}
		$sql = rtrim($sql, ', ');
		$sql .= " where $id=".$h_id;

		$msg .= '<br/>'.$sql;

		// $msg .= $db->myQuery($sql);
		$msg .= $clean[1];
		$_POST[$id] = $h_id;
	}else {
		$msg .= 'not clean !!!!<br/>';
		$msg .= $clean[0];
		$msg .= $lang[$clean[0]];
		$msge .= $lang[$clean[0]];
		$msg .= $clean[1];
	}
	return array($msg, $msge, $sql);
}
/*
* Generates current date and time
* @param $time either Unix time stamp or string containing time
* @return $v=0 Unix timestamp
* @return $v=1 returns formated Unix timestamp
* @return $v=2 returns formated date from provided Unix timestamp
* @return $v=3 returns Unix timestamp from provided string containing time
*/
function getMyTimeFunction($v=0, $time=0) {
	echo 'function getMyTime executed <br>';
	// date_default_timezone_set('UTC');
	date_default_timezone_set("Europe/London");
	//echo 'v: '.$v.' provided time: --  '.$time.' date formated-- '.date("Y-m-d H:i:s",$time).'  unix-- '.strtotime($time).'<br/>';
	//echo 'v: '.$v.' current:   '.time().' -- '.date("Y-m-d H:i:s",time()).'<br/>';

	if($v == 0){
		return time(); // returns Unix timestamp
	}elseif($v == 1){
		return date("Y-m-d H:i:s",time()); // returns formated Unix timestamp
	}elseif($v == 2){
		return date("Y-m-d H:i:s",$time);// returns formated date from provided Unix timestamp
	}elseif($v == 3){
		return strtotime($time);// returns Unix timestamp from provided string containing time
	}
}

/*
 * Helps preparing data for to enter to the log table
 *
 * @param String $l_msg message to be logged
 * @param Int $l_id_staff a number representing stuff u_id in users table
 * @param Int $l_type a number representing a type of the log event
 * @param Int $l_date a Unix time stamp of the event, autogenerated if not provided
 * @param String $l_sql a SQL query associated with the event, optional
 */
 function myLog($l_msg, $l_id_staff, $l_type, $l_sql='', $l_date=0){
	 if($l_date == 0) $l_date = myTime::getMyTime();
	 $log['l_msg'] = $l_msg;
	 $log['l_sql'] = str_replace("\"", "\\\"", $l_sql);
	 $log['l_date'] = $l_date;
	 $log['l_id_staff'] = $l_id_staff;
	 $log['l_type'] = $l_type;

	 return $log;
 }
 function changePassword($userId, $username, $newpass, $userreg,$index){
	 $clean = array();
	 $clean[$index] = $newpass;
	 $clean = sanitiseInput($clean, array('',$index),array(0,1));
	 if($clean[0]==false){
		 $db = new myDB();
		 $nonce = md5('registration-'.$username.$userreg.NONCE_SALT);
		 $userpass = $db->hash_password($newpass, $nonce);
		 $sql3 = 'UPDATE users SET u_password = "'.$userpass.'" WHERE u_id = '.$userId;

		 $results = $db->myQuery($sql3);
		}else {
		 $msg = 'not clean !!!!<br/>';
		 $msg .= $clean[0];
		 $msg .= $lang[$clean[0]];
		 $msg .= $clean[1];
	 }
 }
?>
