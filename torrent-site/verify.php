<?php
require_once "mobile/connection.php";
$errorArr = [];
if (isset($_POST["rmail"])) {
  $email = $mysqli->real_escape_string($_POST["rmail"]);
  $reg = "SELECT `user_id` FROM `users` WHERE `email` = '" . $email . "'";
  $res = $mysqli->query($reg);
  if ($res->num_rows == 1) {
    $errorArr["email"] = "Email уже существует";
    echo json_encode($errorArr);
    http_response_code(601);
  }
} elseif (isset($_POST["rnick"])) {

  $nick = $mysqli->real_escape_string($_POST["rnick"]);
  $reg = "SELECT `user_id` FROM `users` WHERE `nickname` = '" . $nick . "'";
  $res = $mysqli->query($reg);
  if ($res->num_rows == 1) {
    $errorArr["email"] = "Никнейм уже существует";
    echo json_encode($errorArr);
    http_response_code(600);
  }
}