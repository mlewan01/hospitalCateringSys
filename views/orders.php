<?php
displayErroros(); // error output
$msg = "<p>";
$msge = "<p>";// app normal output
$pr = 'o_';
$id = $pr.'id';
$dbwhere = 'orders';
$out = array('','','',);
$formFields = array('id','id_patient','id_item','id_bed','date');
$to_clean = array_merge(array(''), addPrefix($formFields, $pr));
$formButtons = array('edit','add','delete','fetch','reset');
$sql = "";
$required = array(0,1,1,1,0);
$txtField = array(0,0,0,0,2);// 0-input text, 1-textArea, 2-date,

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

$sql = "select o_id, p_name, i_name, b_name, h_name, w_name from
		patients join orders on
		(p_id = o_id_patient) join items on
		(i_id = o_id_item) join beds on
		(b_id = o_id_bed) join hospitals on
		(b_id_hospital = h_id) join wards on
		(b_id_ward = w_id)
	";

$pi = paginationInit("orders", "o_id", null, null, $sql);
$pagin = pagination($pi["statement"],$pi["limit"],$pi["page"], $pi["link"]);
// output
$content .= "<div id='pagingg' >$pagin</div>";

$dev .= "<b>pagHelp:</b> ".$pi["sql"]."<br>"."<b>orders sql:</b> $sql <br>";

if(isset($_GET['pn']) || $pagin == ''){ // to prefent initial display
$result = $db->myQuery($pi["sql"]);
$content .= '<ul>';
// formating output with data from database
while($row = $result->fetch_assoc()) {
	$content .= "<li>";
	foreach ($row as $column => $value){
		$content .= "<b>".ltrim($column, 'o_')."</b>:".$value."  ";
	}
	$content .= "</li>";
}
$content .= '</ul>';

$content .= "<div id='pagingg' >$pagin</div>";
// result object method to free result set
$result->free();
}
$dev .= $pi['dev'];
?>
