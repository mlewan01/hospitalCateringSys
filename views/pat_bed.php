<?php
displayErroros(); // error output
$msg = ''; // app normal output
$pr = 'pb_'; // database prefix
$id = $pr.'id'; // database id field with prefix
$dbwhere = 'pat_bed'; // database target table
$out = array('','','',); // for output from form generating function
$formFields = array('id','id_bed','id_patient','date_from','date_to'); // form fields array
$to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
$formButtons = array('edit','add','delete','fetch','reset'); // form buttons
$sql = ""; // for constructing SQL query
$required = array(0,1,1,1,0); // marks required fields of the form
$txtField = array(0,0,0,2,2); // 0-input text, 1-textArea, 2-date

$form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField));

// connection with database
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
		$result = $db->myQuery($out[2]);
		if($result !== false){
			$result = $result->fetch_assoc();
			if($result == null){
				$msg = " Provided \"ID\" does not exist... Pls try again !".$b;
				$result = array();
			}
		}else{
			$result = array();
			$msg = " Provided \"ID\" probaly was not a number... Pls try again !".$b;
		}
		$form = tpl(2, $form, $result, $txtField);
	}else {
		$msg = " Fetch, id not provided... Pls try again !";
	}
}

$dev .= $out[0];

$content .= $msg;
$arr_lang = arr_lang(array_merge($formFields, $formButtons, array($pr.'legend','msg')));
$content .= tpl(3, $form, $arr_lang);

// pagination
$pi = paginationInit("pat_bed", "pb_id");
$pagin = pagination($pi["statement"],$pi["limit"],$pi["page"], $pi["link"]);
// output
$content .= "<div id='pagingg' >$pagin</div>";

if(isset($_GET['pn']) || $pagin == ''){ // to prefent initial display

$result = $db->myQuery($pi["sql"]);

// formating output with data from database
$content .= "<ul>";
while($row = $result->fetch_assoc()) {
	$content .= "<li>";
	foreach ($row as $column => $value){
		$content .= "<b>".ltrim($column, 'pb_')."</b>:".$value." ";
	}
	$content .= "</p>";
}
$content .= "</ul>";
$content .= "<div id='pagingg' >$pagin</div>";
// result object method to free result set
$result->free();
}
$dev .= $pi['dev'];
?>
