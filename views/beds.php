<?php
displayErroros(); // error output
$dev = "<p>"; // development output
$msg = "<p>";// app normal output
$pr = 'b_'; // database prefix
$id = $pr.'id'; // database id field with prefix
$dbwhere = 'beds'; // database target table
$out = array('','','',); // for output from form generating function TODO remove is occupied, not needed
$formFields = array('id','name','id_ward','id_hospital','phone', 'occupied'); // form fields array
$to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
$formButtons = array('edit','add','delete','fetch','reset'); // form buttons
$sql = ""; // for constructing SQL query
$required = array(0,1,1,1,0,0); // marks required fields of the form
$txtField = array(0,0,0,0,0,0); // 0-input text, 1-textArea, 2-date

$form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField)); // form, not yet translated

// establishing database connection
$db = new myDB();

// Check with what button the form has been submited
if(isset($_POST['edit'])){

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
		$dev = " Fetch, id not provided...";
	}
}

$dev .= $out[0];

$arr_lang = arr_lang(array_merge($formFields, $formButtons, array($pr.'legend','msg')));
$content .= tpl(3, $form, $arr_lang);

if(DEV)$content .= '<div class="devout"><h4>Dev out:</h4>'.$dev.'</div>';

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
