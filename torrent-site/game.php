<?php
// $start = microtime(true);
require_once 'mobile/connection.php';
if (isset($_GET['not'])) {
  header("Status: 404 Not Found");
  header("HTTP/1.0 404 Not Found");
  header("HTTP/1.1 404 Not Found");
  http_response_code(404);
}
function pagecalc($all)
{
  global $gamePerPage;
  return ceil($all / $gamePerPage);
}
function parseResult($res, $count)
{
  if ($res->num_rows > 0) {
    header("CountPages:" . pagecalc($count));
    header("Access-Control-Expose-Headers: CountPages");
    if (!isset($_GET['err'])) {
      echo '<div id="game__content">';
    }

    while ($game = $res->fetch_assoc()) {
      echo '<div class="item_content"><div class="block_item"><span data-games="?art=' . $game['game_id'] . '" class="link game_url"></span><div class="block_img"><img class="img_item" src="' . $game['image'] . '" alt="" /></div><div class="name_item">' . $game['name'] . '</div></div></div>';
    }
    if (!isset($_GET['err'])) {
      echo '<div>';
    }

  } else {
    header("Status: 404 Not Found");
    header("HTTP/1.0 404 Not Found");
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
  }
}
function getSort($sqlreq)
{
  if ($_GET['s'] == "") {
    return $sqlreq;
  }
  $x = $_GET['s'];
  $y = $_GET['st'] == 'desc' || $_GET['st'] == 'asc' ? $_GET['st'] : 'asc';
  $sortAr = array('n' => 'name', 'd' => 'pubdate', 'v' => 'views');
  return $sqlreq .= " ORDER BY `" . $sortAr[$x] . "` " . $y;
}

function searchSqlGame($link, $sqlrequest)
{
  if ($link->multi_query($sqlrequest)) {
    if ($result = $link->store_result()) {
      $count = $result->fetch_assoc();
    }
    if ($link->more_results() && $link->next_result()) {
      if ($result = $link->store_result()) {
        parseResult($result, $count['count']);
      }
    }
  }
}

if (isset($_GET['ct'])) {
  $request = "SELECT COUNT(`game`.`game_id`) AS 'count', `category`.`cat_name` FROM `game` LEFT JOIN `cat_game` ON `cat_game`.`game_id` = `game`.`game_id` LEFT JOIN `category` ON `cat_game`.`cat_id` = `category`.`cat_id` WHERE `cat_game`.`cat_id`= " . (int) $_GET['ct'] . ";";
  $request .= getPage("SELECT `game`.*, `category`.`cat_name` FROM `game` LEFT JOIN `cat_game` ON `cat_game`.`game_id` = `game`.`game_id` LEFT JOIN `category` ON `cat_game`.`cat_id` = `category`.`cat_id` WHERE `cat_game`.`cat_id`= " . (int) $_GET['ct']);
  searchSqlGame($mysqli, $request);
} elseif (isset($_GET['lg'])) {
  $request = "SELECT COUNT(`game`.`game_id`) AS 'count' FROM `game` LEFT JOIN `lang` ON `game`.`game_id` = `lang`.`game_id` WHERE `lang`.`lang_id` = " . (int) $_GET['lg'] . ";";
  $request .= getPage("SELECT `game`.* FROM `game` LEFT JOIN `lang` ON `game`.`game_id` = `lang`.`game_id` WHERE `lang`.`lang_id` = " . (int) $_GET['lg']);
  searchSqlGame($mysqli, $request);
} elseif (isset($_GET['yr'])) {
  $request = "SELECT COUNT(`game_id`) AS 'count' FROM `game` WHERE YEAR(`date`) = " . (int) $_GET['yr'] . ";";
  $request .= getPage("SELECT * FROM `game` WHERE YEAR(`date`) = " . (int) $_GET['yr']);
  searchSqlGame($mysqli, $request);
} elseif (isset($_GET['new'])) {
  $request = "SELECT COUNT(`game_id`) FROM `game` WHERE DATEDIFF(CURRENT_DATE, `date`) < 300;";
  $request .= getPage("SELECT * FROM `game` WHERE DATEDIFF(CURRENT_DATE, `date`) < 300");
  searchSqlGame($mysqli, $request);
} elseif (isset($_GET['search'])) {
  $numPage = $mysqli->real_escape_string($_GET["search"]);
  $request = "SELECT COUNT(`game_id`) AS 'count' FROM `game` WHERE `name` LIKE '%" . $numPage . "%' OR MATCH (`name`) AGAINST ('" . $numPage . "' IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) > 5;";

  $request .= getPage("SELECT * FROM `game` WHERE `name` LIKE '%" . $numPage . "%' OR MATCH (`name`) AGAINST ('" . $numPage . "' IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) > 5");
  echo $request;
  searchSqlGame($mysqli, $request);
} elseif (isset($_GET['art'])) {
  require_once 'mobile/art.php';
} elseif (isset($_GET['err'])) {
  $request = "SELECT * FROM `game` ORDER BY `game`.`views` DESC LIMIT 0,4";
  $res = $mysqli->query($request);
  parseResult($res, "1");
} elseif (isset($_GET['top'])) {
  $request = "SELECT COUNT(`game_id`) AS 'count' FROM `top100` ORDER BY `views` DESC;";
  $request .= getPage("SELECT * FROM `top100` ORDER BY `views` DESC");
  searchSqlGame($mysqli, $request);
} elseif (isset($_GET["do"])) {
  switch ($_GET['do']) {
    case 'feedback':
      require_once 'mobile/feedback.php';
      break;
    case 'about':
      require_once 'about.php';
      break;
    case 'reg':
      require_once 'reg.php';
      break;
    case 'profile':
      require_once 'profile.php';
      break;
  }
} else {
  $request = "SELECT COUNT(`game_id`) AS 'count' FROM `game`;";
  $request .= getPage("SELECT * FROM `game`");
  searchSqlGame($mysqli, $request);
}

// echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 7).' сек.';