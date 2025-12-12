<?php
include_once "../../model/login.php";
$login=new login("","","");
$usr=$_GET['nome'];
$id=$_GET["id"];
$login->delete_usr($id);
$_SESSION['sms']="usuario deletado ".$usr;
header('location: ../../view/vendor/admin/admin.php');