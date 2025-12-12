<?php
include_once "../../../model/login.php";
$login =new login("","","");
$connect = $login->connects();
$senha="";
$email="";
$nome="";
$password=password_hash($senha,PASSWORD_DEFAULT);
$tmg = $connect->prepare("insert into usuario(nome,email,senha)values(?,?,?)");
$tmg->bind_param("sss",$nome,$email,$password);
if(!$tmg->execute()) die("commad nao executado");
$tmg->close();
header('location: ../../index.html');
        