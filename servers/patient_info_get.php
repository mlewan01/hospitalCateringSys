<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
include('../classes/myDB.php');
displayErroros();
autoloader();
  if(isset($_GET['p_id'])){
    $p_id = $_GET['p_id'];
    $sql = "select p_info from patients where p_id = $p_id";
    $answ = (new myDB())->myQuery($sql);
    $answ = $answ->fetch_assoc();
    echo $answ['p_info'];
  }else echo false;
?>
