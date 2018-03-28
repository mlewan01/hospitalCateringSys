<?php
displayErroros(); // error output
$b = '<br/>';
$cookie = $_COOKIE['catering']; // retrivig a cookie

$hospital_id = explode(' ',$cookie['hospital'])[0];
$hospital_name = explode(' ',$cookie['hospital'],2)[1];

$ward_id = '';
$ward_id = explode(' ',$cookie['ward'])[0];
$ward_name = explode(' ',$cookie['ward'],2)[1];
$where = '';

$userId = 0;
$pat2id = '';
$dietRe = array('type','diet','nutrition', 'allergies');
$dietRe['type'] = '';$dietRe['diet'] = '';$dietRe['nutrition'] = '';$dietRe['allergies'] = array();
$err = '';
$msg = '';
$log = '';
$dev = '';
$allergens = array();
$type = array();
$diet = array();
$nutrition = array();
$otherall = array();

$db = new myDB();

$sql = "select v_value from valus where v_type = 'allergens'";
$result = $db->myQuery($sql);
$i=0;
while($row=$result->fetch_assoc()){
	$allergens[$i] = $row['v_value'];
	$i++;
}
$sql = "select v_value from valus where v_type = 'type'";
$result = $db->myQuery($sql);
$i=0;
while($row=$result->fetch_assoc()){
	$type[$i] = $row['v_value'];
	$i++;
}
$sql = "select v_value from valus where v_type = 'diet'";
$result = $db->myQuery($sql);
$i=0;
while($row=$result->fetch_assoc()){
	$diet[$i] = $row['v_value'];
	$i++;
}
$sql = "select v_value from valus where v_type = 'nutrition'";
$result = $db->myQuery($sql);
$i=0;
while($row=$result->fetch_assoc()){
	$nutrition[$i] = $row['v_value'];
	$i++;
}

if(isset($_POST['freebed'])){

	$bed_id = $_POST['bed_id'];
	$bed_name = $_POST['bed_name'];
	$patFreeName = $_POST['pat_name'];
	$sql6 = "update pat_bed set pb_date_to =".getMyTime()." where pb_id_bed=$bed_id and pb_date_to=0";
	$db->myQuery($sql6);
	$msg = $patFreeName.$lang['msg_patbedremoved'].$bed_name;
	if(LOG_)$db->logDB($msg, $userId, 2, $sql6); // logging the event

} elseif(isset($_POST['assign'])){

	// move the queries heere !!
	$sql1 = "SELECT count(pb_id) FROM pat_bed
			 JOIN beds ON (b_id = pb_id_bed )
			 WHERE pb_date_to = 0 and b_id_ward = $ward_id";

	$sql2 = "select count(b_id) from beds where b_id_ward = $ward_id";
	$res1 = ($db->myQuery($sql1))->fetch_assoc();
	$res2 = ($db->myQuery($sql2))->fetch_assoc();

	if($res1['count(pb_id)'] < $res2['count(b_id)'] ) {
		//$dev .= 'it is smaller !!';
		if(isset($_POST['bed']) && isset($_POST['pat']) ){
			$temp = explode(',' , $_POST['bed']);
			$bed_id = $temp[0];
			$bed_name = $temp[1];
			$temp = explode(',' , $_POST['pat']);
			$pat_id = $temp[0];
			$pat_number = $temp[1];
			$pat_name = $temp[2];
			$date = getMyTime();
			$sql8 = "insert into pat_bed (pb_id_bed, pb_id_patient, pb_date_from) values (\"$bed_id\", \"$pat_id\", \"$date\")";
			$db->myQuery($sql8);
			$msg = "Assigning patient: $pat_number - $pat_name to a bed: $bed_name";
			// logging the event
			if(LOG_)$db->logDB($msg, $userId, 1, $sql8);
		}else {
			$err = $lang['err_asgn'];
		}
	}else {
		$err = $lang['err_asgn'];
	}
}elseif(isset($_POST['save'])){
	$dev .= print_r($_POST, true);
	$dev .= '<br/> saving new dietary requirements...<br/>';
	if(isset($_POST['pat2'])){

		$otherall['other_allergies'] = $_POST['other_allergies'];

		$clean = sanitiseInput($otherall, array('', 'other_allergies'), array(0));

		if($clean[0] == false) {
			if(isset($_POST['tree_nuts'])) $_POST['tree nuts'] = 'tree nuts';
			$pat2id = $_POST['pat2'];
			unset($_POST['pat2']);
			unset($_POST['save']);
			$dev .= 'POST: '.print_r($_POST, true).$b;
			$dietRe = $_POST;
			$dietRe['allergies'] = '';
			foreach($allergens as $item){
				if(isset($_POST[$item])){
					$dietRe['allergies'] .= $item.',';
					unset($dietRe[$item]);
				}
			}

			$temp = explode(',' , $dietRe['other_allergies']);
			unset($dietRe['other_allergies']);
			foreach($temp as $item){
				$dietRe['allergies'] .= trim($item).',';
			}
			$dietRe['allergies'] = rtrim($dietRe['allergies'], ',');
			$sql = "update patients set p_type=\"$dietRe[type]\", p_diet=\"$dietRe[diet]\", p_nutrition=\"$dietRe[nutrition]\",
					p_allergies=\"$dietRe[allergies]\" where p_id=$pat2id";
			$dev .= 'allergies: '.$dietRe['allergies'];
			$resutl = $db->myQuery($sql);
			$dietTemp = $dietRe['allergies'];
			$dietRe['allergies'] = explode(',' , $dietRe['allergies']);
			$msg = $lang['msg_dietreqsaved'].$dietRe['type'].' '.$dietRe['diet'].' '.$dietRe['nutrition'].' '.$dietTemp ;
			if(LOG_)$db->logDB($msg, $userId, 3, $sql); // logging the event
		}else {
			$err = $lang['err_input'];
		}
	}else{
		$err = $lang['err_fetch'];
	}

}elseif(isset($_POST['fetch'])){
	$dev .= print_r($_POST, true);
	$dev .= 'fetching data ...<br/>';
	if(isset($_POST['pat2'])){
		$dev .= 'pat id: '.$pat2id = $_POST['pat2'];
		$dev .= '<br/>';
		$sql = "select p_type, p_diet, p_nutrition, p_allergies from patients where p_id = $pat2id";
		$result = $db->myQuery($sql);
		$row=$result->fetch_assoc();
		// $dev .= "data: "; print_r($row); $dev .= '<br/>'
		$dietRe["type"] = $row['p_type'];
		$dietRe['diet'] = $row['p_diet'];
		$dietRe['nutrition'] = $row['p_nutrition'];
		$dietRe['allergies'] = explode(',' , $row['p_allergies']);
		$dev .= print_r($dietRe, true);
	}else{
		$err = $lang['err_fetch'];
	}
}

// print_r($allergens); // log
$content .= '<div id="err"><h4>'.$err.'</h4></div>';
$content .= '<div id="msg"><h4>'.$msg.'</h4></div>';
if($ward_id != ''){

//-----------------------listing beds and assigned patients + free bed buttons-------------------------
	$sql1 = "select b_id, b_name from beds where b_id_ward=$ward_id";
	$result1 = $db->myQuery($sql1);

	while($row=$result1->fetch_assoc()){

		$bname = $row['b_name'].' : ';

		$sql3 = "select pb_id_patient from pat_bed where pb_date_to=0 AND pb_id_bed='$row[b_id]'";
		$result3 = $db->myQuery($sql3);

		while($row3 = $result3->fetch_assoc()){

			$sql_ = "select * from patients where p_id = '$row3[pb_id_patient]'";
			$res_ = $db->myQuery($sql_);
			$row_ = $res_->fetch_assoc();
			$con1 = "$row_[p_name], <b>type:</b> $row_[p_type], <b>diet</b>: $row_[p_diet], <b>nutrition:</b> $row_[p_nutrition], <b>allergies:</b> $row_[p_allergies]";

			$content .= '<form action="index.php?page=bed_pat_diet" method="post" role="form">
			<fieldset><legend>'.$row['b_name'].'</legend>'.$con1.'
			<input type="hidden" name="bed_id" value="'.$row['b_id'].'">
			<input type="hidden" name="bed_name" value="'.$row['b_name'].'">
			<input type="hidden" name="pat_name" value="'.$row_['p_name'].'">
			<input type="submit" value="Free" name="freebed">
			</fieldset></form>'; $bname = '';
		}
		if($bname != ''){
			$content .= '<form action="index.php?page=bed_pat_diet" method="post" role="form">
			<fieldset><legend>'.$row['b_name'].'</legend>empty
			</fieldset></form>';
		}
	}
	//$content .= '<br/>';
//--------------------------------patient bed assignment form-----------------------------------------
	$sql9 = "select pb_id, pb_id_bed, pb_id_patient from pat_bed where pb_date_to = 0";
	$result9 = $db->myQuery($sql9);
	$idpat = array();
	$idbed = array();
	$i = 0;
	while($row = $result9->fetch_assoc()){
		// $dev .= "<br/> $row[pb_id_bed] $row[pb_id_patient]";
		$idpat[$i] = $row['pb_id_patient'];
		$idbed[$i] = $row['pb_id_bed'];
		$i++;
	}
	//print_r($idpat);
	//print_r($idbed);
	$sql4 = "select p_id, p_number, p_name from patients where p_active = 1";
	$result4 = $db->myQuery($sql4);

	$sql5 = "select b_id, b_name from beds where b_id_ward = $ward_id";
	$result5 = $db->myQuery($sql5);

	$content .= '<form enctype="multipart/form-data" action="index.php?page=bed_pat_diet" method="post" role="form">
		<fieldset><legend>'.'patient bed assgnment'.'</legend>
		<div class="controlgroup"><label for="bed">Bed</label><select id="bed" name="bed">';
	while($row = $result5->fetch_assoc()) {
		if(in_array($row['b_id'], $idbed) ) continue ;
		$content .= "<option value=\"$row[b_id],$row[b_name]\">$row[b_name]</option>";
		$selected = '';
	}

	$content .= '</select></div><div class="controlgroup"><label for="pat">Patient</label><select id="pat" name="pat">';
	while($row = $result4->fetch_assoc()) {
		if(in_array($row['p_id'], $idpat) ) continue ;
		$content .= "<option value=\"$row[p_id],$row[p_number],$row[p_name]\">$row[p_number] - $row[p_name]</option>";
		$selected = '';
	}
	$content .= "</select></div>";
	$content .= '<input type="submit" value="Assign" name="assign"></fieldset></form>';

// ----------------------------save/fetch patient dietary requirements form------------------------------
	$sel = ''; // to preselect an item in the form after fetching data about patient
	$content .= '<form enctype="multipart/form-data" action="index.php?page=bed_pat_diet" method="post" role="form">
							<fieldset>';

	$sql7 = "select p_id, p_number, p_name from
			patients join pat_bed ON
			(pb_id_patient = p_id) join beds ON
			(pb_id_bed = b_id)
			WHERE p_active = 1 and pb_date_to = 0 and b_id_ward = $ward_id";

	$res7 = $db->myQuery($sql7);
	$content .= '<legend>dietary requirements</legend>';
	$content .= '<div class="controlgroup"><label for="pat2">'.'for patient:'.'</label>';
	$content .= '<select id="pat2" name="pat2">';
	while($row = $res7->fetch_assoc()) {
		if($row['p_id'] == $pat2id) $sel = ' selected';
		$content .= "<option value=\"$row[p_id]\"$sel>$row[p_number] - $row[p_name]</option>";
		$sel = '';
	}
	$content .= "</select></div>";
	$content .= '<div class="controlgroup">';
	$content .= '<label for="p_type">nhs or private </label><select id="type" name="type">';

	foreach($type as $item){
		if($item == $dietRe['type']) {
			$sel = " selected";
		}
		$content .= '<option value="'.$item.'" '.$sel.'>'.$item.'</option>';
		$sel = '';
	}
	$content .= '</select></div>';
	$content .= '<div class="controlgroup">';
	$content .= '<label for="p_diet">diet type </label><select id="diet" name="diet">';
	foreach($diet as $item){
		if($item === $dietRe['diet']) { $sel = " selected"; }
		$content .= '<option value="'.$item.'"'.$sel.'>'.$item.'</option>';
		$sel = '';
	}
	$content .= '</select></div>';
	$content .= '<div class="controlgroup">';
	$content .= '<label for="p_nutrition">nutrition </label><select id="nutrition" name="nutrition">';
	foreach($nutrition as $item){
		if($item === $dietRe['nutrition']) { $sel = " selected"; }
		$content .= '<option value="'.$item.'"'.$sel.'>'.$item.'</option>';
		$sel = '';
	}
	$content .= '</select></div>';
	$content .= '<div class="controlgroup">';
	$content .= '<label for="p_allergies">Allergies:</label>'; // <textarea type="text" id="p_allergies" name="p_allergies" placeholder=""></textarea><br/>';
	$customAllergen = '';
	$cus_id = 0;
	$content .= '<div class="checkgroup">';
	foreach($allergens as $item){
		foreach($dietRe['allergies'] as $alerg){
			if($item == $alerg) {
				$sel = ' checked';
				$cus_id++;
			}
		}
		$content .= '<div class="checkarea"><label>';
		$content .= '<input type="checkbox" name="'.$item.'" value="'.$item.'"'.$sel.'>'.$item;
		$content .= '</label></div>';
		$sel = '';
	}
	$content .= '</div></div>';
	for( ;$cus_id<count($dietRe['allergies']);$cus_id++){
		$customAllergen .= $dietRe['allergies'][$cus_id].',';
	}

	$customAllergen = rtrim($customAllergen, ', ');
	$content .= "<div class=\"controlgroup\"><label for=\"other_allergies\">other allergies: </label>
				<input type=\"text\" name=\"other_allergies\" value=\"$customAllergen\"/><br/>";
	$content .= '</div>';
	$content .= '<input type="submit" value="Fetch" name="fetch">';
	$content .= '<input type="submit" value="Save" name="save"></fieldset></form>';

}
//------------------------------------forms end------------------------------------------------------------

if(!empty($cookie)){

	$content .= "<br/> your current location is:</br>";
	$content .= " hospital name: $hospital_name , id: $hospital_id </br>";
	$content .= " ward name: $ward_name , id: $ward_id </br>";

	$content .= " to change your location go <a href=\"index.php?page=set_location\">here</a>.";
}else{
	$content .= "Your location is not set.
	Please set your location <a href=\"index.php?page=set_location\">here</a>.";
}
if(DEV) $content .= $dev;
//$w = ctype_alnum("");
//$w = 'apple';
//$dev .= $w.($w ? 'true' : 'false');
//$dev .= 'strlen'.(strlen($w));
?>
