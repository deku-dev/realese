<?require_once "connection.php";
if ($_GET['do'] == "profile" && !$_SESSION['auth_flag']) {
  $new_url = "/desktop/";
  header('Location: ' . $new_url);
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <?require_once "head.php";?>
</head>

<body>
  <div id="elements-placeholder" style="display: none"></div>
  <?require_once "header.php"?>
  <?require_once "main.php"?>
  <?require_once "footer.php";?>
</body>

</html>