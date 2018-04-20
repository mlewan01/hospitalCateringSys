<?php
displayErroros();
$dir = dirname(dirname(__FILE__));
$msg = "<p>"; // development output
$msge = "<p>"; // app normal output
$pr = 'u_'; // database prefix
$id = $pr.'id'; // database id field with prefix
$dbwhere = 'users'; // database target table
$out = array('','','',); // for output from form generating function
$formFields = array('id','name','username','password','privileges','department','role','phone','email','regdate','active'); // form fields array
$to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
$formButtons = array('edit','add','delete','fetch','reset', 'changePassword'); // form buttons
$sql = ""; // for constructing SQL query
$required = array(0,1,1,1,0,0,0,0,0,0,0); // marks required fields of the form
$txtField = array(0,0,0,0,0,0,0,0,0,2,0); // 0-input text, 1-textArea, 2-date,

$form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField)); // form, not yet translated

// connection with database
$db = new myDB();

// Check with what button the form has been submited
if(isset($_POST['edit'])){
	//print_r($_POST);
	$out = formEdit($to_clean, $required, $dbwhere, $id, $txtField);
	if($out[2] != '')$msg = $db->myQuery($out[2]);

}elseif(isset($_POST['add'])){

	$out = formAdd($to_clean, $required, $dbwhere, $id, $txtField);
	if($out[2] != '')$msg = $db->myQuery($out[2]);

}elseif(isset($_POST['delete'])){

	$out = formDelete($to_clean, $required, $dbwhere, $id);
	if($out[2] != '')$msg = $db->myQuery($out[2]);

}elseif(isset($_POST['fetch'])){
	if($_POST[$to_clean[1]]!=''){
		$out = formFetch($to_clean, $required, $dbwhere, $id);
		$result = $db->myQuery($out[2])->fetch_assoc();
		$form = tpl(2, $form, $result, $txtField);
	}else {
		$msg = " Fetch, id not provided...";
	}
}elseif(isset($_POST['changePassword'])){
	unset($_POST['changePassword']);
	$_POST['edit'] = "Edit";
	$p = $_POST;
	$regTime = (new myTime())->getMyTime(3,$p['u_regdate']);

	echo "changing pass !!!11, regtime: ".$regTime;
	print_r($p);
	$out = formEdit($to_clean, $required, $dbwhere, $id, $txtField);
	if($out[2] != '')$msg = $db->myQuery($out[2]);

	changePassword($p['u_id'],$p['u_username'],$p['u_password'],$regTime,'u_password');
}

$msg .= $out[0];
$msge .= $out[1];

$arr_lang = arr_lang(array_merge($formFields, $formButtons, array($pr.'legend','msg')));
$content .= tpl(3, $form, $arr_lang);

$content .= $msg.'<p>';

// retriving data from database
$sql = "select $id, ".$pr."name from $dbwhere";
$result = $db->myQuery($sql);
$content .= "\n";

// formating output with data from database
$content .= "<ul>";
while($row = $result->fetch_assoc()) {
	$content .= "<li>$row[$id]. ".$row[$pr."name"]." </li>";
}
$content .= "</ul>";
// result object method to free result set
$result->free();
?>
