<?php
ob_start();
include_once "../../model/login.php";
if(session_status()===PHP_SESSION_NONE) session_start();
function log_error($log){
    $_SESSION["log_create"]=$log;
    header('location: ../../view/index.php');
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
        }elseif($resultAdmin->num_rows===1){
            $admin=$resultAdmin->fetch_assoc();
            if(password_verify($PassWord,$admin['senha'])){
                if(!isset($_SESSION)) session_start();
                $_SESSION["id"]= $admin['id_admin'];
                $_SESSION["email"]= $admin['email'];
                header('location: ../../../../view/vendor/admin/admin.php');
                exit;
            }else log_error("senha incorreta");
        }else log_error("não existe nenhum registro seu crie um");
        $login->close();
    }
}
ob_end_flush();