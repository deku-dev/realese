<?
require_once "desktop/connection.php";
switch ($_GET['do']) {
  case 'profile':
    $now = time();
    $your_date = strtotime($_SESSION['date']);
    $datediff = floor(($now - $your_date) / (60 * 60 * 24));
    $userStat = [
      6 => $_SESSION['about'],
      2 => $_SESSION['favorites'],
      3 => $_SESSION['views'],
      4 => $_SESSION['rating'],
      5 => $_SESSION['download'],
      1 => $datediff . " дней",
      0 => $_SESSION['user_name'],
      7 => $_SESSION['picture'],
    ];
    echo json_encode($userStat, JSON_UNESCAPED_UNICODE);
    break;
  case 'feedback':
    echo "{}";
    break;
  case 'prof':

    break;
  default:

    break;
}