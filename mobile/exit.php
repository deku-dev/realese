<?

unset($_SESSION['user_name']);
unset($_SESSION['date']);
unset($_SESSION['about']);
unset($_SESSION['picture']);
unset($_SESSION['user_id']);
unset($_SESSION['hash']);
$_SESSION['auth_flag'] = false;
setcookie("_uida", '', time() - 3600, '/');
setcookie("_uide", "", time() - 3600, '/');

// SELECT `users`.`user_id`, `users`.`about`, `users`.`date`, `users`.`password` AS 'pass', `users`.`nickname` AS 'nick', `users`.`user_id`, `users`.`picture` AS 'pict', `users_session`.`user_agent` AS 'usag', `users_session`.`banned` AS 'bann', `users_session`.`ip`, (SELECT COUNT(`game_id`) FROM `favorites` WHERE `user_id`=`users`.`user_id`), (SELECT COUNT(`game_id`) FROM `user_views` WHERE `user_id`=`users`.`user_id`), (SELECT `date` FROM `users_session` WHERE `user_id`=`users`.`user_id` LIMIT 0,1) FROM `users` LEFT JOIN `users_session` ON `users_session`.`user_id` = `users`.`user_id` WHERE `users_session`.`agent_hash` = '41599559630a621ad4011b6b748fe8364125bb9b52cc66943c33019f30e52a0d' 


// SELECT `user_views`.`game_id`, `favorites`.`game_id`, `users`.`user_id`, `users`.`about`, `users`.`date`, `users`.`password` AS 'pass', `users`.`nickname` AS 'nick', `users`.`user_id`, `users`.`picture` AS 'pict', `users_session`.`user_agent` AS 'usag', `users_session`.`banned` AS 'bann', `users_session`.`ip` FROM `users_session` LEFT JOIN `users` ON `users`.`user_id`=`users_session`.`user_id` RIGHT JOIN `favorites` ON `favorites`.`user_id`=`users_session`.`user_id` LEFT JOIN `user_views` ON `user_views`.`user_id`=`users_session`.`user_id` WHERE `users_session`.`agent_hash` = '41599559630a621ad4011b6b748fe8364125bb9b52cc66943c33019f30e52a0d'