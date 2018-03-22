<?php
displayErroros(); // error output
$msg = "<p>"; $dev = '';// development output
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
		$dev = " Fetch, id not provided...";
	}
}
$dev .= $out[0];

$arr_lang = arr_lang(array_merge($formFields, $formButtons, array($pr.'legend','msg')));
$content .= tpl(3, $form, $arr_lang);

if(DEV)$content .= '<div class="devout"><h4>Dev out:</h4>'.$dev.'</div>';

// retriving data from database
$sql = "select o.o_id, p.p_name, i.i_name, b.b_name, h.h_name, w.w_name from
		patients p join orders o on
		(p.p_id = o.o_id_patient) join items i on
		(i.i_id = o.o_id_item) join beds b on
		(b.b_id = o.o_id_bed) join hospitals h on
		(b.b_id_hospital = h.h_id) join wards w on
		(b.b_id_ward = w.w_id)
	";
$result = $db->myQuery($sql);
$content .= "\n";
$content .= '<ul>';
// formating output with data from database
while($row = $result->fetch_assoc()) {
	$content .= "<li>";
	foreach ($row as $column => $value){
		$content .= $column.":".$value."  ";
	}
	$content .= "</li>";
}
$content .= '</ul>';
// result object method to free result set
$result->free();
?>
