<?php
include_once "../../model/login.php";
function log_error($log){
    if(!isset($_SESSION))session_start();
    $_SESSION["log_create"]=$log;
    header('location: ../../view/vendor/create_login/create_login.php');
    exit;
}
$nome = $_POST['nome'];
$Email= $_POST['email'];
$PassWord= $_POST['password'];
if(isset($nome)|| isset($Email)||isset($PassWord)){
    if(strlen($nome)==0) log_error("preencha o campo nome");
    else if(strlen($Email)==0) log_error("preencha o campo email");
    else if(strlen($PassWord)==0)log_error("preencha o campo senha");
    else{
        $login= new login($nome,$Email,$PassWord);
        if($login->login_UsrAdmin("usuario")->nuw_rows > 0){
            log_error("conta já existente");
        }else if($login->create_login()){
            header('location: ../../view/vendor/create_login/create_login.php');
            exit;
        }else log_error("erro ao create conta");
        $login->close();      
    }
}