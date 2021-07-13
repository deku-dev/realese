<?
require_once "mobile/connection.php";
if (isset($_GET['rev_id'])) {
  $page = 10 * (int) $_GET['rev_id'];
  $request = "SELECT `rating`.*, `users`.`nickname`, `users`.`picture` FROM `rating`, `users` WHERE `users`.`user_id` = `rating`.`user_id` AND `rating`.`game_id` = {$_SESSION['this_game']} ORDER BY `date` DESC LIMIT {$page},10";
  $res = $mysqli->query($request);
  $jsonRes = [];
  $order = 2 + $page;
  while ($resRev = $res->fetch_assoc()) {
    $date = strftime("%x", strtotime($resRev['date']));
    $imag = $resRev['picture'];
    $nick = $resRev['nickname'];
    $valu = $resRev['value'];
    $text = $resRev['text_review'];
    $like = $resRev['likes'];
    $revi = $resRev['id_rev'];
    $jsonRes[$order] = [
      "rev_id" => $revi,
      "value" => $valu,
      "date" => $date,
      "text_review" => $text,
      "likes" => $like,
      "nickname" => $nick,
      "picture" => $imag,
    ];
    $order++;
  }
  echo json_encode($jsonRes);
}