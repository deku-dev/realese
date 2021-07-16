<?
require_once "desktop/connection.php";
if (isset($_GET['nick'])) {
  $nick = escStr($_GET['nick']);
  $request = "SELECT `user_id` FROM `users` WHERE `nickname`='{$nick}'";
  $res = $mysqli->query($request);
  if ($res->num_rows) {
    $arrRes["nick"] = "1";
  } else {
    $arrRes["nick"] = "0";
  }
  echo json_encode($arrRes);
}