<?
require_once "desktop/connection.php";
if (isset($_GET['page']) && $_GET['page'] != "user") {
  $user = (int) $_SESSION['user_id'];
  $request = "SELECT `rating`.`value`,`rating`.`text_review`,`rating`.`likes`,DATE_FORMAT(`rating`.`date`, '%d-%m-%Y'),`users`.`picture`,`users`.`nickname`, `rating`.`id_rev`,IF(`review_like`.`user_id`={$user}, 1,0) FROM `users`,`rating` LEFT JOIN `review_like` ON `review_like`.`id_rev`=`rating`.`id_rev` WHERE `users`.`user_id` = `rating`.`user_id` AND `rating`.`game_id` = {$_SESSION['this_game']} ORDER BY `rating`.`date` DESC LIMIT " . (int) $_GET['page'] * 20 . ",20";
  $res = $mysqli->query($request);
  if (!$res->num_rows) {
    die("{}");
  }
  $resParse = $res->fetch_all();
  echo json_encode($resParse, JSON_UNESCAPED_UNICODE);
}
if ($_GET['page'] == "user") {
  $request = "SELECT `value`,`text_review`,`likes`,DATE_FORMAT(`date`, '%d-%m-%Y') AS 'date', `id_rev` FROM `rating` WHERE `game_id` = {$_SESSION['this_game']} AND `user_id`={$_SESSION['user_id']}";
  $res = $mysqli->query($request);
  $resAssoc = $res->fetch_assoc();
  $arrRev[0] = [
    "0" => $resAssoc['value'],
    "1" => $resAssoc['text_review'],
    "2" => $resAssoc['likes'],
    "3" => $resAssoc['date'],
    "4" => $_SESSION['picture'],
    "5" => $_SESSION['user_name'],
    "6" => $resAssoc['id_rev'],
  ];
  echo json_encode($arrRev, JSON_UNESCAPED_UNICODE);
}