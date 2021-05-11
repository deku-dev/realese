<?php
header("CountPages: no-page");
?>
<link rel="stylesheet" href="css/art.css" />
<?php
require_once 'connection.php';
$art = (int) $_GET['art'];
$cook = $_COOKIE['hash'];
$_SESSION['this_game'] = $art;
$verifyReq = "SELECT `views_id` FROM `views` WHERE DATEDIFF(CURRENT_DATE, `date`) < 2 AND `game_id`= {$art} AND `hash`='{$cook}'";
$verifRes = $mysqli->query($verifyReq);
if (!$verifRes->num_rows) {
  if ($_SESSION['auth_flag']) {
    $request = "SELECT `user_view_id` FROM `user_views` WHERE `game_id`= {$art} AND `user_id` = {$_SESSION['user_id']}";
    $res = $mysqli->query($request);
    if (!$res->num_rows) {
      $reqViews = "INSERT INTO `views`(`views_id`, `game_id`, `hash`, `date`) VALUES (NULL, {$art},'{$cook}',current_timestamp()); INSERT INTO `user_views`(`user_view_id`, `game_id`, `user_id`, `date`, `hash`) VALUES (NULL, {$art}, {$_SESSION['user_id']}, current_timestamp(), '{$cook}');UPDATE `game` SET `views`= `views`+1 WHERE `game_id` = {$art};";
    } else {
      $reqViews = "INSERT INTO `views`(`views_id`, `game_id`, `hash`, `date`) VALUES (NULL, {$art},'{$cook}',current_timestamp()); UPDATE `user_views` SET `date`=current_timestamp() WHERE `game_id` = {$art} AND `user_id` = {$_SESSION['user_id']}";
    }
  } else {
    $reqViews = "INSERT INTO `views`(`views_id`, `game_id`, `hash`, `date`) VALUES (NULL, {$art},'{$cook}',current_timestamp()); UPDATE `game` SET `views`= `views`+1 WHERE `game_id` = {$art};";
  }
  if ($mysqli->multi_query($reqViews)) {
    do {
      if ($result = $mysqli->store_result()) {
        $result->free();
      }
    } while ($mysqli->more_results() && $mysqli->next_result());
  }
}

function stmts($mysqli, $sqlrequest, $t, $one)
{
  $stmt = $mysqli->prepare($sqlrequest);
  $stmt->bind_param($t, $one);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result;
}
if (isset($_GET['art'])) {
  $request = "SELECT `game`.`downloads` AS 'dow',`game`.`game_id`,`game`.`name` AS 'nam',`game`.`pubdate`,`game`.`date`,`game`.`views` AS 'vie',`game`.`image` AS 'img',`fulldescip`.`description` AS 'des',`fulldescip`.`specification_json` AS 'sys',`fulldescip`.`media` AS 'med',`fulldescip`.`torrent_file` AS 'tor' FROM `game` LEFT JOIN `fulldescip` ON `fulldescip`.`game_id` = `game`.`game_id` WHERE `game`.`game_id` = ?";
  $r = stmts($mysqli, $request, 'i', $art);
  if ($r->num_rows > 0) {
    $resultG = $r->fetch_assoc();
    $request = "SELECT `avg`, `count` FROM `avg_rating` WHERE `game_id`=" . $art;
    $res = $mysqli->query($request)->fetch_assoc();
    $nam = $resultG['nam'];
    $vie = $resultG['vie'];
    $img = $resultG['img'];
    $sys = $resultG['sys'];
    $des = $resultG['des'];
    $med = $resultG['med'];
    $tor = $resultG['tor'];
    $dow = $resultG['dow'];
    $count = $res['count'] ? $res['count'] : 0;
    $avg = $res['avg'];
  }
  $request = "SELECT `game_id` FROM `favorites` WHERE `game_id`= " . $_SESSION['this_game'] . " AND `user_id`= " . $_SESSION['user_id'];
  $res = $mysqli->query($request);
  if ($res->num_rows) {
    $gameStatus = true;
  } else {
    $gameStatus = false;
  }
}
?>
<div class="art">
  <hr class="hr">
  <h2 id="game_name" class="block__title"><?php echo $nam ?></h2>
  <div class="block_info">
    <div class="img__block">
      <div class="favor__set">
        <img class="art_image" src="<?php echo $img; ?>" alt="<?php echo $nam ?>" />
        <div class="fav_set-block">
          <svg onclick="favoritSet()" class='fav_set-pict
          <?if ($gameStatus) {
  echo "like";
} else {
  echo "dislike";
}
?>' version="1.1" id="favorit-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 0 412.735 412.735" style="enable-background:new 0 0 412.735 412.735;" xml:space="preserve">

            <path id="favorit-path" class="review_path"
              d="M295.706,46.058C354.45,46.344,402,93.894,402.286,152.638 c0,107.624-195.918,214.204-195.918,214.204S10.449,258.695,10.449,152.638c0-58.862,47.717-106.58,106.58-106.58l0,0 c36.032-0.281,69.718,17.842,89.339,48.065C226.123,64.047,259.722,45.971,295.706,46.058z" />
            <path id="svg-like" class="favoth__path"
              d="M206.367,377.291c-1.854-0.024-3.664-0.567-5.224-1.567C193.306,371.544,0,263.397,0,152.638 C0,88.005,52.395,35.609,117.029,35.609l0,0c34.477-0.406,67.299,14.757,89.339,41.273 c41.749-49.341,115.591-55.495,164.932-13.746c26.323,22.273,41.484,55.02,41.436,89.501 c0,112.327-193.306,218.906-201.143,223.086C210.031,376.723,208.221,377.266,206.367,377.291z M117.029,56.507 c-53.091,0-96.131,43.039-96.131,96.131l0,0c0,89.861,155.167,184.424,185.469,202.188 c30.302-17.241,185.469-111.282,185.469-202.188c0.087-53.091-42.881-96.201-95.972-96.289 c-32.501-0.053-62.829,16.319-80.615,43.521c-3.557,4.905-10.418,5.998-15.323,2.44c-0.937-0.68-1.761-1.503-2.44-2.44 C179.967,72.479,149.541,56.08,117.029,56.507z" />

          </svg>
        </div>
      </div>
    </div>
    <div class="block__rating">
      <span class="views bl__ratg">
        <img src="asset/views.svg" alt="Просмотров" class="views__icon rat__icons">
        <div class="views__num">
          <?echo $vie; ?>
        </div>
      </span>
      <span class="rating bl__ratg">
        <img src="asset/rating.svg" alt="Рейтинг" class="rating__icon rat__icons">
        <div class="rating__num">
          <?echo $avg . " ({$count})"; ?>
        </div>
      </span>
      <span class="comment bl__ratg">
        <img src="asset/download.svg" alt="Загрузок" class="comment__icon rat__icons">
        <div class="comment__num">
          <?echo $dow; ?>
        </div>
      </span>
    </div>
    <hr class="hr">
    <div class="sys_requires">
      <?php
if ($sys) {
  $sys_assoc = json_decode($sys, true);
  foreach ($sys_assoc as $item => $item_count) {
    if ($item == "@@@@") {
      echo '<span class="DS_title system"' . $item . '</span>';
    }
    echo $item . ': ' . $item_count . '<br>';
  }
}
// TODO Сделать розбивку на разные цвета Название и значение
?>
    </div>
  </div>
  <div class="descrip_block">
    <div class="desc_title DS_title">Описание:</div>
    <div class="desc_text">
      <?php
echo $des;
?>
    </div>
  </div>
  <div class="media__block">
    <div class="media_title DS_title">Скриншоты:</div>
    <div class="screenshot">
      <?php
$med_assoc = json_decode($med, true);
$video = $med_assoc['video'];
$image = $med_assoc['screenshot'];
foreach ($image as $key => $value) {
  echo '<img class="screen_item"
        src="' . $value . '"
        alt="' . $nam . '" />';
}
?>

    </div>
    <div class="media_title DS_title">Трейлер | Гемплей:</div>
    <div id="video_block">
      <?php
foreach ($video as $key => $value) {
  echo '<div id="' . $value . '" class="youtube__block" style="background-image: url(https://i.ytimg.com/vi/' . $value . '/mqdefault.jpg);"><div class="filter"></div><div class="yo_icon"><img class="play_icon" src="asset/play__icons.svg" /></div></div>';
}
?>


    </div>
  </div>

  <div class="download">
    <?php
if ($tor) {
  $torrent = json_decode($tor, true);
  foreach ($torrent as $file => $size) {
    echo <<<EOD
<div class="torrent"><div class="torrent_size">$size</div><div class="torrent_file"><span data-fileg="$file" class="bl_file">Скачать</span></div></div><hr class="hrb">
EOD;
  }
}

?>

  </div>
  <div class="rating-area__block">

    <?
if ($_SESSION['auth_flag']) {
  $request = "SELECT `id_rev`,`date` AS 'revdate',`value`,`text_review` FROM `rating` WHERE `user_id`={$_SESSION['user_id']} AND `game_id`=" . $art;
  $res = $mysqli->query($request);
  $isRev = $res->num_rows ? "" : "hidden_form";
  $isNewRev = !$res->num_rows ? "" : "hidden_form";
  $userFeed = $res->fetch_assoc();
  $user_review = $userFeed['text_review'];
  $value = $userFeed['value'];
  $revId = $userFeed['id_rev'];
  $revdate = $userFeed['revdate'];
} else {
  $isRev = "hidden_form";
  $isNewRev = "hidden_form";
}
?>
    <div id="user__feedback" class="user_review <?echo $isRev ?>">
      <div class="review__elem">
        <div class="review__img-block"><img class="review__user-image" src="<?echo $_SESSION['picture'] ?>"
            alt="<?echo $_SESSION['user_name'] ?>">
        </div>
        <div class="text__review">
          <div class="review__username">
            <?echo $_SESSION['user_name'] ?>
          </div>
          <div class="review__data"><span id="value_review-js" class="review__value">
              <?echo $value ?> ★
            </span><span id="date_review-js" class="review__date">
              <?echo dateRus($revdate); ?>
            </span>
          </div>
          <div id="text_review-js" class="review__text-value">
            <?echo $user_review; ?>
          </div>
        </div>
      </div>
      <div id="rev__hand-js" class="review__hand-block">
        <span class="review_edit review_handler">Изменить</span>
        <span id="review_id-js" review-id="<?echo $revId ?>" class="review_delete review_handler">Удалить отзыв</span>
      </div>

    </div>
    <form id="review-send" name="rat" class="review_form <?echo $isNewRev; ?>">
      <div class="rating-area">
        <input type="radio" id="star-5" name="rating" value="5">
        <label for="star-5" title="Оценка «5»"></label>
        <input type="radio" id="star-4" name="rating" value="4">
        <label for="star-4" title="Оценка «4»"></label>
        <input type="radio" id="star-3" name="rating" value="3">
        <label for="star-3" title="Оценка «3»"></label>
        <input type="radio" id="star-2" name="rating" value="2">
        <label for="star-2" title="Оценка «2»"></label>
        <input type="radio" id="star-1" name="rating" value="1">
        <label for="star-1" title="Оценка «1»"></label>
        <div id="err_rating" class="err_rat-js">
          <svg height="20" style="overflow:visible;enable-background:new 0 0 32 32" viewBox="0 0 32 32" width="32"
            xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <circle cx="16" cy="16" id="BG" r="16" style="fill:#D72828;" />
            <path d="M14.5,25h3v-3h-3V25z M14.5,6v13h3V6H14.5z" style="fill:#E6E6E6;" />
          </svg>
        </div>
      </div>

      <div class="review__send">
        <textarea name="textrev" class="review__text"
          placeholder="Ваш отзыв (Необязательно)"><?echo $user_review; ?></textarea>
        <input name="review" class="review__submit" type="submit" value="Оставить отзыв">
      </div>

    </form>


  </div>
  <div class="review_block">
    <div class="title_review DS_title">Отзывы:</div>
    <div id="review_list-js" class="review_list">
    </div>
    <div class="load_review">
      <div data-revpage="0" id="load-new_review" class="loadrev_block">
        <div class="other_load">
          Показать еще
        </div>
        <div id="arrow_down-load">+</div>
        <div class="lds-spinner">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <img class="review__get-err" src="asset/error.svg" alt="error">
      </div>
    </div>
  </div>
</div>