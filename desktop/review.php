<?
// Review send in database
// POST['rating'] value int review
// POST['textrev'] text review
require_once "connection.php";
if (!empty($_POST['rating'])) {
  $text = escStr($_POST['textrev']);
  $value = (int) $_POST['rating'];
  $request = "SELECT `id_rev`,`value`, `text_review` FROM `rating` WHERE `game_id`={$_SESSION['this_game']} AND `user_id`=" . $_SESSION['user_id'];
  $res = $mysqli->query($request);
  if ($res->num_rows) {
    $result = $res->fetch_assoc();
    $request = "UPDATE `rating` SET ";
    if ($result['value'] != $_POST['rating']) {
      $request .= "`value`={$value}";
    }
    if ($result['text_review'] != $text) {
      if ($result['value'] != $value) {
        $request .= ",";
      }
      $request .= "`text_review`='{$text}'";
    }
    $request .= " WHERE `game_id`={$_SESSION['this_game']} AND `user_id`=" . $_SESSION['user_id'];
    $res = $mysqli->query($request);
    $insert = 0;
  } else {
    $request = "INSERT INTO `rating`(`id_rev`, `game_id`, `user_id`, `value`, `date`, `text_review`, `likes`) VALUES (NULL,{$_SESSION['this_game']},{$_SESSION['user_id']},{$value},current_timestamp(),'{$text}', 0)";
    $res = $mysqli->query($request);
    $insert = 1;
  }
  $jsonRes[0] = [
    "0" => $mysqli->insert_id,
    "1" => $value,
    "2" => $text,
    "3" => $insert,
  ];
  echo json_encode($jsonRes);
}
