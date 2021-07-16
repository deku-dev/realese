<?php
require_once "mobile/connection.php";
// ! Login
if (isset($_POST["lnick"]) && isset($_POST["lpass"])) {
  $lnick = escStr($_POST["lnick"]);
  $lpass = escStr($_POST["lpass"]);
  if (!($stmt = $mysqli->prepare("SELECT `nickname`, `user_id`, `password`, `picture` FROM `users` WHERE `nickname` = ?"))) {
    http_response_code(500);
  }
  if (!$stmt->bind_param("s", $lnick)) {
    http_response_code(500);
  }
  if (!$stmt->execute()) {
    http_response_code(500);
  }
  if (!($res = $stmt->get_result())) {
    http_response_code(500);
  }
  $pass = $res->fetch_assoc();
  $arrayUser = array(
    'picture' => $pass['picture'],
    'nick' => $pass['nickname'],
  );
  if (password_verify($lpass, $pass["password"]) && !$pass['banned_user']) {
    $UserAgent = $_SERVER['HTTP_USER_AGENT'];
    $UserHash = createHash($pass["password"] . $UserAgent . $_SESSION['ip']);
    $request = "INSERT INTO `users_session` (`user_id`, `agent_hash`, `date`, `ip`, `user_agent`, `banned`) VALUES ('{$pass['user_id']}', '{$UserHash}', current_timestamp(), '" . $_SESSION['ip'] . "', '{$UserAgent}', '0');";
    $mysqli->query($request);
    header("Under:" . $UserHash);
    setcookie("_uida", $UserHash, time() + 60 * 60 * 24 * 365, '/');
    $_SESSION['hash'] = $UserHash;
    $_SESSION['user_name'] = $pass['nickname'];
    $_SESSION['picture'] = $pass['picture'];
    $_SESSION['user_id'] = (int) $pass['user_id'];
    $_SESSION['auth_flag'] = true;
    setcookie("_uide", createHash("/" . $_SESSION['user_id'] . $_SESSION['user_name']), time() + 60 * 60 * 24 * 365, '/');
    echo json_encode($arrayUser);
  } else {
    http_response_code(401);
  }
}

// ! Register
if (isset($_POST["rmail"]) && isset($_POST["rnick"]) && isset($_POST["rpass"])) {
  $nick = escStr($_POST["rnick"]);
  $verify = "SELECT `user_id` FROM `users` WHERE `nickname`=" . $nick;
  $verRes = $mysqli->query($verify);
  if ($verRes->num_rows) {
    http_response_code(401);
  }
  $rpass = password_hash(htmlspecialchars($_POST["rpass"]), PASSWORD_DEFAULT);
  if (!($stmt = $mysqli->prepare("INSERT INTO `users`(`user_id`, `password`, `nickname`, `date`, `email`, `picture`, `banned_user`, `about`) VALUES (NULL, ?, ?, current_timestamp(), ?, 'asset/user.svg', 0, '')"))) {
    // echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
    http_response_code(500);
  }
  if (!$stmt->bind_param("sss", $rpass, escStr($_POST["rnick"]), $_POST["rmail"])) {
    // echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
    http_response_code(500);
  }
  if (!$stmt->execute()) {
    // echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
    http_response_code(500);
  }
  header("Under:" . $rpass);
}
