<?
if (!$_SESSION['auth_flag']) {
  die("Войдите или зарегистрируйтесь на сайте");
}

$request = "SELECT `game`.`downloads`,`game`.`name`,`game`.`game_id`,`game`.`date`,`game`.`views`,`game`.`image`, `avg_rating`.`avg`,`avg_rating`.`count` FROM `user_views` LEFT JOIN `game` ON `user_views`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `user_views`.`game_id` = `avg_rating`.`game_id` WHERE `user_views`.`user_id` = {$_SESSION['user_id']} ORDER BY `user_views`.`date` DESC LIMIT 0,30; SELECT `game`.`downloads`,`game`.`name`,`game`.`game_id`,`game`.`date`,`game`.`views`,`game`.`image`, `avg_rating`.`avg`,`avg_rating`.`count` FROM `favorites` LEFT JOIN `game` ON `favorites`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `favorites`.`game_id` = `avg_rating`.`game_id` WHERE `favorites`.`user_id` = {$_SESSION['user_id']} ORDER BY `favorites`.`date` DESC LIMIT 0,30;";
if ($mysqli->multi_query($request)) {
  $views = $mysqli->store_result();
  if ($mysqli->more_results() && $mysqli->next_result()) {
    $favorites = $mysqli->store_result();
  }
}
?>
<link rel="stylesheet" href="css/profile.css">
<div class="profile__block">
  <div class="profile__header">
    <div class="profile__picture-block">
      <img onerror="this.src='asset/user.svg'" src="<?echo $_SESSION['picture'] ?>" alt="" class="profile__picture">
    </div>
    <div class="header__name-block">
      <span class="header__user-name">
        <?
echo $_SESSION['user_name'];
?>
      </span>
    </div>
  </div>
  <hr class="hrp">
  <div class="profile__menu">
    <nav id="profile__nav-js" class="profile__nav">
      <input id="set_lab" name="tabs" data-profile="settings" type="radio" class="profile__link" checked>
      <label for="set_lab" class="profile__link-nav">
        Настройки
      </label>
      <input data-profile="favorites" id="fav_lab" name="tabs" type="radio" class="profile__link">
      <label for="fav_lab" class="profile__link-nav">
        Избранное
      </label>
      <input data-profile="recent" id="rec_lab" name="tabs" type="radio" class="profile__link">
      <label for="rec_lab" class="profile__link-nav">
        Недавнее
      </label>
    </nav>
  </div>
  <hr class="hrp">
  <div class="profile__content">
    <div id="settings" class="check__profile-menu profile__content-block">
      <table class="user__data-profile">
        <tr class="user-block__data">
          <td class="user-span">Никнейм: </td>
          <td class="user-value">
            <?echo $_SESSION['user_name'] ?>
          </td>
        </tr>
        <tr class="user-block__data">
          <td class="user-span">Дата регистрации: </td>
          <td class="user-value">
            <?echo $_SESSION['date'] ?>
          </td>
        </tr>
        <tr class="user-block__data">
          <td class="user-span">Дней на сайте: </td>
          <td class="user-value">
            <?
echo date_diff(new DateTime(), new DateTime($_SESSION['date']))->days;
?>
          </td>
        </tr>
        <tr class="user-block__data">
          <td class="user-span">Кол-во комментариев: </td>
          <td class="user-value">
            <?echo $_SESSION['rating']; ?>
          </td>
        </tr>
        <tr class="user-block__data">
          <td class="user-span">Избранное: </td>
          <td class="user-value">
            <?echo $_SESSION['favorites'] ?>
          </td>
        </tr>
        <tr class="user-block__data">
          <td class="user-span">Просмотрено: </td>
          <td class="user-value">
            <?echo $_SESSION['views'] ?>
          </td>
        </tr>
        <tr class="user-block__data">
          <td class="user-span">О себе: </td>
          <td class="user-value">
            <?echo $_SESSION['about'] ?>
          </td>
        </tr>
        <tr class="user-block__data">
          <td class="user-span">Скачанных игр:</td>
          <td class="user-value">
            <?
$request = "SELECT COUNT(`id_down`) AS 'down_count' FROM `load_list` WHERE `user_id`=" . $_SESSION['user_id'];
$res = $mysqli->query($request);
$result = $res->fetch_assoc();
echo $result['down_count']?>
          </td>
        </tr>
      </table>
      <button id="btn_edit-user" class="profile-input edit-user__profile">Редактировать профиль</button>
      <form name="fomrff" id="form-edit__js" action="#" class="form-edit__profile">
        <input autocomplete="username" name="nick" placeholder="Никнейм" type="text"
          class="profile-input edit-user__nick" minlength="3">

        <textarea name="about" placeholder="О себе" type="text" class="profile-input edit-user__about" cols="30"
          rows="10" value="name" maxlength="300"></textarea>
        <div>Изменить пароль:</div>
        <div id="pass-edit__show">
          <label for="lastpass" class="open_pass">
            <input placeholder="Старый пароль" autocomplete="current-password" minlength="8" type="password"
              id="lastpass" name="lastpass" class="profile-input">
            <span class="profile_pass-eye pass_control">
              <span class="icon_aye"></span>
            </span>
          </label>
          <label for="pass_seting" class="open_pass">
            <input autocomplete="new-password" placeholder="Новый пароль" minlength="8" type="password" id="pass_seting"
              name="pass" class="profile-input edit-user__pass">
            <span class="pass_control profile_pass-eye">
              <span class="icon_aye"></span>
            </span>
          </label>
        </div>
        <div class="block-edit__img"><input type="file" name="image" id="user-image__js">
          <label for="user-image__js" class="profile-input edit-user__image">Выбрать картинку</label>
        </div>
        <div id="thumbnail-image" class="thumb__image"></div>
        <input class="profile-input edit-user__profile" type="submit" value="Сохранить">
      </form>
    </div>

    <div id="favorites" class="profile__content-block">
      <div class="fav__list">
        <?
while ($res = $favorites->fetch_assoc()) {
  echo "<div class='fav__item'><div class='item__block-fav'><div class='fav__img'><img src='{$res['image']}' alt='{$res['name']}' class='fav__img-obj'></div><div class='fav__block-name'><div class='fav__name-top'><div class='fav__date style_value-game'><img src='asset/download.svg' alt='Загрузки'><span class='value_game-stat'>{$res['downloads']}</span></div><div class='fav__views style_value-game'><img src='asset/views.svg' alt='Просмотры'><span class='value_game-stat'>{$res['views']}</span></div></div><div class='fav__name-bottom'><a data-games='?art={$res['game_id']}' href='?art={$res['game_id']}' class='fav__name'>{$res['name']}</a></div></div></div></div>";
}
?>
      </div>
    </div>
    <div id="recent" class="profile__content-block">
      <div class="fav__list">
        <?
while ($res = $views->fetch_assoc()) {
  echo "<div class='fav__item'><div class='item__block-fav'><div class='fav__img'><img src='{$res['image']}' alt='{$res['name']}' class='fav__img-obj'></div><div class='fav__block-name'><div class='fav__name-top'><div class='fav__date style_value-game'><img src='asset/download.svg' alt='Загрузки'><span class='value_game-stat'>{$res['downloads']}</span></div><div class='fav__views style_value-game'><img src='asset/views.svg' alt='Просмотры'><span class='value_game-stat'>{$res['views']}</span></div></div><div class='fav__name-bottom'><a data-games='?art={$res['game_id']}' href='?art={$res['game_id']}' class='fav__name'>{$res['name']}</a></div></div></div></div>";
}
?>
      </div>
    </div>
  </div>
</div>