<?
require_once "connection.php";
if (!empty($_GET['art'])) {
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
  $user = (int) $_SESSION['user_id'];
  $request = "SELECT MAX(IF(`favorites`.`user_id` = {$user}, 1,0)) AS 'favorit',`fulldescip`.*,`game`.`name`,`game`.`image`,`game`.`views`,`game`.`downloads`,COUNT(`favorites`.`user_id`),`avg_rating`.`avg`,`avg_rating`.`count` FROM `fulldescip`,`game` LEFT JOIN `favorites` ON `favorites`.`game_id` = `game`.`game_id` LEFT JOIN `avg_rating` ON `avg_rating`.`game_id` = `game`.`game_id` WHERE `game`.`game_id` = `fulldescip`.`game_id` AND `fulldescip`.`game_id` = {$art}";
  // echo $request;
  $res = $mysqli->query($request);
  echo json_encode($res->fetch_all(), JSON_UNESCAPED_UNICODE);
}
// <div class="block-art">
//   <div class="cont__block short__desc">
//     <div class="left__short-desc">
//       <img src="asset/1502223021__cover.png" alt="" class="game__image">
//     </div>
//     <div class="right__short-desc">
//       <div class="content-requires">
//         Год выпуска: 2014<br>Жанр: Adventure<br>Разработчик: Croteam<br>Издатель: Devolver Digital<br>Тип издания:
//         Лицензия <br>Язык интерфейса: RUS | ENG | Multi13<br>Язык озвучки: RUS | ENG | Multi12<br>Таблетка: Не требуется
//         (DRM-Free)<br>Системные требования: <br>ОС: Windows 7 / 10 (64-bit)<br>Процессор: Dual-core 2.0
//         GHz<br>Оперативная память: 2 GB<br>Видеокарта: 1 GB, совместимая с DirectX 11<br>Свободное место на жестком
//         диске: 13 GB<br> </div>
//     </div>
//   </div>
//   <div class="cont__block full-desc">
//     <div class="title_block">Описание:</div>
//     <div class="content-desc">
//       <b>The Talos Principle</b> сочетает в себе мир прошлого и мир будущего, сознание человека и сознание робота. Это
//       трехмерная головоломка с видом от 1-го лица, отличающаяся от множества других глубоким философским смыслом.
//       Главная героиня — робот, наделенный человеческим разумом. Причем разумом всегда мыслящим, задающимся
//       фундаментальными вопросами, ищущим на них ответы и ничего не принимающим на веру. <br><br>В поисках своей сущности
//       и своего пути роботу предстоит решить около 120 сложных головоломок, основанных на продвижении по лабиринтам и
//       преодолении с помощью лазерных лучей различных технологических препятствий вроде автоматизированных турелей и
//       летающих дронов. Нельзя не добавить, что разработчиком головоломки выступила студия Croteam, создавшая серию
//       Serious Sam. Это подтверждает высокое качество проекта, хотя речь и о совершенно другом
//       жанре.<br><br><b>Особенности The Talos Principle:</b><br>- интересное сочетание руин древнего мира с технологиями
//       будущего;<br>- философский сюжет, затрагивающий вопрос о смысле существования;<br>- около 120 захватывающих
//       разнообразных головоломок, основанных на преодолении препятствий;<br>- самая большая причина скачать The Talos
//       Principle через торрент как для нас это нелинейный сюжет, позволяющий лично определить жизненный путь
//       героини-робота.
//     </div>
//   </div>
//   <div class="cont__block screenshot-block">
//     <div class="title_block">Скриншоты:</div>
//     <div class="content-screenshot">
//       <img
//         src="https://s1.torrents-igruha.org/uploads/posts/2015-11/thumbs/1446647987_ss_3f16d21674b27dad893ccb27b581670b5bb8043a.1920x1080.jpg"
//         alt="" class="screenshot-item"><img
//         src="https://s1.torrents-igruha.org/uploads/posts/2015-11/thumbs/1446647987_ss_3f16d21674b27dad893ccb27b581670b5bb8043a.1920x1080.jpg"
//         alt="" class="screenshot-item"><img
//         src="https://s1.torrents-igruha.org/uploads/posts/2015-11/thumbs/1446647987_ss_3f16d21674b27dad893ccb27b581670b5bb8043a.1920x1080.jpg"
//         alt="" class="screenshot-item"><img
//         src="https://s1.torrents-igruha.org/uploads/posts/2015-11/thumbs/1446647987_ss_3f16d21674b27dad893ccb27b581670b5bb8043a.1920x1080.jpg"
//         alt="" class="screenshot-item"><img
//         src="https://s1.torrents-igruha.org/uploads/posts/2015-11/thumbs/1446647987_ss_3f16d21674b27dad893ccb27b581670b5bb8043a.1920x1080.jpg"
//         alt="" class="screenshot-item"><img
//         src="https://s1.torrents-igruha.org/uploads/posts/2015-11/thumbs/1446647987_ss_3f16d21674b27dad893ccb27b581670b5bb8043a.1920x1080.jpg"
//         alt="" class="screenshot-item">
//     </div>
//   </div>
//   <div class="cont__block video-block">
//     <div class="title_block">Видео:</div>
//     <div class="content-video">
//       <div id="Vu9QFBWb7WQ" class="youtube__block"
//         style="background-image: url(https://i.ytimg.com/vi/Vu9QFBWb7WQ/mqdefault.jpg);">
//         <div class="filter"></div>
//         <div class="yo_icon"><img class="play_icon" src="asset/play__icons.svg"></div>
//       </div>
//       <div id="Vu9QFBWb7WQ" class="youtube__block"
//         style="background-image: url(https://i.ytimg.com/vi/Vu9QFBWb7WQ/mqdefault.jpg);">
//         <div class="filter"></div>
//         <div class="yo_icon"><img class="play_icon" src="asset/play__icons.svg"></div>
//       </div>
//     </div>
//   </div>
//   <div class="cont__block file-block">
//     <div class="file-elem">
//       <div class="file_size">Размер: 9.9 GB v1.01 | GOG</div>
//       <span class="file-in"><a href="#" class="file">Скачать</a></span>
//     </div>
//     <div class="file-elem">
//       <div class="file_size">Размер: 9.9 GB v1.01 | GOG</div>
//       <span class="file-in"><a href="#" class="file">Скачать</a></span>
//     </div>
//     <div class="file-elem">
//       <div class="file_size">Размер: 9.9 GB v1.01 | GOG</div>
//       <span class="file-in"><a href="#" class="file">Скачать</a></span>
//     </div>
//     <div class="file-elem">
//       <div class="file_size">Размер: 9.9 GB v1.01 | GOG</div>
//       <span class="file-in"><a href="#" class="file">Скачать</a></span>
//     </div>
//   </div>

// </div>
