<?php
include_once "../../../model/login.php";
$login=new login("","","");
$login->protect();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>site</title>
</head>
<body>
    <a href="../../../controller/login/logout.php">logout</a>
    <h1>inicio do site</h1>
</body>
</html>