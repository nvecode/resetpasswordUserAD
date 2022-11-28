<?php
session_start();

function redirect() {
  header('Location: index.php');
  exit;
}

$admin_login_reset = $_POST['admin_login_reset'];
$admin_password_reset = $_POST['admin_password_reset'];
$username_reset = $_POST['username_reset'];

if (empty($admin_login_reset)) {
    $_SESSION['error_reset'] = "Логин администратора не может быть пустым!";
    redirect();
}

if (empty($admin_password_reset)) {
  $_SESSION['error_reset'] = "Пароль администратора не может быть пустым!";
  redirect();
}

if (empty($username_reset)) {
  $_SESSION['error_reset'] = "Имя пользователя не может быть пустым!";
  redirect();
}

//Проверка логина администратора
include 'auth.php';

foreach ($accountsAdm as $accountAdm) {
  if ($accountAdm['login'] == $admin_login_reset) {
    if ($accountAdm['password'] == $admin_password_reset) {
      $username = $username_reset;
    }

    else {
      $_SESSION['error_reset'] = "Неверный логин или пароль администратора!";
      redirect();
    }
    break;
  }
}

putenv("NAME=\"$username\"");

if (exec('sh /var/www/html/resetpass_test/reset.sh') == "true") {
  $_SESSION['success_reset'] = "Сессия успешно сброшена!";
  redirect();
}

else {
  $_SESSION['error_reset'] = "Проверьте правильность написания имени пользователя и повторите ещё раз!";
  redirect();
}

?>
