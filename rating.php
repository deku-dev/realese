<?
require_once "mobile/connection.php";
if (isset($_POST['rating']) && $_SESSION['user_id']) {
  $ratValue = (int) $_POST['rating'];
  $request = "SELECT `game_id` FROM `rating` WHERE `game_id`= {$_SESSION['this_game']} AND `user_id`= {$_SESSION['user_id']}";
  $res = $mysqli->query($request);
  if ($res->num_rows) {
    $request = "UPDATE `rating` SET `value`= {$ratValue} WHERE `game_id`= {$_SESSION['this_game']} AND `user_id`= {$_SESSION['user_id']}";
    if (!$mysqli->query($request)) {
      http_response_code(500);
    }
  } else {
    $request = "INSERT INTO `rating`(`game_id`, `user_id`, `value`, `date`) VALUES ({$_SESSION['this_game']},{$_SESSION['user_id']},{$ratValue},current_timestamp())";
    if (!$mysqli->query($request)) {
      http_response_code(500);
    }
  }
}