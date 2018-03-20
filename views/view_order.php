<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$b = '<br/>';
	$sql = '';
	$sel_b = '';
	$sel_l = '';
	$sel_s = '';
	$sql_b = "o_meal LIKE '%'";
	$sql_l = '';
	$sql_s = '';

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
		echo $currentMeal ='breakfast'.$b;
		$currentMeal = "breakfast";
	}elseif($curHur < TIME_LUNCH){
		echo $currentMeal = 'lunch'.$b;
		$currentMeal = "lunch";
	}elseif($curHur < TIME_SUPPER){
		echo $currentMeal = 'supper'.$b;
		$currentMeal = "supper";
	}else {
		echo 'next day breakfast';
		$currentMeal = 'breakfast';
		$curDay += $d;
	}


	if(isset($_POST['show_orders'])){
		echo " show orders !!".$b;
		if(isset($_POST['breakfast'])){
			$sql_b = ' o_meal = \''.$_POST['breakfast'].'\' ';
			$sel_b = 'checked';
		}
		if(isset($_POST['lunch'])){
			if($sel_b == 'checked'){
				$sql_l = ' OR o_meal = \''.$_POST['lunch'].'\' ';
			}else{
				$sql_l = ' o_meal = \''.$_POST['lunch'].'\' ';
				$sql_b = '';
			}
			$sel_l = 'checked';
		}
		if(isset($_POST['supper'])){
			if($sel_b == 'checked' || $sel_l == 'checked'){
				$sql_s = ' OR o_meal = \''.$_POST['supper'].'\' ';
			}else{
				$sql_s = ' o_meal = \''.$_POST['supper'].'\' ';
				$sql_b = '';
			}
			$sel_s = 'checked';
		}

		// echo " breakfast checkbox:".$sql_b.':end';

		$date_from = $_POST['vo_date_from'];
		$date_to = $_POST['vo_date_to'];
		$date_from = $mt->getMyTime(3, $date_from);
		$date_to = $mt->getMyTime(3, $date_to);

		$sql = "SELECT o_id_item, i_name, COUNT(*)
				FROM orders JOIN items ON (o_id_item = i_id)
				WHERE o_date_meal > $date_from AND o_date_meal < $date_to AND ( $sql_b $sql_l $sql_s )
				GROUP BY o_id_item";
		//
		// $result = $db->myQuery($sql);
		// echo $b.$sql.$b;
		// while($row = $result->fetch_assoc()) {
		// 	$content .= $row['i_name'].' - '.$row['COUNT(*)'].$b;
		// }

	}else{
		echo " no show orders !".$b;

		$sql = "SELECT o_id_item, i_name, COUNT(*)
				FROM orders JOIN items ON (o_id_item = i_id)
				WHERE o_date_meal = $curDay AND o_meal = '$currentMeal'
				GROUP BY o_id_item";

		// $result = $db->myQuery($sql);
		// echo $b.$sql.$b;
		// $total = 0;
		// while($row = $result->fetch_assoc()) {
		// 	$content .= $row['i_name'].' - '.$row['COUNT(*)'].$b;
		// }
	}
	$result = $db->myQuery($sql);
	echo $b.$sql.$b;
	echo "SELECT o_id_item, i_name, COUNT(*) FROM orders JOIN items ON (o_id_item = i_id) JOIN beds ON (o_id_bed = b_id) WHERE o_date_meal LIKE '%' AND b_id_ward = 4 GROUP BY o_id_item";
	// $total = 0;
	// while($row = $result->fetch_assoc()) {
	// 	$content .= $row['i_name'].' - '.$row['COUNT(*)'].$b;
	// 	$total = $total + $row['COUNT(*)'];
	// }
	// $content .= ' total orders: '.$total;
	$content .= '<form enctype="multipart/form-data" action="index.php?page=view_order" method="post">';
	$content .= '<fieldset>';
	$content .= '<legend>Orders time range</legend>';
	$content .= '<label for="vo_date_from">Date from</label>';
	$content .= '<input type="text" id="vo_date_from" name="vo_date_from" value="'.$mt->getMyTime(2, $date_from).'" />';
	$content .= '<br />';
	$content .= '<label for="vo_date_to">Date to</label>';
	$content .= '<input type="text" id="vo_date_to" name="vo_date_to" value="'.$mt->getMyTime(2, $date_to).'" />';
	$content .= '<br />';
	$content .= '<input type="checkbox" name="breakfast" value="breakfast" '.$sel_b.'>breakfast <br/>';
	$content .= '<input type="checkbox" name="lunch" value="lunch" '.$sel_l.'>lunch<br/>';
	$content .= '<input type="checkbox" name="supper" value="supper" '.$sel_s.'>supper<br/>';
	$content .= '<input type="submit" value="Show orders" name="show_orders" >';
	$content .= '<input type="submit" value="Orders wards" name="orders_ward">';
	$content .= '</fieldset></form>';

	$total = 0;
	while($row = $result->fetch_assoc()) {
		$content .= $row['i_name'].' - '.$row['COUNT(*)'].$b;
		$total = $total + $row['COUNT(*)'];
	}
	$content .= ' total orders: '.$total;
	$content .= $_SERVER['SERVER_NAME'];

	echo $mt->getMyTime().$b;
	echo $mt->getMyTime(1).$b;
	echo $mt->getCurDay().$b;
	echo $mt->getMyTime(2, $mt->getCurDay() );

	$loc = getLocation();
	echo $b.'loc msg: '.$loc['msg'];
	print_r($loc);
?>
