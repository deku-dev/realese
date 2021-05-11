<?
require_once "connection.php";
if ($_SESSION['auth_flag']) {
  $request = "DELETE FROM `rating` WHERE `game_id`= {$_SESSION['this_game']} AND `user_id`={$_SESSION['user_id']}";
  if (!$res = $mysqli->query($request)) {
    http_response_code(501);
  }
}