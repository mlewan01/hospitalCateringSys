<?php
displayErroros(); // error output

$b = '<br/>';
$msg = ''; $err=''; $dev='';
$msg2= ''; // 'Currently excample data in hostptal 2, ward 22, bed 2201/2';
$hospital_id = '';
$ward_id = '';
$bed_id = '';
$where = '';
$bed_name = '';
$ward_name = '';
$hospital_name = '';
$cookie = array();
if(isset($_COOKIE['catering'])){
	$dev .= "cookie catering exists".$b;
	$cookie = $_COOKIE['catering'];
	if(isset($cookie['hospital'])){
		$hospital_id = explode(' ',$cookie['hospital'])[0];
		$hospital_name = explode(' ',$cookie['hospital'],2)[1];
	}
	if(isset($cookie['ward'])){
		$ward_id = explode(' ',$cookie['ward'])[0];
		$ward_name = explode(' ',$cookie['ward'],2)[1];
	}
	if(isset($cookie['bed'])){
		$bed_id = explode(' ',$cookie['bed'])[0];
		$bed_name = explode(' ',$cookie['bed'],2)[1];
	}
}else{
	$dev .= "cookie catering does not exists".$b;
}

$cookie_time = time()+60*60*24*365;

if(isset($_POST['selecthospital'])){
	setcookie('catering[hospital]', $_POST['hospital'], $cookie_time, '', '', '', true);
	$hospital_id = explode(' ',$_POST['hospital'],2)[0];
	$hospital_name = explode(' ',$_POST['hospital'],2)[1];
	$dev .= 'hospital</br>';

	setcookie('catering[ward]', '', -3600, '', '', '', true);
	setcookie('catering[bed]', '', -3600, '', '', '', true);
	$ward_id = '';
	$ward_name = '';
	$bed_id = '';
	$bed_name = '';
	header("Location: ".getURL('?page=set_location')['url']); // HACK: since the new value assigned to cookie is not immidiatly accessible
}elseif(isset($_POST['selectward'])){
	setcookie('catering[ward]', $_POST['ward'], $cookie_time, '', '', '', true);
	$ward_id = explode(' ',$_POST['ward'],2)[0];
	$ward_name = explode(' ',$_POST['ward'],2)[1];
	$dev .= 'ward</br>';

	setcookie('catering[bed]', '', -3600, '', '', '', true);
	$bed_id = '';
	$bed_name = '';
	header("Location: ".getURL('?page=set_location')['url']); // HACK: since the new value assigned to cookie is not immidiatly accessible
}elseif(isset($_POST['selectbed'])){
	setcookie('catering[bed]', $_POST['bed'], $cookie_time, '', '', '', true);
	$bed_id = explode(' ',$_POST['bed'],2)[0];
	$bed_name = explode(' ',$_POST['bed'],2)[1];
	$dev .= 'bed</br>';
	header("Location: ".getURL('?page=set_location')['url']);  // HACK: since the new value assigned to cookie is not immidiatly accessible
}elseif(isset($_POST['resetloccookie'])){
	setcookie('catering[hospital]', '', -3600, '', '', '', true);
	setcookie('catering[ward]', '', -3600, '', '', '', true);
	setcookie('catering[bed]', '', -3600, '', '', '', true);
	$hospital_id = '';
	$ward_id = '';
	$bed_id = '';
	$where = '';
	$bed_name = '';
	$ward_name = '';
	$hospital_name = '';
	$cookie = array();
	header("Location: ".getURL('?page=set_location')['url']);  // HACK: since the new value assigned to cookie is not immidiatly accessible
}
$content .= '<div id="err"><h4>'.$err.'</h4></div>';
$content .= '<div id="msg"><h4><font color="red">'.$msg2.'</font></h4></div>';

$db = new myDB();

$selected ='';

$sql = "select h_id, h_name from hospitals";
$result = $db->myQuery($sql);
$content .= '<form enctype="multipart/form-data" action="index.php?page=set_location" method="post" role="form">
			<fieldset><legend>Set location</legend><div class="controlgroup">
			<label for="hospital">Hospital:</label>
			<select id="hospital" name="hospital">';
while($row = $result->fetch_assoc()) {
	if($hospital_id == $row['h_id']) $selected = ' selected ';
	$content .= "<option value=\"$row[h_id] $row[h_name]\"$selected>$row[h_name]</option>";
	$selected = '';
}
$content .= '</select><input type="submit" value="Select" name="selecthospital"></div>';//'</form>';

if($hospital_id != '') $where = " where w_id_hospital = '$hospital_id'";
$sql = "select w_id, w_name from wards$where";
$where = '';
$result = $db->myQuery($sql);
//$content .= '<form enctype="multipart/form-data" action="index.php?page=set_location" method="post" role="form">
	$content .= '<div class="controlgroup"><label for="ward">Ward:</label>
			<select id="ward" name="ward">';
while($row = $result->fetch_assoc()) {
	if($ward_id == $row['w_id']) $selected = ' selected ';
	$content .= "<option value=\"$row[w_id] $row[w_name]\"$selected>$row[w_name]</option>";
	$selected = '';
}
$content .= '</select><input type="submit" value="Select" name="selectward"></div>';//</form>';

if($ward_id != '') $where = " where b_id_ward = '$ward_id'";
$sql = "select b_id, b_name from beds$where";
$where = '';
$result = $db->myQuery($sql);
//$content .= '<form enctype="multipart/form-data" action="index.php?page=set_location" method="post" role="form">
$content .= '<div class="controlgroup"><label for="bed">Bed:</label>
			<select id="bed" name="bed">';
while($row = $result->fetch_assoc()) {
	if($bed_id == $row['b_id']) $selected = ' selected ';
	$content .= "<option value=\"$row[b_id] $row[b_name]\"$selected>$row[b_name]</option>";
	$selected = '';
}
$content .= '</select><input type="submit" value="Select" name="selectbed"></div></fieldset></form>';

if(!empty($cookie) || $hospital_id != '' || $ward_id != '' || $bed_id != ''){

	$content .= '<form enctype="multipart/form-data" action="index.php?page=set_location" method="post">
								<fieldset><legend>Reset location</legend>';
	$content .= '<label for="resetloccookie">Reset location: </label>
							<input type="submit" value="reset" name="resetloccookie"></fieldset></form>';
}

function resetLocCookies(){
	setcookie('catering[hospital]', '', -3600, '', '', '', true);
	setcookie('catering[ward]', '', -3600, '', '', '', true);
	setcookie('catering[bed]', '', -3600, '', '', '', true);
	$hospital_id = '';
	$ward_id = '';
	$bed_id = '';
	$where = '';
	$bed_name = '';
	$ward_name = '';
	$hospital_name = '';
	$cookie = array();
}
?>
