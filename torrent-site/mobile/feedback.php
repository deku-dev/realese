<?php
header("CountPages: no-page");
function fix_str($conn, $string)
{
  return htmlentities(fix_st($conn, $string));
}
function fix_st($conn, $string)
{
  return $conn->real_escape_string($string);
}
function specialChars($name, $email, $theme, $text)
{
  global $feed;
  $select = $feed->query("INSERT INTO `message` (`mes_id`, `ip`, `forename`, `email`, `theme`, `text`, `date`) VALUES (NULL, '" . $_SERVER['REMOTE_ADDR'] . "', '" . $name . "', '" . $email . "', '" . $theme . "', '" . $text . "', current_timestamp())");
  if ($select) {
    echo 'hhhh';
  }
  return $select;
}
if ((isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) && isset($_POST['name']) && isset($_POST['theme']) && isset($_POST['text'])) {
  $feed = new mysqli(
    "localhost",
    'feed_user',
    'password',
    'games'
  );
  if (!$feed) {
    die('Connect Error (' . $feed->connect_errno . ') ' . $feed->connect_error);
  }
  echo specialChars(fix_str($feed, $_POST['name']), fix_str($feed, $_POST['email']), fix_str($feed, $_POST['theme']), fix_str($feed, $_POST['text']));
}

?>
<!-- <script src="js/script.js"></script> -->
<link rel="stylesheet" href="css/feedback.css">
<title>Обратная Связь</title>
<div class="feedback__block">
  <div class="feedback__title">Обратная связь</div>
  <form id="feed__form" name="feedback_form" action="feedback.php" method="post">
    <input minlength="2" maxlength="100" required class="feed__text" type="text" name="name" placeholder="Ваше имя">
    <input minlength="6" maxlength="150" required class="feed__text" type="email" name="email" placeholder="Email">
    <input maxlength="150" autocomplete="off" required class="feed__text" type="text" name="theme"
      placeholder="Тема Сообщения">
    <textarea minlength="6" maxlength="200" required class="feed__text" name="text" cols="30" rows="10"
      placeholder="Текст сообщения"></textarea>
    <div class="g-recaptcha"></div>

    <div class="feed__op">
      <input id="feed_submit" name="submit" class="feed__submit" type="submit" value="Отправить">
      <span id="res__status" class="status">
      </span>
      <div class="lds">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </form>
</div>
