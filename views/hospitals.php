<?php
displayErroros(); // error output
$msg = "<p>";
$pr = 'h_';
$id = $pr.'id';
$dbwhere = 'hospitals';
$out = array('','','',); // for output from form generating function
$formFields = array('id','name','address','email','phone','description');
$to_clean = array_merge(array(''), addPrefix($formFields, $pr));
$formButtons = array('edit','add','delete','fetch','reset');
$sql = "";
$required = array(0,1,0,0,0,0);
$txtField = array(0,0,1,0,0,1);

$form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField)); // form, not yet translated

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
		if($result != false){
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
$pi = paginationInit("hospitals", "h_id");
$pagin = pagination($pi["statement"],$pi["limit"],$pi["page"], $pi["link"]);
// output
$content .= "<div id='pagingg' >$pagin</div>";

if(isset($_GET['pn']) || $pagin == ''){ // to prefent initial display

$result = $db->myQuery($pi["sql"]);
$content .= '<ul>';
while($row = $result->fetch_assoc()){
	$content .= "<li> $row[h_id] - $row[h_name] </li>";
}
$content .= '</ul>';

$content .= "<div id='pagingg' >$pagin</div>";
// result object method to free result set
$result->free();
}
$dev .= $pi['dev'];
?>
