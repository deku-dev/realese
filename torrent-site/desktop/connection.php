<?
ini_set("session.use_strict_mode", 1);
session_start();
// $_SESSION['auth_flag'] = false;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$host = 'localhost'; // адрес сервера
$database = 'games'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'root'; // пароль
// mysqli_report(MYSQLI_REPORT_ALL);
$gamePerPage = 20;
$nameSite = "TorrentGame.net";
$nameSite = "ACTorrent.net";
$_SESSION['ip'] = getIp();
setlocale(LC_ALL, 'ru_RU.UTF-8');
$mysqli = new mysqli($host, $user, $password, $database);
if (!$mysqli) {
  die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");
function getIp()
{
  $keys = [
    'HTTP_CLIENT_IP',
    'HTTP_X_FORWARDED_FOR',
    'REMOTE_ADDR',
  ];
  foreach ($keys as $key) {
    if (!empty($_SERVER[$key])) {
      $ip = trim(end(explode(',', $_SERVER[$key])));
      if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
      }
    }
  }
}
function multiQuery($reqViews)
{
  global $mysqli;
  if ($mysqli->multi_query($reqViews)) {
    do {
      if ($result = $mysqli->store_result()) {
        $result->free();
      }
    } while ($mysqli->more_results() && $mysqli->next_result());
  }
}
function escStr($str)
{
  global $mysqli;
  return $mysqli->real_escape_string(htmlspecialchars($str));
}
function createHash($hashCre)
{
  return hash("sha256", $hashCre);
}
function userCheck($hash)
{
  global $mysqli;
  $req = "SELECT `user_id` FROM `user_hash` WHERE `hash` = '{$hash}'";
  $resHash = $mysqli->query($req);
  return $resHash->fetch_assoc();
}
function dateRus($date)
{
  $rus_months = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
  $newDatetime = new Datetime($date);
  $month = $newDatetime->format('n');
  $album_data = $newDatetime->format('d ' . $rus_months[$month - 1] . ' ');
  $album_data .= $newDatetime->format('Y');
  return $album_data;
}
function userSet($hash, $ip)
{
  global $mysqli;
  $req = "INSERT INTO `user_hash` (`user_id`, `hash`, `date`, `ip`, `user_ha`, `register`) VALUES (NULL, '{$hash}', current_timestamp(), '{$ip}', '{$_SERVER['HTTP_USER_AGENT']}', 0);";
  $mysqli->query($req);
  return $mysqli->insert_id;
}
function getPage($sqlreq)
{
  $num_page = 0;
  global $gamePerPage;
  if (isset($_GET['page'])) {
    $num_page = (int) $_GET['page'] - 1 <= -1 ? 0 : (int) $_GET['page'] - 1;
  }
  if (isset($_GET['s'])) {
    $sqlreq = getSort($sqlreq);
  }
  return $sqlreq .= " LIMIT " . $num_page * $gamePerPage . "," . $gamePerPage . ";";
}
$hash = createHash($_SERVER['HTTP_USER_AGENT'] . getIp());
if ($hash_id = userCheck($hash)) {
  // echo $hash;
  $_SESSION['hash_id'] = $hash_id['hash_id'];
  setcookie("hash", $hash, time() + 60 * 60 * 24 * 3, '/');
} else {
  $_SESSION['hash_id'] = userSet($hash, $_SESSION['ip']);
  setcookie("hash", $hash, time() + 60 * 60 * 24 * 3, '/');
}
require_once "authenfication.php";