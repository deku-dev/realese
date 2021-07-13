<?
require_once "desktop/connection.php";
if (!empty($_GET["search"])) {
  $search = escStr($_GET["search"]);
  $request = "SELECT `game_id`, `name`, `image` FROM `game` WHERE `name` LIKE '%{$search}%' LIMIT 0,6";
  $game = $mysqli->query($request);
  echo json_encode($game->fetch_all(), JSON_UNESCAPED_UNICODE);
} else {
  echo "{}";
}