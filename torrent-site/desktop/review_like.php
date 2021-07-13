<?
require_once "connection.php";
$resArr = [];
if (!$_SESSION['auth_flag']) {
  $resArr['error'] = "Пожалуйста войдите или зарегистрируйтесь";
}
if (isset($_GET['liked']) && $_SESSION['auth_flag']) {
  $rev_id = (int) $_GET['liked'];
  $request = "SELECT `id_rev` FROM `review_like` WHERE `game_id`={$_SESSION['this_game']} AND `id_rev`={$rev_id} AND `user_id`=" . $_SESSION['user_id'];
  $res = $mysqli->query($request);
  if ($res->num_rows) {
    $request = "DELETE FROM `review_like` WHERE `id_rev`= {$rev_id} AND `game_id`={$_SESSION['this_game']};UPDATE `rating` SET `likes`= `likes`-1 WHERE `id_rev` = {$rev_id};";
    multiQuery($request);
    $resArr["act"] = 0;
  } else {
    $request = "INSERT INTO `review_like`(`id_rev`, `user_id`, `game_id`, `date`) VALUES ({$rev_id},{$_SESSION['user_id']},{$_SESSION['this_game']}, current_timestamp());UPDATE `rating` SET `likes`= `likes`+1 WHERE `id_rev` = {$rev_id};";
    multiQuery($request);
    $resArr["act"] = 1;
  }
}
echo json_encode($resArr);