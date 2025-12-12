<?php
include_once "../../../model/login.php";
$login=new login("","","");
$login->protect();
$list=$login->list_All("usuario");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./admin.css">
    <title>site</title>
</head>
<body>
    <div class="center">
        <nav>
            <a href="../../../controller/login/logout.php">logout</a>
        </nav>
            <h1>inicio do admin</h1>
    <table border="1">
        <tr>
            <th>nome</th>
            <th>email</th>
            <th>senha</th>
            <th>editer</th>
            <th>delete</th>
        </tr>
        <?php while($lina=$list->fetch_assoc()){?>
            <tr>
                <td class="info-dado"><?=$lina['nome']?></td>
                <td class="info-dado"><?=$lina['email']?></td>
                <td class="info-dado"><?=$lina['senha']?></td>
                <td><a href="#" class="btn-edite">editer</a></td>
                <td><a href="../../../controller/login/delete.php?id=<?=$lina['id_usuario']?>?nome=<?=$lina['nome']?>" class="btn-delete">delete</a></td>
            </tr>
        <?php }?>
    </table>
    </div>
</body>
</html>