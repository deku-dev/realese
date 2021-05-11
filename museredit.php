<?
require_once "mobile/connection.php";
class SimpleImage
{

  public $image;
  public $image_type;
  public $extension;
  public $image_name;

  public function getFileName($path)
  {
    $path = $path ? $path . '/' : '';
    $name = $_SESSION['user_name'];
    $file = $path . $name;
    return $name;
  }
  public function load($filename)
  {
    $image_info = getimagesize($filename);
    $this->image_type = $image_info[2];
    $this->extension = image_type_to_extension($this->image_type);
    if ($this->image_type == IMAGETYPE_JPEG) {
      $this->image = imagecreatefromjpeg($filename);
    } elseif ($this->image_type == IMAGETYPE_GIF) {
      $this->image = imagecreatefromgif($filename);
    } elseif ($this->image_type == IMAGETYPE_PNG) {
      $this->image = imagecreatefrompng($filename);
    }
  }
  public function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 85, $permissions = null)
  {
    if ($image_type == IMAGETYPE_JPEG) {
      imagejpeg($this->image, $filename, $compression);
    } elseif ($image_type == IMAGETYPE_GIF) {
      imagegif($this->image, $filename);
    } elseif ($image_type == IMAGETYPE_PNG) {
      imagepng($this->image, $filename);
    }
    if ($permissions != null) {
      chmod($filename, $permissions);
    }
  }
  public function output($image_type = IMAGETYPE_JPEG)
  {
    $nameimg = "upload/" . $this->getFileName("upload") . $this->extension;
    if ($image_type == IMAGETYPE_JPEG) {
      imagejpeg($this->image, $nameimg);
    } elseif ($image_type == IMAGETYPE_GIF) {
      imagegif($this->image);
    } elseif ($image_type == IMAGETYPE_PNG) {
      imagepng($this->image);
    }
    return $nameimg;
  }
  public function getWidth()
  {
    return imagesx($this->image);
  }
  public function getHeight()
  {
    return imagesy($this->image);
  }
  public function resizeToHeight($height)
  {
    $ratio = $height / $this->getHeight();
    $width = $this->getWidth() * $ratio;
    $this->resize($width, $height);
  }
  public function resizeToWidth($width)
  {
    $ratio = $width / $this->getWidth();
    $height = $this->getheight() * $ratio;
    $this->resize($width, $height);
  }
  public function scale($scale)
  {
    $width = $this->getWidth() * $scale / 100;
    $height = $this->getheight() * $scale / 100;
    $this->resize($width, $height);
  }
  public function resize($width, $height)
  {
    $new_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->image = $new_image;
  }
}

if (!empty($_FILES['image']) || !empty($_POST['nick']) || !empty($_POST['about']) || !empty($_POST['pass']) && !empty($_POST['lastpass'])) {
  $request = "UPDATE `users`,`users_session` SET ";
  if (!empty($_FILES['image']['tmp_name'])) {
    $size = 3145728;
    $fileTmpName = $_FILES['image']['tmp_name'];
    if (filesize($fileTmpName) > $size) {
      return false;
    }
    $image = new SimpleImage();
    $image->load($_FILES['image']['tmp_name']);
    $image->resizeToWidth(850);
    $nameUrl = $image->output();
    $request .= "`users`.`picture`='" . $nameUrl . "',";
  }
  if (!empty($_POST['nick'])) {
    $request .= "`users`.`nickname`='" . escStr($_POST['nick']) . "',";
  }

  if (!empty($_POST['about'])) {
    $request .= "`users`.`about`='" . escStr($_POST['about']) . "',";
  }

  if (!empty($_POST['lastpass']) && !empty($_POST['pass'])) {
    $passReg = "SELECT `users`.`user_id`,`users`.`password` AS 'pass',`users`.`nickname` AS 'nick',`users_session`.`user_agent` AS 'usag' FROM `users_session` LEFT JOIN `users` ON `users`.`user_id` = `users_session`.`user_id` WHERE `users_session`.`agent_hash` = '{$_SESSION['hash']}'";
    $passRes = $mysqli->query($passReg);
    $passUser = $passRes->fetch_assoc();
    if (password_verify($_POST['lastpass'], $passUser["pass"])) {
      $pass = password_hash(escStr($_POST["rpass"]), PASSWORD_DEFAULT);
      $_SESSION['hash'] = createHash($pass . $_SERVER['HTTP_USER_AGENT'] . $_SESSION['ip']);
      $request .= "`users`.`password`='{$pass}',`users_session`.`agent_hash`='{$_SESSION['hash']}',`users_session`.`ip`='{$_SESSION['ip']}',";
    }
  }
  $request = rtrim($request, ",");
  $request .= " WHERE `users_session`.`user_id`=`users`.`user_id` AND `users`.`user_id`=" . $_SESSION['user_id'];
  if ($mysqli->query($request)) {
    // echo $_SESSION['hash'];
  }
}
