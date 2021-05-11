<?
require_once 'connection.php';
$resArr = [];
if (!$_SESSION['auth_flag']) {
  $resArr['error'] = "Пожалуйста войдите или зарегистрируйтесь";
}
if (isset($_GET['art']) && $_SESSION['auth_flag']) {
  $art = (int) $_GET['art'];

  $request = "SELECT `game_id` FROM `favorites` WHERE `game_id`= " . $art . " AND `user_id`= " . $_SESSION['user_id'];
  $res = $mysqli->query($request);
  if ($res->num_rows) {
    $request = "DELETE FROM `favorites` WHERE `game_id`= " . $art . " AND `user_id`= " . $_SESSION['user_id'];
    if (!$mysqli->query($request)) {
      http_response_code(500);
    } else {
      $resArr["act"] = 0;
    }
  } else {
    $request = "INSERT INTO `favorites`(`game_id`, `user_id`, `date`) VALUES ({$art}, {$_SESSION['user_id']}, current_timestamp())";
    if (!$mysqli->query($request)) {
      http_response_code(500);
    } else {
      $resArr["act"] = 1;
    }
  }
}
echo json_encode($resArr);
