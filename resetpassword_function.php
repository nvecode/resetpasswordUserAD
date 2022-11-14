<?php
session_start();

function redirect() {
  header('Location: index.php');
  exit;
}

$admin_login = $_POST['admin_login'];
$admin_password = $_POST['admin_password'];
$account_login = $_POST['account_login'];
$account_password = $_POST['account_password'];

//Проверка заполнения полей
if (empty($admin_login)) {
  $_SESSION['errors'] = "Логин администратора не может быть пустым!";
  redirect();
}

if (empty($admin_password)) {
  $_SESSION['errors'] = "Пароль администратора не может быть пустым!";
  redirect();
}

if (empty($account_login)) {
  $_SESSION['errors'] = "Логин пользователя не может быть пустым!";
  redirect();
}

if (empty($account_password)) {
  $_SESSION['errors'] = "Пароль пользователя не должен быть пустым!";
  redirect();
}

//Проверка логина администратора
include 'auth.php';

foreach ($accountsAdm as $accountAdm) {
  if ($accountAdm['login'] == $admin_login) {
    if ($accountAdm['password'] == $admin_password) {
      $admingro = $admin_login;
    }

    else {
      $_SESSION['errors'] = "Неверный логин или пароль администратора!";
      redirect();
    }
    break;
  }
}

$serveradd = "ldaps://VMSrvDC2.skg.stavkraygaz.ru:636"; // domain controller
$user = $account_login;
$pass = $account_password;

function resetpassword($serveradd,$user,$pass,$admingro) {
$ldaprdn  = "CN=admchange,OU=Учётные записи администраторов,DC=skg,DC=stavkraygaz,DC=ru";
$ldappass = "12qwaszxC";  // associated password
$ldapconn = ldap_connect($serveradd);
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

  if ($ldapconn) {

        // binding to ldap server
        $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

        // verify binding
        if ($ldapbind) {

            $samaccountname = $user;

            $filter="(samaccountname=$samaccountname)";
            $dn="DC=skg,DC=stavkraygaz,DC=ru";

            $res = ldap_search($ldapconn, $dn, $filter);
            $first = ldap_first_entry($ldapconn, $res);
            $data = ldap_get_dn($ldapconn, $first);
            $data2 = ldap_get_attributes($ldapconn, $first);
            $resultData2 = $data2["sAMAccountName"][0];

            if (stripos($resultData2, $admingro) === false) {
              $_SESSION['errors'] = "У Вас нет прав для сброса пароля для данной учётной записи пользователя!";
              redirect();
            }

            if (stripos($resultData2, $user) === false) {
              $_SESSION['errors'] = "Пользователь не найден!";
              redirect();
            }

            if (mb_strlen($pass) <= 10 && preg_match('/[A-Z0-9]/', $pass)) {
              $_SESSION['errors'] = "Пароль должен быть не менее 10 символов и иметь хотя бы одну заглавную букву и цифру!";
              redirect();
            }

            else {
              $password = $pass;
            }

            $dn = $data;
            $newPassword = "\"" . $password . "\"";
            $newPass = mb_convert_encoding($newPassword, "UTF-16LE");
            $newEntry = array('unicodePwd' => $newPass);

            $newEntry["lockouttime"][0]=0;

            if(ldap_mod_replace($ldapconn, $dn, $newEntry)) { 
              $_SESSION['success'] = "Пароль успешно сброшен. Ваш пароль: " . $password;
              redirect();
            }

            else {
              $_SESSION['errors'] = "Неизвестная ошибка. Повторите ещё раз!";
              redirect();
            }

        }

  else {
    $_SESSION['errors'] = "Сервис сброса паролей временно недоступен!";
    redirect();
  }              
  
  }
}

resetpassword($serveradd,$user,$pass,$admingro)
          
?>