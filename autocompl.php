<?php 
require_once('mobile/connection.php');
if (isset($_GET['compl'])) {
  $wordAuto = $mysqli->real_escape_string($_GET["compl"]);
  $request = "SELECT `game_id`, `name` FROM `game` WHERE `name` LIKE '%".$wordAuto."%' LIMIT 0,5";
  $searComp = $mysqli->query($request);
  if ($searComp->num_rows > 0) {
    while ($game = $searComp->fetch_assoc()) {
      echo '<li data-games="?art='.$game['game_id'].'" class="compl_item game_url">'.$game['name'].'</li>';
    }
  }
}
?>