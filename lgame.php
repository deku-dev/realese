<?
require_once "desktop/connection.php";
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
$user = (int) $_SESSION['user_id'];
if (isset($_GET['art'])) {
  require_once "desktop/art.php";
} elseif (isset($_GET['ct'])) {
  $ct = (int) $_GET['ct'];
  $getGame = getPage("SELECT `game`.*,`avg`,SUBSTRING_INDEX(`description`, ' ', 40) AS 'desc',JSON_UNQUOTE(JSON_EXTRACT(`specification_json`, '$.Жанр')) AS 'genre',MAX(IF(`favorites`.`user_id`={$user}, 1, 0)) AS 'favorit' FROM `game` LEFT JOIN `cat_game` ON `cat_game`.`game_id` = `game`.`game_id` LEFT JOIN `fulldescip` ON `fulldescip`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `avg_rating`.`game_id` = `game`.`game_id` LEFT JOIN `favorites` ON `favorites`.`game_id` = `game`.`game_id` WHERE `cat_game`.`cat_id` = {$ct} GROUP BY `game`.`game_id`");
  $game = $mysqli->query($getGame);
  if (!$game->num_rows) {
    http_response_code(404);
  }
  echo json_encode($game->fetch_all(), JSON_UNESCAPED_UNICODE);
} elseif (isset($_GET['search']) && iconv_strlen($_GET['search'], 'UTF-8') > 3) {
  $searchReq = $mysqli->real_escape_string($_GET["search"]);
  $getGame = getPage("SELECT `game`.*,`avg`,SUBSTRING_INDEX(`description`, ' ', 40) AS 'desc',JSON_UNQUOTE(JSON_EXTRACT(`specification_json`, '$.Жанр')) AS 'genre',MAX(IF(`favorites`.`user_id` = {$user}, 1, 0)) AS 'favorit' FROM `game` LEFT JOIN `fulldescip` ON `fulldescip`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `avg_rating`.`game_id` = `game`.`game_id` LEFT JOIN `favorites` ON `favorites`.`game_id` = `game`.`game_id` WHERE `name` LIKE '%{$searchReq}%' OR MATCH(`name`) AGAINST('{$searchReq}' IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) > 0 GROUP BY `game`.`game_id`");
  $game = $mysqli->query($getGame);
  if (!$game->num_rows) {
    http_response_code(404);
  }
  echo json_encode($game->fetch_all(), JSON_UNESCAPED_UNICODE);
} elseif (isset($_GET["sort"])) {
  $searchArr = [
    "name" => "`game`.`name`",
    "date" => "`game`.`date`",
    "rati" => "`avg_rating`.`avg`",
    "popu" => "`game`.`views`",
  ];
  $getGame = "SELECT `game`.*, `avg`, SUBSTRING_INDEX(`description`, ' ', 40) AS 'desc',JSON_UNQUOTE(JSON_EXTRACT(`specification_json`, '$.Жанр')) AS 'genre',MAX(IF(`favorites`.`user_id`={$user},1,0)) AS 'favorit' FROM `game` LEFT JOIN `cat_game` ON `cat_game`.`game_id` = `game`.`game_id` LEFT JOIN `fulldescip` ON `fulldescip`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `avg_rating`.`game_id` = `game`.`game_id` LEFT JOIN `lang` ON `lang`.`game_id` = `game`.`game_id` LEFT JOIN `favorites` ON `favorites`.`game_id`=`game`.`game_id` WHERE YEAR(`game`.`date`) >= " . (int) $_GET["min"] . " AND YEAR(`game`.`date`) <= " . (int) $_GET["max"];
  if (!empty($_GET["cat"])) {
    $getGame .= " AND `cat_game`.`cat_id` = " . (int) $_GET["cat"];
  }
  if (!empty($_GET["lang"])) {
    $getGame .= " AND `lang`.`lang_id` = " . (int) $_GET["lang"];
  }
  $getGame .= " GROUP BY `game`.`game_id` ORDER BY {$searchArr[$_GET["sort"]]} DESC";

  $getGame = getPage($getGame);

  $game = $mysqli->query($getGame);
  if (!$game->num_rows) {
    http_response_code(404);
  }
  echo json_encode($game->fetch_all(), JSON_UNESCAPED_UNICODE);
} elseif (isset($_GET['do'])) {
  require_once "doing.php";
} else {
  $getGame = getPage("SELECT DISTINCT `game`.*,`avg`,SUBSTRING_INDEX(`description`, ' ', 40) AS 'desc',JSON_UNQUOTE(JSON_EXTRACT(`specification_json`, '$.Жанр')) AS 'genre',MAX(IF(`favorites`.`user_id`={$user},1,0)) AS 'favorit' FROM `fulldescip`,`game` LEFT JOIN `favorites` ON `favorites`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `avg_rating`.`game_id` = `game`.`game_id` WHERE `fulldescip`.`game_id` = `game`.`game_id` GROUP BY `game`.`game_id`");

  $game = $mysqli->query($getGame);
  if (!$game->num_rows) {
    http_response_code(404);
  }
  echo json_encode($game->fetch_all(), JSON_UNESCAPED_UNICODE);
}

// }
// <div class='content_item'>
//   <div class='img_block'>
//     <img class='img_item' src="{$res[' image']}" alt="{$res[' name']}" />
//     <div class='name_game'>{$res['name']}</div>
//   </div>
//   <div class='info_item'>
//     <div class='block_item'>
//       <div class='genre_item'>Жанр: {$res['genre']}</div>
//       <div class='views'>
//         <svg height='1.2vw' version='1.1' viewBox='0 -25 150 122' width='1.5vw' xmlns='http://www.w3.org/2000/svg'>
//           <use xlink:href='#views'></use>
//         </svg>
//         <span class='view_value'>{$res['views']}</span>
//       </div>
//     </div>
//     <div class='description'>" . strip_tags($res['desc']) . "...</div>
//     <div class='down_link'>
//       <a href='#' class='btn_down'>Скачать</a>
//       <div class='item_value'>
//         <span class='file_size'></span>
//         <span class='rat_icon'>&#9733;</span>
//         <span class='rating'>{$res['downloads']}</span>
//       </div>
//     </div>
//   </div>
// </div>