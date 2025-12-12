<?php
ob_start();
include_once "../../model/login.php";
function log_error($log){
    if(!isset($_SESSION))session_start();
    $_SESSION["log_create"]=$log;
    header('location: ../../view/index.html');
    exit;
}
$Email= $_POST["email"];
$PassWord= $_POST["password"];
if(isset($Email,$PassWord)){
    if(strlen($Email)==0) log_error("preencha o campo email");
    else if(strlen($PassWord)==0) log_error("preenchao campo senha");
    else{
        $login= new login("",$Email,$PassWord);
        $resultUsr=$login->login_UsrAdmin("usuario");
        $resultAdmin=$login->login_UsrAdmin("admins");
        if($resultUsr->num_rows===1){
            $user=$resultUsr->fetch_assoc();
            if(password_verify($PassWord,$user['senha'])){
                if(!isset($_SESSION)) session_start();
                $_SESSION["id"]=$user["id_usuario"];
                $_SESSION["email"]=$user["email"];
                header('location: ../../../../view/vendor/site/site.php');
                exit;
            }else log_error("senha incorreta");
        }
        if($resultAdmin->num_rows===1){
            $admin=$resultAdmin->fetch_assoc();
            if(password_verify($PassWord,$admin['senha'])){
                if(!isset($_SESSION)) session_start();
                $_SESSION["id"]= $admin['id_admin'];
                $_SESSION["email"]= $admin['email'];
                header('location: ../../../../view/vendor/admin/admin.php');
                exit;
            }else log_error("senha incorreta");
        }
        $login->close();
    }
}
ob_end_flush();