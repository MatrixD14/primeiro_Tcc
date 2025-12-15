<?php
if(session_status()===PHP_SESSION_NONE) session_start();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../css/global.css" />
    <link rel="stylesheet" href="../../css/component/button.css" />
    <title>creating login</title>
  </head>
  <body>
    <div class="center box-border">
      <h1 class="h1-center">cria um login</h1>
      <div class="center">
      <p style="color: red;">
        <?php
        if(isset($_SESSION["log_create"])){ 
        echo $_SESSION["log_create"];
        session_destroy();
      }
        ?>
        </p>
        <form action="../../../controller/login/create_login.php" method="post">
          <label for="nome">nome</label><br />
          <input type="text" name="nome" /><br />
          <label for="email">email</label><br />
          <input type="email" name="email" /><br />
          <label for="password">senha</label><br />
          <input type="password" name="password" /><br /><br />
          <div class="box-center">
            <input type="submit" value="enter" class="bt-enter" />
            <p></p>
            <a href="../../index.php">login</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
