<div class="right_content">
  <div class="cont_cent">
    <div class="bl_bot-down">
      <div class="title_categorie">
        <div class="cat_name">
          <h3 id="rename_page-js"></h3>
        </div>
        <div class="switch_btn">
          <span class="line_view">
            <svg class="anim_view" onclick="lineView()" version="1.1" xmlns="http://www.w3.org/2000/svg" width="1.1vw"
              height="1vw" viewBox="0 0 32 32">
              <use xlink:href="#line_view"></use>
            </svg>
          </span>
          <span class="table_view"><svg class="anim_view" onclick="tableView()" version="1.1"
              xmlns="http://www.w3.org/2000/svg" width="1.1vw" height="1vw" viewBox="0 0 34 34">
              <use xlink:href="#table_view"></use>
            </svg>
          </span>
        </div>
      </div>
      <div id="search">
        <form id="search_grid">
          <select class="search_item select-css first_line_item" name="sort" id="search-sort" size="1">
            <?
$sort = [
  "name" => "По имени",
  "date" => "По дате",
  "rati" => "По рейтингу",
  "popu" => "По просмотрам",
];
foreach ($sort as $key => $value) {
  if ($_GET["sort"] == $key) {
    echo "<option selected value='{$key}'>{$value}</option>";
    continue;
  }
  echo "<option value='{$key}'>{$value}</option>";
}
?>
          </select>
          <select class="search_item select-css first_line_item" name="cat" id="search-cat" size="1">
            <option class="opt-cat" value>Категория</option>
            <?
foreach ($cat as $name) {
  if ($_GET["cat"] == $name[0]) {
    echo '<option selected class="opt-cat" value="' . $name[0] . '">' . $name[1] . '</option>';
    continue;
  }
  echo '<option class="opt-cat" value="' . $name[0] . '">' . $name[1] . '</option>';
}
?>
          </select>
          <select class="search_item select-css first_line_item" name="lang" id="search-language">
            <option value>Язык Озвучки</option>
            <?
$lang = [
  "1" => "Русский",
  "2" => "Английский",
  "3" => "Другое",
];
foreach ($lang as $key => $value) {
  if ($_GET["lang"] == $key) {
    echo "<option selected value='{$key}'>{$value}</option>";
    continue;
  }
  echo "<option value='{$key}'>{$value}</option>";
}
?>

          </select>

          <div class="block-new_slider">
            <div id="slider_year" class="pub_year"></div>
          </div>

          <div class="btn_search-right"><input class="btn_search" type="submit" value="Поиск" /></div>
        </form>
      </div>

      <div id="content-js" class="block_content">
      </div>
    </div>
    <div id="page-js" class="page">
    </div>
  </div>

</div>