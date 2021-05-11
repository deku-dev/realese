<?
require_once "desktop/connection.php";
if ($_SESSION['auth_flag']) {
  $request = "SELECT `id_rev`,`value`,`text_review` FROM `rating` WHERE `game_id`= {$_SESSION["this_game"]} AND `user_id`=" . $_SESSION['user_id'];
  $res = $mysqli->query($request);
  if (!$res->num_rows) {
    $arrNo['no'] = "no";
    echo json_encode($arrNo);
  } else {
    echo json_encode($res->fetch_all(), JSON_UNESCAPED_UNICODE);
  }

}
