<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$b = '<br/>';
$mt = new myTime();
$patient_id = 0;
$pat_name = '';
$bed_id = 0;
$bed_name = '';
$itemsOrdered = array();
$disabled = '';
$cookie = array();
$p_allergies=array();
$msg = '';
$alrgDisabled = '';

// checking if cookie representing location has been set, redirecting otherwise
if(!isset($_COOKIE['catering'])){
	$disabled = 'disabled';
	echo 'test: cookie catering does not exists'.$b;
}else {
	echo 'test: cookie catering exists '.$b;
	$cookie = $_COOKIE['catering'];
	if(isset($cookie['bed'])){
		$bed_id = explode(' ',$cookie['bed'])[0];
		echo ' bed is set !!! '.$b;
	}else {
		$disabled = 'disabled';
		echo ' bed id is not set.... '.$b;
	}
}

// $cookie = $_COOKIE['catering'];
// $bed_id = explode(' ',$cookie['bed'])[0];
$meal = '';
$dbwhere = "orders";
$curDay = $mt->getCurDay();

$h = $mt->h;
$d = $mt->d;

// connection with database
$db = new myDB();

$sql = '';

// retriving data from database
if($bed_id != 0){
	$sql = "select b_name from beds where b_id =$bed_id";
	$result = $db->myQuery($sql);
	$row = $result->fetch_assoc();
	$bed_name = $row['b_name'];
	$sql = "select pb_id_patient, p_name, p_allergies from pat_bed
		join patients on (pb_id_patient = p_id)
		where pb_id_bed=$bed_id and pb_date_to=0";
	$result = $db->myQuery($sql);
	$row = $result->fetch_assoc();
	// echo 'the bed id: '.$bed_id.$b.'the patient id: ';print_r($row);echo $b;
	$patient_id = $row['pb_id_patient'];
	$pat_name = $row['p_name'];
	$p_allergies = $row['p_allergies'];
	echo " id bed: $bed_id -- id pat: $patient_id -- pat name: $pat_name allergens: $p_allergies".$b;
	$p_allergies = explode(',', $p_allergies);
	print_r($p_allergies);echo $b;
	if($patient_id == ""){
		echo "patient not assigned the the bed id: $bed_id".$b;
		$patient_id == 0;
		$disabled = "disabled";
	}
}

$content .= " welcome <b> $pat_name </b> to the Catering Menu System in <b>$bed_name</b>".$b;
//$t = getMyTime(3, '2017-03-07 17:50:10');
$t =  $mt->getMyTime();
$t1 = $t%$d/$h; // current houres of the time of the day where 60min = 100
echo 'current time, t1: '.$t1.$b;
if($t1 < TIME_BREAKFAST){
	echo "next meal : ".$meal ='breakfast'; echo $b;
	$sql = 'select * from menu_sets where ms_name = "Breakfast"';
}elseif($t1 < TIME_LUNCH){
	echo "next meal : ".$meal = 'lunch'; echo $b;
	$sql = 'select * from menu_sets where ms_name = "Lunch"';
}elseif($t1 < TIME_SUPPER){
	echo "next meal : ".$meal = 'supper'; echo $b;
	$sql = 'select * from menu_sets where ms_name = "Supper"';
}else {
	echo "next meal : ".'next day breakfast'; echo $b;
	$meal = 'breakfast';
	$sql = 'select * from menu_sets where ms_name = "Breakfast"';
	$t += $d;
	$curDay += $d;
}
if($bed_id != 0 && $patient_id != 0){
	$sql9 = "select o_id_item from orders where o_date_meal=$curDay and o_id_patient=$patient_id";
	$result3 = $db->myQuery($sql9);
	$itemsOrdered = array(); $i=0;
	while($row = $result3->fetch_assoc()){
		//$content .= 'id item: '.$row['o_id_item'].$b;
		$itemsOrdered[$i] = $row['o_id_item'];
		$i++;
	}
	//print_r($itemsOrdered);


	$order = array("o_id_patient" => $patient_id, "o_id_item" => '',
					"o_id_bed" => $bed_id, "o_date_meal" => $curDay,
					"o_date" => $mt->getMyTime(), "o_meal"=> $meal);

	if(isset($_POST['order'])){
		unset($_POST['order']);

		foreach($_POST as $index => $value){
			$con=false;
			for($j=0;$j<count($itemsOrdered); $j++){
				if($value == $itemsOrdered[$j]){
					echo ' it '.$itemsOrdered[$j].' val '.$value.$b;
					$con = true;
					array_splice($itemsOrdered, $j, 1);
					continue;
				}
			}
			if($con) continue;

			// $content .= "index: $index -- value: $value <br/>";
			$order["o_id_item"] = $value;
			$sql8 = sprintf(
					'insert into '.$dbwhere.' (%s) values ("%s")',
					implode(',',array_keys($order)),
					implode('","',array_values($order)) );

			 $db->myQuery($sql8);

			 $msg = 'id: '.$patient_id.' '.$lang['msg_mealordered'].$value;
			 echo ' msg order '.$msg.$b;

			 if(LOG_)$db->logDB($msg, $patient_id, 4, $sql8); // logging the event
		}
		echo 'items TO BE DELETED '; print_r($itemsOrdered); echo $b;
		//DELETE FROM `orders` WHERE `orders`.`o_id` = 1
		foreach($itemsOrdered as $itm){

			$sql7 = "delete from orders where o_meal=\"$meal\"
						and o_id_item=$itm and o_date_meal=$curDay";
			$db->myQuery($sql7);

			$msg ='id '.$itm.' '.$lang['msg_itemcancelled'].$patient_id;
			echo ' msg cancel '.$msg.$b;

			if(LOG_)$db->logDB($msg, $patient_id, 5, $sql7); // logging the event
			echo $sql7.$b;
		}
	}
}

$result = $db->myQuery($sql);
$res = $result->fetch_assoc();
$msid = $res['ms_id'];
echo 'menue set id: ms_id: '.$msid.$b;

echo ' menue lenght in days: '. $len = $res['ms_length'];
$from = $res['ms_date_from'];
$seq = (($t - $from )%($len*$d))/$d;
$seq = ($seq - ($seq - $seq%$d))+1;
echo $b.'sequence day: '.$seq.'<br/>';

$sql2 = "SELECT i_id, i_name, i_allergens FROM
		menu_sets JOIN menus ON
		(ms_id = m_id_menuset) JOIN menu_items ON
		(m_id = mi_id_menu) JOIN items ON
		(mi_id_product = i_id)
		 WHERE ms_id = $msid AND m_sequence = $seq ";

$result2 = $db->myQuery($sql2);

if($bed_id != 0 && $patient_id != 0){
	$sql9 = "select o_id_item from orders where o_date_meal=$curDay and o_id_patient=$patient_id";
	$result3 = $db->myQuery($sql9);
	$itemsOrdered = array(); $i=0;
	while($row = $result3->fetch_assoc()){
		//$content .= 'id item: '.$row['o_id_item'].$b;
		$itemsOrdered[$i] = $row['o_id_item'];
		$i++;
	}
}

$content .= '<form action="index.php" method="post"> ';
$i = 1;
$checked = '';
while($row = $result2->fetch_assoc()) {
	foreach($itemsOrdered as $item){
		if($row['i_id'] == $item) {
			// echo 'dupa '.$row['i_name'].' i_id '.$row['i_id'].' item '.$item.$b;
			$checked = 'checked';
		}
	}
	// checking if the allergen confilct exists !
	$msg = 'allergy conflict: ';
	if(!empty($p_allergies[0])){// || ($patient_id != 0)){
		
		foreach($p_allergies as $pall){

			if(in_array($pall, explode(',',$row['i_allergens']))){
				echo $b.' >>>>>- allergens conflict !! '.$pall.$b;
				$msg .= '<b>'.$pall.'</b> ';
				$alrgDisabled = 'disabled';
			}else{
					//$msg = 'no allergens....';
			}

		}
	}
	if($msg == 'allergy conflict: '){
		echo $b.">> no allergens conflict at all".$b;
		$msg = '';
	}else {
		echo $b.">>  allergens conflict detected....".$b;
	}
	$msg .= $b;

	$content .= '<input type="checkbox" name="item'.$i.'" value="'.
	$row['i_id'].'" '.$checked.' '.$alrgDisabled.'>'.$row['i_name'].'<br/>';

	$content .= $msg;
	$i++;
	$checked = '';
	$alrgDisabled = '';
}
$content .= '<input type="submit" value="Confirm !" name="order" '.$disabled.'></form>';


//print_r($order);

 $result->free();
 $result2->free();
?>
