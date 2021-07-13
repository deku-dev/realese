<?
require_once "connection.php";
if (isset($_GET['favorites'])) {
  $request = "SELECT `game`.`image`,`game`.`name`,`game`.`game_id` FROM `favorites`, `game` WHERE `game`.`game_id`=`favorites`.`game_id` AND `favorites`.`user_id`={$_SESSION['user_id']} ORDER BY `favorites`.`date` DESC LIMIT " . (int) $_GET['favorites'] * 30 . ",30";
  $res = $mysqli->query($request);
  if (!$res->num_rows) {
    die("{}");
  }
  echo json_encode($res->fetch_all(), JSON_UNESCAPED_UNICODE);
} elseif (isset($_GET['views'])) {
  $request = "SELECT `game`.`image`,`game`.`name`,`game`.`game_id` FROM `user_views`, `game` WHERE `game`.`game_id`=`user_views`.`game_id` AND `user_views`.`user_id`={$_SESSION['user_id']} ORDER BY `user_views`.`date` DESC LIMIT " . (int) $_GET['views'] * 30 . ",30";
  $res = $mysqli->query($request);
  if (!$res->num_rows) {
    die("{}");
  }
  echo json_encode($res->fetch_all(), JSON_UNESCAPED_UNICODE);
} elseif (isset($_GET['download'])) {
  $request = "SELECT `game`.`image`,`game`.`name`,`game`.`game_id` FROM `load_list`, `game` WHERE `game`.`game_id`=`load_list`.`game_id` AND `load_list`.`user_id`={$_SESSION['user_id']} ORDER BY `load_list`.`date` DESC LIMIT " . (int) $_GET['download'] * 30 . ",30";
  $res = $mysqli->query($request);
  if (!$res->num_rows) {
    die("{}");
  }
  echo json_encode($res->fetch_all(), JSON_UNESCAPED_UNICODE);
}