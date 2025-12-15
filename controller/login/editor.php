<?php
if(session_status()===PHP_SESSION_NONE) session_start();
include_once "../../model/login.php";
$nome=$_POST['nome'];
$email=$_POST['email'];
$senha=$_POST['senha'];
$id=$_POST['id'];
$login=new login($nome,$email,$senha);
$login->edite_usr($id);
$_SESSION['sms']="usuario editado ".$nome;
header('location: ../../view/vendor/admin/admin.php');