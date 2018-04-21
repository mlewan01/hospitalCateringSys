<?php
displayErroros(); // error output
$dev = '';
$b = '<br/>';
$content = '';
$sql = '';

$db = new myDB();

$page = (int) (!isset($_GET["pn"]) ? 1 : $_GET["pn"]);  $dev = 'page: '.$page;

$limit = 10; //if you want to dispaly 10 records per page then you have to change here
$startpoint = ($page * $limit) - $limit;  $dev = 'startpoing: '.$startpoint;

$order = "order by l_id desc";
$statement = "logs"; //you have to pass your query over here

$sql = "select * from {$statement} {$order} LIMIT {$startpoint} , {$limit}";  $dev = "$sql <br>";
$statement = "{$statement} {$order}";

$sq = $_SERVER["QUERY_STRING"];  $dev = "sq: $sq<br>";
$sq = strstr($sq, 'pn', true) === false ? $sq : rtrim(strstr($sq, 'pn', true), "?&") ;  $dev = "sq trimmed: $sq<br>";
$sq = $sq == '' ? '?': '?'.$sq.'&';  $dev = 'url: '.$_SERVER["PHP_SELF"].$sq."<br>";
$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
$link = "http".$s."://".$_SERVER['SERVER_NAME'].$_SERVER["PHP_SELF"].$sq;  $dev = "link : $link <br>";
$pagin = pagination($statement,$limit,$page, $link);

$content .= "<div id='pagingg' >$pagin</div>";

$result = $db->myQuery($sql);
$content .= '<ul>';
while($row = $result->fetch_assoc()){
	$content .= "<li> $row[l_id] - $row[l_msg] </li>";
}
$content .= '</ul>';

$content .= "<div id='pagingg' >$pagin</div>";

if(DEV)$content .= '<div class="devout"><h4>Dev out:</h4>'.$dev.'</div>';
$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
?>
