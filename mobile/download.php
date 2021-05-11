<?
require_once "connection.php";
if (!empty($_GET['down'])) {
  $url = escStr($_GET['down']);
  $request = "SELECT `id_down` FROM `load_list` WHERE `user_id`={$_SESSION['user_id']} AND `game_id`={$_SESSION['this_game']} AND `hash_id`={$_SESSION['hash_id']} AND `file_url`='{$url}'";
  $res = $mysqli->query($request);
  if ($res->num_rows == 0) {
    $user = empty($_SESSION['user_id']) ? "NULL" : $_SESSION['user_id'];
    $request = "INSERT INTO `load_list`(`id_down`, `user_id`, `game_id`, `hash`,`ip_down`,`agent_down`,`file_url`, `date`) VALUES (NULL,{$user},{$_SESSION['this_game']},'{$_COOKIE['hash']}','{$_SESSION['ip']}','{$_SERVER['HTTP_USER_AGENT']}','{$url}',current_timestamp());UPDATE `game` SET `downloads`=`downloads`+1 WHERE `game_id` = {$_SESSION['this_game']};";
    multiQuery($request);
  }
}
