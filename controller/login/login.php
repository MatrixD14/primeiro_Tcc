<?php
include_once "../../model/login.php";
$Email= $_POST["email"];
$PassWord= $_POST["password"];
$login= new login("",$Email,$PassWord);
