<?php
displayErroros(); // error output
$b = '<br/>';
$content = '';
$sql = '';

$db = new myDB();

$pi = paginationInit("logs", "l_id", null, "desc");

$pagin = pagination($pi["statement"],$pi["limit"],$pi["page"], $pi["link"]);

$content .= "<div id='pagingg' >$pagin</div>";

$result = $db->myQuery($pi["sql"]);
$content .= '<ul>';
while($row = $result->fetch_assoc()){
	$content .= "<li> $row[l_id] - $row[l_msg] </li>";
}
$content .= '</ul>';
$result->free();
$content .= "<div id='pagingg' >$pagin</div>";
$dev .= $pi['dev'];
?>
