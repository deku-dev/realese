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
    if ($result['text_review'] != $_POST['textrev']) {
      if ($result['value'] != $_POST['rating']) {
        $request .= ",";
      }
      $request .= "`text_review`='{$text}'";
    }
    $request .= " WHERE `game_id`={$_SESSION['this_game']} AND `user_id`=" . $_SESSION['user_id'];
    $res = $mysqli->query($request);
    $action = "UPDATE";
  } else {
    $request = "INSERT INTO `rating`(`id_rev`, `game_id`, `user_id`, `value`, `date`, `text_review`) VALUES (NULL,{$_SESSION['this_game']},{$_SESSION['user_id']},{$value},current_timestamp(),'{$text}')";
    $res = $mysqli->query($request);
    $action = "INSERT";
  }
  $order = 1;
  $jsonRes[$order] = [
    "action" => $action,
    "rev_id" => $mysqli->insert_id,
    "value" => $value,
    "date" => strftime("%x"),
    "text_review" => $text,
    "likes" => 0,
    "nickname" => $_SESSION['user_name'],
    "picture" => $_SESSION['picture'],
  ];
  echo json_encode($jsonRes);
}