<?
if (empty($_COOKIE['_uida']) && isset($_SESSION['hash'])) {
  setcookie('_uida', $_SESSION['hash'], time() + 60 * 60 * 24 * 365, '/');
}
if (isset($_COOKIE['_uida']) && empty($_SESSION['hash'])) {
  $_SESSION['hash'] = $_COOKIE["_uida"];
}
if (isset($_SESSION['hash']) && isset($_COOKIE['_uida'])) {
  $_SESSION['auth_flag'] = false;
}
if ($_SESSION['auth_flag']) {
  $uide = createHash("/" . $_SESSION['user_id'] . $_SESSION['user_name']);
  if ($_COOKIE["_uide"] != $uide) {
    require_once "exit.php";
  }
}
if (isset($_SESSION['hash']) && !$_SESSION['auth_flag'] || !$_SESSION['user_id'] || !$_SESSION['user_name']) {
  $request = "SELECT COUNT(DISTINCT `rating`.`game_id`) AS 'rating', COUNT(DISTINCT `user_views`.`game_id`) AS 'views', COUNT(DISTINCT `favorites`.`game_id`) AS 'favorites',`users`.`user_id`,`users`.`about`,`users`.`date`,`users`.`password` AS 'pass',`users`.`nickname` AS 'nick',`users`.`user_id`,`users`.`picture` AS 'pict',`users_session`.`user_agent` AS 'usag',`users_session`.`banned` AS 'bann',`users_session`.`ip` FROM `users_session` LEFT JOIN `users` ON `users`.`user_id` = `users_session`.`user_id` LEFT JOIN `favorites` ON `favorites`.`user_id` = `users_session`.`user_id` LEFT JOIN `user_views` ON `user_views`.`user_id` = `users_session`.`user_id` LEFT JOIN `rating` ON `rating`.`user_id` = `users_session`.`user_id` WHERE `users_session`.`agent_hash` = '" . $_COOKIE["_uida"] . "'";
  $res = $mysqli->query($request);
  if ($res->num_rows) {
    $user = $res->fetch_assoc();
    if (createHash($user["pass"] . $_SERVER['HTTP_USER_AGENT'] . $user['ip']) == $_SESSION['hash'] && !$user['bann']) {
      $_SESSION['user_name'] = $user['nick'];
      $_SESSION['picture'] = $user['pict'];
      $_SESSION['about'] = $user["about"];
      $_SESSION['date'] = $user['date'];
      $_SESSION['favorites'] = $user["favorites"];
      $_SESSION['views'] = $user['views'];
      $_SESSION['rating'] = $user['rating'];
      $_SESSION['user_id'] = (int) $user['user_id'];
      $_SESSION['auth_flag'] = true;
      setcookie("_uide", createHash("/" . $_SESSION['user_id'] . $_SESSION['user_name']), time() + 60 * 60 * 24 * 365, '/');
    }
  } else {
    require_once "exit.php";
  }
}
