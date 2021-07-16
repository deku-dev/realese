<?
if (isset($_GET["sort"])) {
  $searchArr = [
    "name" => "`game`.`name`",
    "date" => "`game`.`date`",
    "rati" => "`avg_rating`.`avg`",
    "popu" => "`game`.`views`",
  ];
  $request = "SELECT `game`.*, `avg`, SUBSTRING_INDEX(`description`, ' ', 40) AS 'desc',JSON_UNQUOTE(JSON_EXTRACT(`specification_json`, '$.Жанр')) AS 'genre' FROM `game` LEFT JOIN `cat_game` ON `cat_game`.`game_id` = `game`.`game_id` LEFT JOIN `fulldescip` ON `fulldescip`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `avg_rating`.`game_id` = `game`.`game_id` LEFT JOIN `lang` ON `lang`.`game_id` = `game`.`game_id` WHERE YEAR(`game`.`date`) >= " . (int) $_GET["min"] . " AND YEAR(`game`.`date`) <= " . (int) $_GET["max"];
  if (isset($_GET["cat"])) {
    $request .= " AND `cat_game`.`cat_id` = " . (int) $_GET["cat"];
  }
  if (isset($_GET["lang"])) {
    $request .= " AND `lang`.`lang_id` = " . (int) $_GET["cat"];
  }
  $request .= " ORDER BY {$searchArr[$_GET["sort"]]} DESC";
  echo $request;
  $game = $mysqli->query($request);
  if (!$game->num_rows) {
    http_response_code(404);
  }
  echo json_encode($game->fetch_all(), JSON_UNESCAPED_UNICODE);
}