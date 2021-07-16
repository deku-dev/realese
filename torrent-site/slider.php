<div id="to_top" class="slider">
  <div id="slider__wrapper">
    <?
$getTopGame = "SELECT * FROM `game` ORDER BY `downloads` DESC,`views` DESC LIMIT 0,12";
$top = $mysqli->query($getTopGame);
while ($res = $top->fetch_assoc()) {
  echo '<div class="slider__item second_item"><div class="cent__item"><img class="slider__img" src="' . $res['image'] . '" alt="' . $res['name'] . '" /><span class="fil_span" data-url="?art=' . $res["game_id"] . '"></span><div class="title_item">' . $res['name'] . '</div></div></div>';
}
?>
  </div>
  <a class="slider__control slider__control_left" href="#" role="button"></a>
  <a class="slider__control slider__control_right slider__control_show" href="#" role="button"></a>
</div>