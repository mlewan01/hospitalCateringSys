<?php
displayErroros(); // error output
$msg = "<p>";// app normal output
$pr = 'mi_'; // database prefix
$id = $pr.'id';// database id field with prefix
$dbwhere = 'menu_items'; // database target table
$out = array('','','',); // for output from form generating function
$formFields = array('id','id_product','id_menu'); // form fields array
$to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
$formButtons = array('edit','add','delete','fetch','reset'); // form buttons
$sql = "";
$required = array(0,1,1);
$txtField = array(0,0,0);

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
		$dev .= " Fetch, id not provided...";
	}
}
$dev .= $out[0];

$arr_lang = arr_lang(array_merge($formFields, $formButtons, array($pr.'legend','msg')));
$content .= tpl(3, $form, $arr_lang);

// pagination
$pi = paginationInit("menu_items", "mi_id");
$pagin = pagination($pi["statement"],$pi["limit"],$pi["page"], $pi["link"]);
// output
$content .= "<div id='pagingg' >$pagin</div>";

$result = $db->myQuery($pi["sql"]);
$content .= '<ul>';
while($row = $result->fetch_assoc()){
	$content .= "<li><b>mid</b> $row[mi_id] <b>mi_id_product</b> $row[mi_id_product] <b>mi_id_menu</b> $row[mi_id_menu]</li>";
}
$content .= '</ul>';

$content .= "<div id='pagingg' >$pagin</div>";
$dev .= $pi['dev'];
// result object method to free result set
$result->free();
?>
