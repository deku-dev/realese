<?php
require_once 'connection.php';
?>
<div id="block_progr">
  <div id="progress"></div>
  <div id="top_progr"></div>
</div>
<div id="rescheat" style="display:none;"></div>
<div id="count" class="count_pages" style="display:none;"></div>
<div class="header">
  <div class="inner__header">
    <a class="homepage" href="/">
      <div class="logo"><span class="ac">AC</span><span class="no__ac">Torrent.net</span></div>
    </a>

  </div>
  <div class="menu">
    <div class="menu-icon" id="menu" data-lst="toggle-nav">
      <div class="block_menu_bar">
        <span class="menu-icon__bar"></span>
      </div>
    </div>
    <nav class="nav" id="toggle-nav">
      <div id="search_block">
        <div class="search">
          <input class="search_input" type="search" name="search" id="search" placeholder="Искать Здесь..."
            autocomplete="off" />
          <button id="sear_btn" class="btn_search" type="submit">Поиск</button>
        </div>
        <ul id="autocomplete">
        </ul>
      </div>
      <div id="profile" class="prof_set
      <?php
if ($_SESSION['auth_flag']) {
  echo "user_lock_regs";
  // $request = "SELECT * FROM `users` WHERE `user_id`=(SELECT `user_id` FROM `users_session` WHERE `agent_hash`='" . $_COOKIE["_uida"] . "')";
  // $res = $mysqli->query($request);
  // $user = $res->fetch_assoc();
  $picture = $_SESSION['picture'];
  $nickname = $_SESSION['user_name'];
} else {
  echo "user_none_regs";
  $nickname = "";
  $picture = "asset/user.svg";
}?>">
        <a class="user-picture_link" href="?do=profile">
          <div id="user-pic__block" class="picture_prof"><img src="<?php echo $picture ?>" alt="" id="user-pic"></div>
        </a>
        <div id="user-name__block" class="prof_name">
          <span class="user__data" id="nick-user"><?php echo $nickname ?></span>
        </div>
        <span id="menu-user" class="tit-user"><span data-lst="block_reg" id="profile-menu" class="setlab">Войти</span>
          <a id="set_user-js" href="?exit" class="menu-uset__link link_setup"><img src="asset/exit-account.svg" alt=""
              class="menu-user__icon"></a>
        </span>
      </div>
      <ul id="nav_list">
        <li class="nav__item">
          <?php
function stmtc($stmt)
{
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc();
}
function pagecalc($all)
{
  global $gamePerPage;
  return ceil($all / $gamePerPage);
}
$lang = "1";
$stmt = $mysqli->prepare("SELECT COUNT(`game`.`game_id`) AS `lang`, (SELECT COUNT(`game_id`) FROM `game`) AS 'mc'
          FROM `game`
          LEFT JOIN `lang` ON `game`.`game_id` = `lang`.`game_id`
          WHERE `lang`.`lang_id` = ?");
$stmt->bind_param("s", $lang);
$var = stmtc($stmt);
$russian = $var['lang'];
$lang = "2";
$var = stmtc($stmt);
$england = $var['lang'];
$lang = "3";
$var = stmtc($stmt);
$otherl = $var['lang'];
$stmt->close();
$mainCount = $var['mc'];
?>
          <a id="mainlink" class="nav__link main__link link_setup" data-count="<?php echo pagecalc($mainCount); ?>"
            href="/">

            Все игры
          </a>
        </li>
        <li class="nav__item">
          <a class="nav__link main__link nav__link--plus" href="#" data-menu>
            Категория <span class="plus_icon"></span>
          </a>
          <ul class="nav__sub-list" data-height="">
            <?php

$category = mysqli_query($mysqli, "SELECT`category`.*, COUNT(`cat_game`.`cat_id`) AS 'count_game' FROM `category`,`cat_game` WHERE `category`.`cat_id` = `cat_game`.`cat_id` GROUP BY `category`.`cat_id`");
while ($cat = mysqli_fetch_assoc($category)) {
  echo '<li class="nav__sub-item"><a class="nav__link link_setup" data-count="' . pagecalc($cat['count_game']) . '" href="?ct=' . $cat['cat_id'] . '">' . $cat['cat_name'] . '</a></li>';
}
?>


          </ul>
        </li>
        <li class="nav__item">
          <a class="nav__link main__link nav__link--plus" data-menu href="#">
            Озвучка <span class="plus_icon"></span>
          </a>
          <ul class="nav__sub-list" data-height="">

            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="<?php echo pagecalc($russian); ?>" href="?lg=1"> Озвучка
                Русская</a>
            </li>
            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="<?php echo pagecalc($england); ?>" href="?lg=2">Озвучка
                Английская</a>
            </li>
            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="<?php echo pagecalc($otherl); ?>" href="?lg=3">Озвучка
                Другое</a>
            </li>
          </ul>
        </li>
        <li class="nav__item">
          <a class="nav__link main__link nav__link--plus" data-menu href="#">
            Год выпуска <span class="plus_icon"></span>
          </a>
          <?php
$year = array('2020', '2019', '2018', '2017', '2016');
$stmt = $mysqli->prepare("SELECT COUNT(`game_id`) AS `year` FROM `game` WHERE YEAR(`date`)=?");
$curyear = $year['0'];
$stmt->bind_param("s", $curyear);
$var = stmtc($stmt);
?>
          <ul class="nav__sub-list" data-height="">
            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="<?php echo pagecalc($var['year']); ?>"
                href="?yr=<?php echo $curyear; ?>"><?php echo $curyear; ?></a>
            </li>
            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="
              <?php
$curyear = $year['1'];
$var = stmtc($stmt);
echo pagecalc($var['year']);?>" href="?yr=<?php echo $curyear; ?>"><?php echo $curyear;
?></a>
            </li>
            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="
              <?php $curyear = $year['2'];
$var = stmtc($stmt);
echo pagecalc($var['year']);?>" href="?yr=<?php echo $curyear; ?>"><?php echo $curyear; ?></a>
            </li>
            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="
              <?php $curyear = $year['3'];
$var = stmtc($stmt);
echo pagecalc($var['year']);?>" href="?yr=<?php echo $curyear; ?>"><?php echo $curyear; ?></a>
            </li>
            <li class="nav__sub-item">
              <a class="nav__link link_setup" data-count="
              <?php
$curyear = $year['4'];
$var = stmtc($stmt);
echo pagecalc($var['year']);?>" href="?yr=<?php echo $curyear; ?>"><?php echo $curyear; ?></a>
            </li>
          </ul>
        </li>
        <li class="nav__item">
          <?php
$new = $mysqli->query("SELECT COUNT(`game_id`) AS new FROM `game` WHERE DATEDIFF(CURRENT_DATE, `date`) < 300");
$compl = $new->fetch_assoc();
?>
          <a class="nav__link main__link link_setup" data-count="<?php echo pagecalc($compl['new']); ?>" href="?new=">
            Новые игры
          </a>
        </li>
        <li class="nav__item">
          <a data-count="<?php echo 100 / $gamePerPage; ?>" href="?top=" class="nav__link main__link link_setup">Топ
            100</a>
        </li>
      </ul>
    </nav>
  </div>
</div>