<?php
	displayErroros(); // error output
	$dev = ''; // for development output
	$loc = getLocation();
	$dev = print_r($loc, true);
	$locH = isset($loc['hospital_id']) ? $loc['hospital_id'] : false; // check for set catering cookie with location
	$b = '<br/>'; // html output line breaker
	$sql = '';
	$msg = '';
	$mt = new myTime();
	$curDay = $mt->getCurDay();
	$date_from = $curDay;
	$date_to = $mt->getMyTime();

	$d = $mt->getD();
	$curHur = $mt->curHur();
	$nxtDay = '';
	$currentMeal ='';

	$db = new myDB();

	if($curHur < TIME_BREAKFAST){
		$menuday = date('l', strtotime('today'));
		$dev .=  $currentMeal ='breakfast'.$b;
		$currentMeal = "breakfast";
	}elseif($curHur < TIME_LUNCH){
		$menuday = date('l', strtotime('today'));
		$dev .=  $currentMeal = 'lunch'.$b;
		$currentMeal = "lunch";
	}elseif($curHur < TIME_SUPPER){
		$menuday = date('l', strtotime('today'));
		$dev .=  $currentMeal = 'supper'.$b;
		$currentMeal = "supper";
	}else {
		$menuday = date('l', strtotime('tomorrow'));
		$dev .=  'next day breakfast';
		$currentMeal = 'breakfast';
		$curDay += $d;
	}
	$sql = "SELECT w_id, w_name FROM wards WHERE w_id_hospital = '$locH'";
	// $content .= $sql.$b;
	$res1 = $db->myQuery($sql);
	while($r = $res1->fetch_assoc()){
		$content .= '..........................<b>'.$r['w_name'].'</b>..........................'.$b;
		$wid = $r['w_id'];
		$sql2 = "SELECT b_id, b_name FROM beds WHERE b_id_ward = '$wid'";
		// $content .= $sql2.$b;
		$res2 = $db->myQuery($sql2);
		while($r2 = $res2->fetch_assoc()){
			$content .= ' .. '.$r2['b_name'];
			$bid = $r2['b_id'];
			$sql3 = "select pb_id_patient, p_name, p_allergies, p_type, p_nutrition, p_diet from pat_bed
				join patients on (pb_id_patient = p_id)
				where pb_id_bed=$bid and pb_date_to=0";
			// $content .= $sql3.$b;
			$res3 = $db->myQuery($sql3);
			$row = $res3->fetch_assoc();
			// print_r($row);
			// $dev .=  'the bed id: '.$bed_id.$b.'the patient id: ';print_r($row);$dev .=  $b;
			$patient_id = $row['pb_id_patient'];
			$pat_name = $row['p_name'];
			$content .= ' '.$pat_name.$b;
			$sql4 = "select o_id_item, i_name from orders join items on (o_id_item = i_id) where o_date_meal=$curDay
							and o_id_patient=$patient_id and o_meal='$currentMeal'";
			$res4 = $db->myQuery($sql4);
			while($r3 = $res4->fetch_assoc()){
				$content .= ' .. .. '.$r3['o_id_item'].' '.$r3['i_name'].$b;
			}
			$content .= $b;
		}
	}
	$content .= '................<b>TOTAL ORDERS for '.$menuday.' '.$currentMeal.'</b>.................'.$b;
	$sql = "SELECT o_id_item, i_name, COUNT(*)
			FROM orders JOIN items ON (o_id_item = i_id)
			WHERE o_date_meal = $curDay AND o_meal = '$currentMeal'
			GROUP BY o_id_item";
	$result = $db->myQuery($sql);
	$total = 0;
	while($row = $result->fetch_assoc()) {
		$content .= $row['i_name'].' - '.$row['COUNT(*)'].$b;
		$total = $total + $row['COUNT(*)'];
	}
	$content .= ' total quantity of orders: '.$total.$b;

	$content .= DEV ? $dev: '';
?>
