<?
require_once "desktop/connection.php";
$arrStat = [];
if (!empty($_POST["lpass"])) {
  $lnick = escStr($_POST["nick"]);
  $lpass = escStr($_POST["lpass"]);
  $searchNick = "SELECT `nickname`, `user_id`, `password`, `picture`, `banned_user` FROM `users` WHERE `nickname` = '{$lnick}'";
  $res = $mysqli->query($searchNick);
  if (!$res->num_rows) {
    $arrStat['err'] = "pass";
    die(json_encode($arrStat));
  }
  $pass = $res->fetch_assoc();
  $arrStat['picture'] = $pass['picture'];
  $arrStat['nick'] = $pass['nickname'];

  if (password_verify($lpass, $pass["password"]) && !$pass['banned_user']) {
    $UserAgent = $_SERVER['HTTP_USER_AGENT'];
    $UserHash = createHash($pass["password"] . $UserAgent . $_SESSION['ip']);
    $request = "INSERT INTO `users_session` (`user_id`, `agent_hash`, `date`, `ip`, `user_agent`, `banned`) VALUES ('{$pass['user_id']}', '{$UserHash}', current_timestamp(), '" . $_SESSION['ip'] . "', '{$UserAgent}', '0');";
    $mysqli->query($request);
    setcookie("_uida", $UserHash, time() + 60 * 60 * 24 * 365, '/');
    $_SESSION['hash'] = $UserHash;
    $_SESSION['user_name'] = $pass['nickname'];
    $_SESSION['picture'] = $pass['picture'];
    $_SESSION['user_id'] = (int) $pass['user_id'];
    $_SESSION['auth_flag'] = true;
    setcookie("_uide", createHash("/" . $_SESSION['user_id'] . $_SESSION['user_name']), time() + 60 * 60 * 24 * 365, '/');
    $arrStat['act'] = "log";
    echo json_encode($arrStat);
  } else {
    $arrStat['err'] = "pass";
  }
} elseif (!empty($_POST["rpass"])) {
  $nick = escStr($_POST["nick"]);
  $mail = escStr($_POST["mail"]);
  $verify = "SELECT `nickname`, `email` FROM `users` WHERE `nickname`='{$nick}' OR `email`='{$mail}'";
  $verRes = $mysqli->query($verify);
  $res = $verRes->fetch_assoc();
  if ($nick == $res['nickname']) {
    $arrStat['err'] = "nick";
    die(json_encode($arrStat));
  }
  if ($res['email'] == $mail) {
    $arrStat['err'] = "email";
    die(json_encode($arrStat));
  }
  $rpass = password_hash($_POST["rpass"], PASSWORD_DEFAULT);
  if (!($stmt = $mysqli->prepare("INSERT INTO `users`(`user_id`, `password`, `nickname`, `date`, `email`, `picture`, `banned_user`, `about`) VALUES (NULL, ?, ?, current_timestamp(), ?, 'asset/user.svg', 0, '')"))) {
    // echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
    http_response_code(500);
  }
  if (!$stmt->bind_param("sss", $rpass, $nick, $mail)) {
    // echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
    http_response_code(500);
  }
  if (!$stmt->execute()) {
    // echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
    http_response_code(500);
  }

  $arrStat['act'] = "reg";
  echo json_encode($arrStat);
}