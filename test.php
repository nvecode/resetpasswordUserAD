<?php

$admin_login = "test555";
$admin_password = "test5551";


$accounts = array(^M
    1 => array (^M
      'login'=>'gro01',^M
      'password'=>'eQ0xUN8B'^M
    ),^M
    2 => array (^M
      'login'=>'gro02',^M
      'password'=>'wlCVCzb8'^M
    ),



$accounts = [
  ["login" => "test555", "password" => "test555"]
];


foreach ($accounts as $account) {
    if($admin_login != $account['login'] || $admin_password != $account['password']) {
      echo 'Неверный логин или пароль!';
    }
  }

?>

