<?
require_once "desktop/connection.php";
function calcPage($all)
{
  global $gamePerPage;
  return ceil($all / $gamePerPage);
}
$arrPage = [];
if (isset($_GET['ct'])) {
  $countReq = "SELECT COUNT(DISTINCT `game`.`game_id`) AS 'count' FROM `game` LEFT JOIN `cat_game` ON `cat_game`.`game_id` = `game`.`game_id` WHERE `cat_game`.`cat_id` = " . (int) $_GET['ct'];
  $res = $mysqli->query($countReq);
  $result = $res->fetch_assoc();
  $arrPage['count'] = calcPage($result['count']);
} elseif (isset($_GET['search']) && iconv_strlen($_GET['search'], 'UTF-8') > 4) {
  $reqSer = escStr($_GET['search']);
  $request = "SELECT COUNT(`game_id`) AS 'count' FROM `game` WHERE `name` LIKE '%{$reqSer}%' OR MATCH(`name`) AGAINST('{$reqSer}' IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) > 0";
  $game = $mysqli->query($request);
  $result = $game->fetch_assoc();
  $arrPage['count'] = calcPage($result['count']);
  $arrPage['all'] = $result['count'];
} elseif (isset($_GET['art'])) {
  $arrPage['count'] = 1;
} elseif (isset($_GET['do'])) {
  $arrPage['count'] = 1;
  $arrPage['all'] = 0;
} elseif (isset($_GET["sort"])) {
  $request = "SELECT COUNT(DISTINCT `game`.`game_id`) AS 'count' FROM `game` LEFT JOIN `cat_game` ON `cat_game`.`game_id` = `game`.`game_id` LEFT JOIN `fulldescip` ON `fulldescip`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `avg_rating`.`game_id` = `game`.`game_id` LEFT JOIN `lang` ON `lang`.`game_id` = `game`.`game_id` WHERE YEAR(`game`.`date`) >= " . (int) $_GET["min"] . " AND YEAR(`game`.`date`) <= " . (int) $_GET["max"];
  if (!empty($_GET["cat"])) {
    $request .= " AND `cat_game`.`cat_id` = " . (int) $_GET["cat"];
  }
  if (!empty($_GET["lang"])) {
    $request .= " AND `lang`.`lang_id` = " . (int) $_GET["lang"];
  }
  $game = $mysqli->query($request);
  $result = $game->fetch_assoc();
  $arrPage['count'] = calcPage($result['count']);
  $arrPage['all'] = $result['count'];
} else {
  $countReq = "SELECT COUNT(`game_id`) AS 'count' FROM `game`";
  $res = $mysqli->query($countReq);
  $result = $res->fetch_assoc();
  $arrPage['count'] = calcPage($result['count']);
}
header('Content-Type: application/json');
echo json_encode($arrPage);