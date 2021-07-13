"use strict";
// Прокрутка шапки страницы
var cbpAnimatedHeader = (function () {
  var docElem = document.documentElement,
    header = document.querySelector(".inner_header"),
    didScroll = false,
    changeHeaderOn = 100;

  function scrollPage() {
    var sy = scrollY();
    if (sy >= changeHeaderOn) {
      header.classList.add("header-shrink");
    } else {
      header.classList.remove("header-shrink");
    }
    didScroll = false;
  }

  function init() {
    window.addEventListener(
      "scroll",
      function (event) {
        if (!didScroll) {
          didScroll = true;
          setTimeout(scrollPage, 100);
        }
      },
      false
    );
  }

  function scrollY() {
    return window.pageYOffset || docElem.scrollTop;
  }

  init();
})();

var slider = document.getElementById("slider_year");

noUiSlider.create(slider, {
  start: [2000, 2021],
  connect: true,
  range: {
    min: 2000,
    max: 2021,
  },
  step: 1,
  pips: {
    mode: "steps",
    stepped: true,
    density: 4,
  },
});

var block = document.querySelector(".block_content");
function lineView() {
  if (block.classList.contains("table_content")) {
    block.classList.remove("table_content");
  }
}
function tableView() {
  block.classList.add("table_content");
}
// * Table view
/*
.content_item {
  width: 33.3333%;
}
.info_item {
  display: none;
}
.img_item {
  height: initial;
  width: 95%;
}
.content_item {
  border-left: none;
  border-bottom: 5px solid transparent;
}
.name_game {
  font-size: 17px;
}
*/

var categorie = [
  "Топ игры",
  "Экшен игры",
  "Шутеры",
  "Стратегии",
  "Аркады",
  "Платфомер",
  "RPG игры",
  "Гонки",
  "Приключения",
  "Инди игры",
  "Хоррор игры",
  "Симуляторы",
  "Спортивные",
  "На выживание",
  "Логические",
  "Головоломки",
  "Квесты",
  "Поиск предметов",
  "Драки",
  "Игры про зомби",
  "Песочницы",
  "Онлайн игры",
  "Стелс игры",
  "Дополнения к играм",
  "Ожидаемые игры",
];
var link;
var cat_name = document.getElementById("js_cat");
var cat_search = document.getElementById("search-cat");
for (var i = 0; i < categorie.length; i++) {
  link = "" + categorie[i] + "";
  cat_name.insertAdjacentHTML("beforeend", link);
  link =
    '<option class="opt-cat" value="' +
    categorie[i] +
    '">' +
    categorie[i] +
    "</option>";
  cat_search.insertAdjacentHTML("beforeend", link);
}
for (
  var link,
    categorie = [
      "Топ игры",
      "Экшен игры",
      "Шутеры",
      "Стратегии",
      "Аркады",
      "Платфомер",
      "RPG игры",
      "Гонки",
      "Приключения",
      "Инди игры",
      "Хоррор игры",
      "Симуляторы",
      "Спортивные",
      "На выживание",
      "Логические",
      "Головоломки",
      "Квесты",
      "Поиск предметов",
      "Драки",
      "Игры про зомби",
      "Песочницы",
      "Онлайн игры",
      "Стелс игры",
      "Дополнения к играм",
      "Ожидаемые игры",
    ],
    cat_name = document.getElementById("js_cat"),
    cat_search = document.getElementById("search-cat"),
    i = 0;
  i < categorie.length;
  i++
)
  (link =
    '<li class="cat_list"><a class="cat_link" href="#">' +
    categorie[i] +
    "</a></li>"),
    cat_name.insertAdjacentHTML("beforeend", link),
    (link =
      '<option class="opt-cat" value="' +
      categorie[i] +
      '">' +
      categorie[i] +
      "</option>"),
    cat_search.insertAdjacentHTML("beforeend", link);
//   <li class="cat_list">
//   <a class="cat_link" href="#"></a>
// </li>
var params = new URL(document.location).searchParams;
console.log(params.get("search-categorie"));

document.getElementById("elements-placeholder").innerHTML = SVG_SPRITE;
