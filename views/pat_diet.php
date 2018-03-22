<?php
displayErroros(); // error output
$dir = dirname(dirname(__FILE__));
$dev = ''; // development output
$msg = '';// app normal output
$pr = 'pd_'; // database prefix
$id = $pr.'id'; // database id field with prefix
$dbwhere = 'pat_diet'; // database target table
$out = array('','','',); // for output from form generating function
$formFields = array('id','id_patient','diet','date'); // form fields array
$to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
$formButtons = array('edit','add','delete','fetch','reset'); // form buttons
$sql = "";
$required = array(0,1,1,1);
$txtField = array(0,0,0,2);// 0-input text, 1-textArea, 2-date,

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
$sql = "select * from $dbwhere";
$result = $db->myQuery($sql);
$content .= "\n";

// formating output with data from database
$content .= "<ul>";
while($row = $result->fetch_assoc()) {
	$content .= "<li>";
	foreach ($row as $column => $value){
		$content .= $column.":".$value."  ";
	}
	$content .= "</p>";
}
$content .= "</ul>";
// result object method to free result set
$result->free();
?>
