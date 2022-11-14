<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Главная</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
  </head>
  <body>
    <div class="container">
    <br><h1 align="center"><b>Форма для сброса пароля</b></h1>
      <div class="text-success"></div><br>
      <form action="resetpassword_function.php" method="post">
        <div class="text-danger"><?=$_SESSION['errors']?></div><br>
        <input type="text" name="admin_login" placeholder="Имя учётной записи администратора" class="form-control"><br>
        <input type="password" name="admin_password" placeholder="Пароль учётной записи администратора" class="form-control"><br>
        <input type="text" name="account_login" placeholder="Имя учётной записи пользователя" class="form-control"><br>
        <input type="password" name="account_password" placeholder="Новый пароль учётной записи пользователя" class="form-control"></input><br>
        <button type="submit" name="button" class="btn btn-success">Сбросить пароль и разблокировать учётную запись</button><br>
        <div class="text-success"><?=$_SESSION['success']?></div>
      </form>
  </body>
</html>
<?php
session_destroy();
?>
