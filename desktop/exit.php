<?
unset($_SESSION['user_name']);
unset($_SESSION['date']);
unset($_SESSION['about']);
unset($_SESSION['picture']);
unset($_SESSION['user_id']);
unset($_SESSION['hash']);
$_SESSION['auth_flag'] = false;
setcookie("_uida", '', time() - 3600, '/');
setcookie("_uide", "", time() - 3600, '/');