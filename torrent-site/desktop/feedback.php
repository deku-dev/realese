<?
require_once "connection.php";
function fix_feed($string)
{
  global $mysqli;
  return $mysqli->real_escape_string($string);
}
if (isset($_POST['name'])) {
  $name = fix_feed($_POST['name']);
  $email = fix_feed($_POST['email']);
  $theme = fix_feed($_POST['theme']);
  $text = fix_feed($_POST['text']);
  $request = "INSERT INTO `message` (`mes_id`, `ip`, `forename`, `email`, `theme`, `text`, `date`) VALUES (NULL, '{$_SERVER['REMOTE_ADDR']}', '{$name}', '{$email}', '{$theme}', '{$text}', current_timestamp())";
  $res = $mysqli->query($request);
}
//
