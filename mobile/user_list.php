<?
$countGame = 30;
if (isset($_GET['views'])) {
  $request = "SELECT `game`.`game_id`, `game`.`name`, `game`.`views`, `game`.`image` FROM `game` LEFT JOIN `user_views` ON `user_views`.`game_id` = `game`.`game_id` WHERE `user_views`.`user_id` = " . (int)$_SESSION['user_id'] . " ORDER BY `user_views`.`date` DESC LIMIT " . (int)$_GET['views'] . "," . $countGame . ";";
}
if (isset($_GET['favorites'])) {
  $request = "SELECT `game`.`game_id`, `game`.`name`, `game`.`views`, `game`.`image` FROM `game` LEFT JOIN `favorites` ON `favorites`.`game_id` = `game`.`game_id` WHERE `favorites`.`user_id` = " . (int)$_SESSION['user_id'] . " ORDER BY `favorites`.`date` DESC LIMIT " . (int)$_GET['favorites'] . ", " . $countGame . ";";
}
