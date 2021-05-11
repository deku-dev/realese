var favIcon =
  " fav_set-pict' onclick='favoritSet(this)' version='1.1' id='favorit-svg' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 412.735 412.735' style='enable-background:new 0 0 412.735 412.735;' xml:space='preserve'><path id='favorit-path' class='review_path' d='M295.706,46.058C354.45,46.344,402,93.894,402.286,152.638 c0,107.624-195.918,214.204-195.918,214.204S10.449,258.695,10.449,152.638c0-58.862,47.717-106.58,106.58-106.58l0,0 c36.032-0.281,69.718,17.842,89.339,48.065C226.123,64.047,259.722,45.971,295.706,46.058z'></path><path id='svg-like' class='favoth__path' d='M206.367,377.291c-1.854-0.024-3.664-0.567-5.224-1.567C193.306,371.544,0,263.397,0,152.638 C0,88.005,52.395,35.609,117.029,35.609l0,0c34.477-0.406,67.299,14.757,89.339,41.273 c41.749-49.341,115.591-55.495,164.932-13.746c26.323,22.273,41.484,55.02,41.436,89.501 c0,112.327-193.306,218.906-201.143,223.086C210.031,376.723,208.221,377.266,206.367,377.291z M117.029,56.507 c-53.091,0-96.131,43.039-96.131,96.131l0,0c0,89.861,155.167,184.424,185.469,202.188 c30.302-17.241,185.469-111.282,185.469-202.188c0.087-53.091-42.881-96.201-95.972-96.289 c-32.501-0.053-62.829,16.319-80.615,43.521c-3.557,4.905-10.418,5.998-15.323,2.44c-0.937-0.68-1.761-1.503-2.44-2.44 C179.967,72.479,149.541,56.08,117.029,56.507z'></path></svg>";
!(function (e, t) {
  "object" == typeof exports && "undefined" != typeof module
    ? (module.exports = t())
    : "function" == typeof define && define.amd
    ? define(t)
    : ((e = e || self),
      (function () {
        var n = e.Cookies,
          r = (e.Cookies = t());
        r.noConflict = function () {
          return (e.Cookies = n), r;
        };
      })());
})(this, function () {
  "use strict";
  function e(e) {
    for (var t = 1; t < arguments.length; t++) {
      var n = arguments[t];
      for (var r in n) e[r] = n[r];
    }
    return e;
  }
  var t = {
    read: function (e) {
      return e.replace(/(%[\dA-F]{2})+/gi, decodeURIComponent);
    },
    write: function (e) {
      return encodeURIComponent(e).replace(
        /%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,
        decodeURIComponent
      );
    },
  };
  return (function n(r, o) {
    function i(t, n, i) {
      if ("undefined" != typeof document) {
        "number" == typeof (i = e({}, o, i)).expires &&
          (i.expires = new Date(Date.now() + 864e5 * i.expires)),
          i.expires && (i.expires = i.expires.toUTCString()),
          (t = encodeURIComponent(t)
            .replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent)
            .replace(/[()]/g, escape)),
          (n = r.write(n, t));
        var c = "";
        for (var u in i)
          i[u] &&
            ((c += "; " + u), !0 !== i[u] && (c += "=" + i[u].split(";")[0]));
        return (document.cookie = t + "=" + n + c);
      }
    }
    return Object.create(
      {
        set: i,
        get: function (e) {
          if ("undefined" != typeof document && (!arguments.length || e)) {
            for (
              var n = document.cookie ? document.cookie.split("; ") : [],
                o = {},
                i = 0;
              i < n.length;
              i++
            ) {
              var c = n[i].split("="),
                u = c.slice(1).join("=");
              '"' === u[0] && (u = u.slice(1, -1));
              try {
                var f = t.read(c[0]);
                if (((o[f] = r.read(u, f)), e === f)) break;
              } catch (e) {}
            }
            return e ? o[e] : o;
          }
        },
        remove: function (t, n) {
          i(t, "", e({}, n, { expires: -1 }));
        },
        withAttributes: function (t) {
          return n(this.converter, e({}, this.attributes, t));
        },
        withConverter: function (t) {
          return n(e({}, this.converter, t), this.attributes);
        },
      },
      {
        attributes: { value: Object.freeze(o) },
        converter: { value: Object.freeze(r) },
      }
    );
  })(t, { path: "/" });
});
function fetchHandler(res) {
  if (res.ok) {
    return Promise.resolve(res);
  } else {
    return Promise.reject(new Error(res.status));
  }
}
function json(response) {
  return response.json();
}
function text(response) {
  return response.text();
}
function parseURL(url) {
  return new URLSearchParams(url);
}
function generErr(code, message) {
  let frag = document.createDocumentFragment(),
    bl = create("div", "error_cont"),
    top = create("div", "top-code__error"),
    codebl = create("div", "code__error"),
    text = create("div", "text__error");
  codebl.innerText = code;
  text.innerHTML = message;
  top.append(codebl, text);
  bl.append(top);
  frag.append(bl);
  return frag;
}
function loadNew(params) {
  let contBlock = document.getElementById("content-js");
  if (sessionStorage.getItem(params) !== null) {
    contBlock.innerHTML = "";
    generHand(sessionStorage.getItem(params), params, contBlock);
    pagesReload();
    return;
  }
  fetch("lgame.php" + params)
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      contBlock.innerHTML = "";
      if (sessionStorage.getItem(params) === null) {
        sessionStorage.setItem(params, res);
      }
      generHand(res, params, contBlock);
      pagesReload();
    })
    .catch((error) => {
      let errArr = {
        404: "Хмм...Похоже здесь ничего нет",
        503: "Сервер временно недоступен",
      };
      if (errArr[error.message]) {
        contBlock.innerHTML = "";
        contBlock.append(generErr(error.message, errArr[error.message]));
      }
      noticeAll("Код ошибки: " + error.message, 1);
      console.error(error);
    });
}
function generHand(res, par, bl) {
  let url = parseURL(par);
  let json = JSON.parse(res);
  // let json = res;
  if (url.has("ct")) {
    bl.append(generCont(json));
  } else if (url.has("art")) {
    bl.append(generArt(json));
    baguetteBox.run(".content-screenshot");
    loadRev();
    loadComment(0);
  } else if (url.has("do")) {
    if (url.get("do") == "profile") {
      bl.append(generProfile(json));
      loadUlist("favorites", 0);
      loadUlist("views", 0);
      loadUlist("download", 0);
    } else if (url.get("do") == "feedback") {
      bl.append(generFeedback(json));
    }
  } else {
    bl.append(generCont(json));
  }
}
function create(elem, classn = "") {
  let block = document.createElement(elem);
  block.className = classn;
  return block;
}
function rebCateg() {
  let url = parseURL(location.search);
  if ((selDel = document.querySelector(".select_cat")))
    selDel.classList.remove("select_cat");
  if (
    url.has("ct") &&
    (curUrl = document.querySelector("[data-url='ct=" + url.get("ct") + "']"))
  ) {
    let listCat = curUrl.parentNode;
    listCat.classList.add("select_cat");
  }
}
function generCont(json) {
  let frag = document.createDocumentFragment(),
    content = create("div", "content_item");
  for (const key in json) {
    let cloneBl = content.cloneNode(),
      imgBlock = create("div", "img_block"),
      info = create("div", "info_item"),
      bloItem = create("div", "block_item"),
      genre = create("div", "genre_item"),
      views = create("div", "views"),
      imgItem = create("img", "img_item"),
      down = create("div", "down_link"),
      nameGame = create("div", "name_game"),
      desc = create("div", "description"),
      filSpan = create("span", "fil_span"),
      favSet = create("div", "fav_set-block"),
      tempFav = json[key][10] == 1 ? "" : "dis";
    favSet.innerHTML = "<svg class='" + tempFav + "like" + favIcon;
    imgItem.src = json[key][5];
    imgItem.alt = json[key][1];
    imgItem.onerror = function () {
      this.src = "asset/err_image.svg";
    };
    nameGame.innerText = json[key][1];
    filSpan.setAttribute("data-url", "?art=" + json[key][0]);
    imgBlock.append(filSpan, favSet, imgItem, nameGame);
    genre.innerHTML = "Жанр: " + json[key][9];
    views.innerHTML =
      "<svg height='1.2vw' version='1.1' viewBox='0 -25 150 122' width='1.5vw' xmlns='http://www.w3.org/2000/svg'><use xlink:href='#views'></use></svg><span class='view_value'>" +
      json[key][4] +
      "</span>";
    bloItem.append(genre, views);
    desc.innerHTML = json[key][8];
    desc.innerText = desc.innerText;
    down.innerHTML =
      "<a href='?art=" +
      json[key][0] +
      "' class='btn_down'>Скачать</a><div class='item_value'><span class='file_size'></span><img class='down_icon' src='asset/download.svg'><span class='rating'>" +
      json[key][6] +
      "</span></div>";
    info.append(bloItem, desc, down);
    cloneBl.append(imgBlock, info);
    frag.append(cloneBl);
  }
  return frag;
}
function generProfile(json) {
  console.log(json);
  window.pageTitles = json[0];
  let frag = document.createDocumentFragment();
  let profile = create("div", "profile__page");
  let top = create("div", "top_profile-page");
  let left = create("div", "top_user-left");
  let lblock = create("div", "top_left-block");
  let img = create("img", "top_left-picture");
  let loadImg = create("label", "update__block");
  let upload = create("img", "upload-up");
  let spanUp = create("span", "span-upload");
  let upInput = create("input", "hid-inp__upload");
  let subAvat = create("div", "submit-avatar");
  subAvat.addEventListener("click", updateAvatar);
  subAvat.innerText = "Сохранить";
  subAvat.style.display = "none";
  upload.src = "asset/upload.svg";
  spanUp.innerText = "Загрузить";
  upInput.addEventListener("change", handleFileSelect);
  upInput.id = "img-upload__input";
  upInput.type = "file";
  upInput.name = "avatar";
  loadImg.for = "img-upload__input";
  loadImg.append(upload, spanUp, upInput);
  console.log(img.src);
  img.onerror = () => {
    img.src = "asset/user.svg";
  };
  img.id = "avatar-user";
  img.src = json[7];
  img.alt = json[0];
  lblock.append(img, loadImg);
  left.append(lblock, subAvat);
  let right = create("div", "top_user-right");
  let rblock = create("div", "top_right-block");
  let table = create("table", "user_stat-table");
  let arrList = [
    "Никнейм",
    "На сайте",
    "Избранное",
    "Просмотренное",
    "Оценки",
    "Загрузки",
    "О себе",
  ];
  for (const key in arrList) {
    if (Object.hasOwnProperty.call(arrList, key)) {
      const element = arrList[key];
      let tr = create("tr", "row-stat");
      tr.innerHTML =
        "<td class='name-stat'>" +
        element +
        ": </td><td class='value-stat'>" +
        json[key] +
        "</td>";
      console.log(tr.innerHTML);
      table.append(tr);
    }
  }
  let setProf = create("img", "set_profile");
  setProf.src = "asset/set_profile.svg";
  setProf.onclick = () => {
    showReview("setting-profile");
  };
  let setting = create("div", "setting-profile hidden-rev");
  setting.addEventListener("click", hideRev);
  let setForm = create("form");
  setForm.addEventListener("submit", updateProf);
  setForm.id = "setting-form";
  setForm.innerHTML =
    "<input oninput='nickCheck(this)' autocomplete='username' placeholder='Никнейм' type='text' name='nick' class='setting-input'><textarea placeholder='О себе' name='about' cols='30' rows='10' class='setting-input'></textarea><input autocomplete='current-password' type='password' placeholder='Текущий пароль' name='lastpass' class='setting-input' id='cur-pass'><input autocomplete='new-password' type='password' placeholder='Новый пароль' id='new-pass' name='pass' class='setting-input'><label class='check-lab'><input onclick=\"showPass(this, 'cur-pass')\" name='vsp' type='checkbox' class='pass-check off'>Показать пароль</label><input type='submit' value='Сохранить' class='setting-input'>";
  setting.append(setForm);
  rblock.append(table, setProf, setting);
  right.append(rblock);
  top.append(left, right);
  let bottom = create("div", "bottom_profile-page");
  let title = create("div", "bott_profile-title");
  title.innerHTML =
    "<div data-target='content-favorites' class='title_profile active-title'>Избранное</div><div data-target='content-download' class='title_profile'>Загрузки</div><div data-target='content-views' class='title_profile'>История</div>";
  title.addEventListener("click", handUser);
  let content = create("div", "bott_profile-content");
  let favorites = create("div", "bott_content-item act_user-menu");
  favorites.id = "content-favorites";
  let views = create("div", "bott_content-item");
  views.id = "content-views";
  let download = create("div", "bott_content-item");
  download.id = "content-download";
  content.append(favorites, views, download);
  bottom.append(title, content);
  profile.append(top, bottom);
  frag.append(profile);
  return frag;
}
function generArt(json) {
  // Generate Game page (game stat, specify, description, screenshot, video, torrent, reviews)
  window.json = json;
  let frag = document.createDocumentFragment(),
    content = create("div", "block-art"),
    left = create("div", "left__short-desc"),
    img = create("img", "game__image"),
    right = create("div", "right__short-desc"),
    requires = create("div", "content-requires"),
    shortDesc = create("div", "cont__block short__desc"),
    statArt = create("div", "cont__block stat-art"),
    full = create("div", "cont__block full-desc"),
    screenshots = create("div", "cont__block screenshot-block"),
    video = create("div", "cont__block video_block"),
    comment = create("div", "cont__block comment-block"),
    yourReview = create("div", "cont__block your-review"),
    file = create("div", "cont__block file-block"),
    fullTitle = create("div", "title_block"),
    description = create("div", "content-desc"),
    screenTitle = create("div", "title_block"),
    contScreen = create("div", "content-screenshot"),
    vidTitle = create("div", "title_block"),
    contVid = create("div", "content-video"),
    fav = create("div", "fav_set-block"),
    commTit = create("div", "title_block"),
    commOther = create("div", "comm-pages"),
    reviTit = create("div", "title_block"),
    connRevi = create("div"),
    leave = create("div", "leave-review hidden-rev"),
    yours = create("div"),
    write = create("div", "bl__write-review"),
    formRev = create("form"),
    blComm = create("div"),
    thisjson = json[0],
    tempfav = thisjson[0] == 1 ? "" : "dis",
    media = thisjson[4] ? JSON.parse(thisjson[4]) : "",
    specify = thisjson[3] ? JSON.parse(thisjson[3]) : "",
    torrent = thisjson[5] ? JSON.parse(thisjson[5]) : "";
  img.src = thisjson[7];
  img.alt = thisjson[6];
  leave.addEventListener("click", hideRev);
  rnamePage(thisjson[6]);
  statArt.innerHTML =
    "<div class='stat-item'><img src='asset/views.svg' alt='Просмотров' class='stat-img'><span class='stat__views stat__vidofa'>" +
    thisjson[8] +
    "</span></div><div class='stat-item middle-stat'><img src='asset/download.svg' alt='Загрузок' class='stat-img'><span class='stat__downloads stat__vidofa'>" +
    thisjson[9] +
    "</span></div><div class='stat-item middle-stat'><img src='asset/dhearth.svg' alt='Избранное' class='stat-img'><span class='stat__favorites stat__vidofa'>" +
    Number(thisjson[10]) +
    "</span></div><div class='stat-item'><img src='asset/rating.svg' alt='Оценка' class='stat-img'><span class='stat__favorites stat__vidofa'>" +
    Number(thisjson[11]) +
    "(" +
    Number(thisjson[12]) +
    ")</span></div>";
  blComm.id = "content-comment";
  commTit.innerText = "Отзывы:";
  fullTitle.innerText = "Описание:";
  let loadAg = create("span");
  loadAg.setAttribute("data-pages", 1);
  loadAg.id = "comm-load";
  loadAg.innerText = "Показать еще";
  loadAg.addEventListener("click", commLister);
  description.innerHTML = thisjson[2];
  formRev.addEventListener("submit", sendReview);
  formRev.id = "review-form";
  formRev.innerHTML =
    "<fieldset class='rating'><div class='rating__group'><input class='rating__input' id='general-1' type='radio' name='rating' value='1'><label class='rating__star' for='general-1' aria-label='Ужасно'></label><input class='rating__input' id='general-2' type='radio' name='rating' value='2'><label class='rating__star' for='general-2' aria-label='Сносно'></label><input class='rating__input' id='general-3' type='radio' name='rating' value='3'><label class='rating__star' for='general-3' aria-label='Нормально'></label><input class='rating__input' id='general-4' type='radio' name='rating' value='4'><label class='rating__star' for='general-4' aria-label='Хорошо'></label><input class='rating__input' id='general-5' type='radio' name='rating' value='5'><label class='rating__star' for='general-5' aria-label='Отлично'></label><div class='rating__focus'></div></div></fieldset><textarea placeholder='Текст отзыва' class='text-review' name='textrev' rows='7'></textarea><div class='send__block-review'><input type='submit' value='Оставить отзыв' class='send-review'></div>";
  leave.append(formRev);
  connRevi.id = "content-review";
  yours.id = "yours-review";
  reviTit.innerText = "Ваш отзыв";
  let writeSpan = create("span");
  writeSpan.id = "write-review";
  writeSpan.onclick = () => {
    showReview("leave-review");
  };
  writeSpan.innerText = "Написать отзыв";
  write.append(writeSpan);
  connRevi.append(leave, write, yours);
  yourReview.append(reviTit, connRevi);

  screenTitle.innerText = "Скриншоты:";
  console.log(thisjson);
  fav.innerHTML =
    "<svg data-url='?art=" +
    thisjson[1] +
    "' class='" +
    tempfav +
    "like" +
    favIcon;
  vidTitle.innerText = "Видео:";
  for (const key in specify) {
    if (Object.hasOwnProperty.call(specify, key)) {
      const element = specify[key];
      let nameChara = create("span", "requires_name");
      let valueChara = create("span", "requires_value");
      let br = create("br");
      nameChara.innerHTML = key + ": ";
      valueChara.innerHTML = element;
      requires.append(nameChara, valueChara, br);
    }
  }

  for (const key in media.screenshot) {
    if (Object.hasOwnProperty.call(media.screenshot, key)) {
      const element = media.screenshot[key];
      let screenItem = create("img", "screenshot-item");
      let link = create("a");
      console.log(element);
      link.href = element.replace("thumbs/", "");
      link.setAttribute("data-caption", thisjson[6]);
      screenItem.src = element;
      screenItem.alt = key + " Скриншот";
      link.append(screenItem);
      contScreen.append(link);
    }
  }
  for (const key in media.video) {
    if (Object.hasOwnProperty.call(media.video, key)) {
      const element = media.video[key];
      let youtBlock = create("div", "youtube__block");
      youtBlock.id = element;
      youtBlock.style.backgroundImage =
        "url(https://i.ytimg.com/vi/" + element + "/mqdefault.jpg)";
      youtBlock.innerHTML =
        "<div class='filter'></div><div class='yo_icon'><img onclick='loadYoutube(this)' class='play_icon' src='asset/play__icons.svg'></div>";
      contVid.append(youtBlock);
    }
  }
  for (const key in torrent) {
    if (Object.hasOwnProperty.call(torrent, key)) {
      const element = torrent[key];
      let fileElem = create("div", "file-elem");
      let size = create("div", "file_size");
      size.innerText = element;
      let fileUrl = create("span", "file bl_file");
      fileUrl.setAttribute("data-fileg", key);
      fileUrl.addEventListener("click", downloadEv);
      fileUrl.innerText = "Скачать";
      fileElem.append(size, fileUrl);
      file.append(fileElem);
    }
  }
  sessionStorage.removeItem("image");
  sessionStorage.removeItem("title");
  left.append(img, fav);
  right.append(requires);
  shortDesc.append(left, right);
  commOther.append(loadAg);
  full.append(fullTitle, description);
  screenshots.append(screenTitle, contScreen);
  video.append(vidTitle, contVid);
  comment.append(commTit, blComm, commOther);
  content.append(
    shortDesc,
    statArt,
    full,
    screenshots,
    video,
    file,
    yourReview,
    comment
  );
  frag.append(content);
  return frag;
}
function generComm(json) {
  // Review generate page
  let frag = document.createDocumentFragment();
  for (const key in json) {
    if (Object.hasOwnProperty.call(json, key)) {
      const element = json[key];
      let item = create("div", "comm-item");
      let left = create("div", "comm-left");
      let right = create("div", "comm-right");
      let avatar = create("img", "comm-image");
      item.setAttribute("data-revid", element[6]);
      avatar.src = element[4];
      avatar.onerror = () => {
        avatar.src = "asset/user.svg";
      };
      left.append(avatar);
      let user = create("div", "comm-username");
      user.innerText = element[5];
      let data = create("div", "comm-data");
      data.innerHTML =
        "<span class='comm-value'>" +
        element[0] +
        " ★</span><span class='comm-date'>" +
        element[3] +
        "</span>";
      let text = create("div", "comm-text");
      text.innerText = element[1];
      let like = create("span", "comm-like");
      like.addEventListener("click", reviewLike);
      let liked = Number(element[7]) ? " rev_like'" : "'";
      like.innerHTML =
        "<img review-id='" +
        element[6] +
        "' class='comm__like-img" +
        liked +
        " src='asset/like.svg' alt='Полезный'/><span class='comm__like-num'>" +
        element[2] +
        "</span>";
      right.append(user, data, text, like);
      item.append(left, right);
      frag.append(item);
    }
  }
  return frag;
}
// download review by pages
function loadComment(page) {
  fetch("comment.php?page=" + page)
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      let parseRes = JSON.parse(res);
      let pages = document.getElementById("comm-load");
      let content = document.getElementById("content-comment");
      console.log(parseRes);
      window.parseRes = parseRes;
      if (parseRes.length < 20 || !(0 in parseRes)) {
        pages.style.display = "none";
      } else {
        pages.setAttribute("data-pages", Number(page) + 1);
      }
      if (page == "user") {
        content.prepend(generComm(parseRes));
      } else {
        content.append(generComm(parseRes));
      }
    })
    .catch((err) => {
      console.log(err);
    });
}
function commLister(e) {
  let page = this.getAttribute("data-pages");
  loadComment(page, this);
}
function pagesReload() {
  let urlParsed = parseURL(location.search);
  let pageStr = "",
    pages = false;
  if (urlParsed.has("page")) {
    pageStr = " | Страница " + urlParsed.get("page");
    pages = urlParsed.get("page");
    urlParsed.delete("page");
  }
  if (urlParsed.has("ct")) {
    let title = document.querySelector(
      "[data-url='ct=" + urlParsed.get("ct") + "']"
    ).innerText;
    rnamePage(title + pageStr);
    document.getElementById("search").hidden = false;
  } else if (urlParsed.has("do")) {
    rnamePage(window.pageTitles);
    document.getElementById("search").hidden = true;
  } else if (urlParsed.toString() == "") {
    document.getElementById("search").hidden = false;
    rnamePage("Все игры" + pageStr);
  }
  let curPage = pages ? pages : 1;
  if (urlParsed.has("ct")) rebCateg();
  let blockPage = document.getElementById("page-js");
  blockPage.innerHTML = "";
  fetch("countpage.php" + location.search)
    .then(fetchHandler)
    .then(text)
    .then((resJson) => {
      resJson = JSON.parse(resJson);
      console.log(resJson);
      if (urlParsed.has("sort") || urlParsed.has("search")) {
        rnamePage("Результатов: " + resJson.all + pageStr);
      }
      if (resJson.count <= 1) return;
      let allPage = resJson.count;
      let frag = document.createDocumentFragment();
      let link = create("a");
      link.className = "page_num";
      let first = curPage >= 3 ? curPage - 2 : 1;
      let second = allPage - curPage >= 2 ? Number(curPage) + 2 : allPage;
      let newlink = link.cloneNode();
      urlParsed.set("page", 1);
      newlink.href = "?" + urlParsed.toString();
      newlink.setAttribute("data-url", "?" + urlParsed.toString());
      newlink.addEventListener("click", pageLoad);
      newlink.innerText = 1;
      for (let i = 1; i <= allPage; i++) {
        let newlink = link.cloneNode();
        urlParsed.set("page", i);
        newlink.href = "?" + urlParsed.toString();
        newlink.setAttribute("data-url", "?" + urlParsed.toString());
        newlink.addEventListener("click", pageLoad);
        newlink.innerText = i;
        if (1 < i && i < first) {
          let space = create("span");
          space.innerText = "...";
          frag.append(space);
          i = first - 1;
          continue;
        }
        if (second < i && i < allPage) {
          let space = create("span");
          space.innerText = "...";
          frag.append(space);
          i = allPage - 1;
          continue;
        }
        if (i == curPage) {
          let curP = create("span");
          curP.innerText = i;
          curP.className = "sel_page";
          frag.append(curP);
          continue;
        }
        frag.append(newlink);
      }

      blockPage.appendChild(frag);
    })
    .catch((error) => {
      noticeAll("Код ошибки:" + error, 1);
      console.error(error);
    });
}
function generCompl(json) {
  let auto = document.getElementById("autocomplete");
  auto.innerHTML = "";
  let frag = document.createDocumentFragment();
  for (const key in json) {
    if (Object.hasOwnProperty.call(json, key)) {
      const el = json[key];
      let item = create("div", "auto-item");
      let fil = create("span", "fil_span");
      fil.setAttribute("data-url", "?art=" + el[0]);
      fil.addEventListener("click", loadGame);
      let left = create("div", "auto-left");
      let img = create("img", "auto-img");
      img.src = el[2];
      left.append(img);
      let right = create("div", "auto-right");
      let name = create("span", "auto-name");
      name.innerText = el[1];
      right.append(name);
      item.append(fil, left, right);
      frag.append(item);
    }
  }
  auto.append(frag);
}
function generList(json) {
  let frag = document.createDocumentFragment();
  for (const key in json) {
    if (Object.hasOwnProperty.call(json, key)) {
      const element = json[key];
      let item = create("div", "item_profile-list");
      let fil = create("span", "fil_span");
      fil.setAttribute("data-url", "?art=" + element[2]);
      let pict = create("div", "top-pict_item");
      let img = create("img", "pict-item");
      img.src = element[0];
      img.alt = element[1];
      pict.append(img);
      let nameItem = create("div", "down-name_item");
      let game = create("span", "game-item");
      game.innerText = element[1];
      nameItem.append(game);
      item.append(fil, pict, nameItem);
      frag.append(item);
    }
  }
  return frag;
}
function generFeedback(json) {
  window.pageTitles = "Обратная Связь";
  let frag = document.createDocumentFragment();
  let content = create("div", "block-feed");
  let formFeed = create("form");
  formFeed.id = "feedback-form";
  formFeed.addEventListener("submit", sendFeed);
  formFeed.innerHTML =
    "<input placeholder='Имя' minlength='2' maxlength='100' type='text' name='name' class='input-feed' required><input minlength='6' maxlength='150' placeholder='Email' type='mail' name='email' class='input-feed' required><input maxlength='150' autocomplete='off' placeholder='Тема сообщения' maxlength='150' type='text' name='theme' class='input-feed' required><textarea name='text' minlength='6' maxlength='200' class='input-feed' cols='30' rows='10' required placeholder='Текст сообщения'></textarea><div class='block__submit'><input class='submit-feed' type='submit' value='Отправить'></div>";
  content.append(formFeed);
  frag.append(content);
  return frag;
}
function sendFeed(e) {
  e.preventDefault();
  let form = new FormData(document.getElementById("feedback-form"));
  fetch("feedback.php", {
    method: "post",
    body: form,
  })
    .then(fetchHandler)
    .then(text)
    .then(() => {
      noticeAll("Сообщение отправлено");
    })
    .catch((err) => {
      noticeAll("Код ошибки:" + error.message, 1);
    });
}
function noticeAll(text, type = 0) {
  let arrNotice = [
      ["asset/info.svg", "Новое уведомление", ""],
      ["asset/error.svg", "Произошла ошибка", "error"],
    ],
    frag = document.createDocumentFragment(),
    hr = create("hr"),
    block = create("div"),
    stat = create("div"),
    textStat = create("div"),
    img = create("img"),
    textElem = create("div");
  block.className = "block__notice temp " + arrNotice[type][2];
  stat.className = "status_notice";
  textStat.className = "text__status-notice";
  textStat.innerText = arrNotice[type][1];
  img.className = "image__status-notice";
  img.alt = arrNotice[type][1];
  img.src = arrNotice[type][0];
  stat.append(img, textStat);
  hr.className = "hr";
  textElem.className = "text_notice";
  textElem.innerHTML = text;
  block.append(stat, hr, textElem);
  frag.append(block);
  document.getElementById("notice-all").appendChild(frag);
  let height = block.clientHeight + 6;
  block.style.height = 0;
  setTimeout(() => {
    block.classList.remove("temp");
    block.style.height = height + "px";
  }, 100);

  setTimeout(() => {
    block.classList.add("temp");
  }, 3500);
  setTimeout(() => {
    block.remove();
  }, 3900);
}
function rnamePage(name) {
  document.title = name;
  document.getElementById("rename_page-js").innerText = name;
}
function loadCat(e) {
  e.preventDefault();
  let tar = e.target;
  if (tar.classList.contains("cat_list")) tar = tar.firstChild;
  if (!tar.hasAttribute("data-url")) return;
  let state = {
    url: "?" + tar.getAttribute("data-url"),
    title: tar.innerText,
    xPage: window.pageYOffset || document.documentElement.scrollTop,
  };
  history.pushState(state, state.title, state.url);
  // rnamePage(tar.innerText);

  loadNew("?" + tar.getAttribute("data-url"));
}
function pageLoad(e) {
  e.preventDefault();
  if (this.classList.contains("page_num")) {
    let title =
      document.title.split("|")[0] + " | " + "Страница " + this.innerText;
    let state = {
      url: this.getAttribute("data-url"),
      title: title,
      xPage: window.pageYOffset || document.documentElement.scrollTop,
    };
    history.pushState(state, state.title, state.url);
    document.getElementById("rename_page-js").scrollIntoView();
    // rnamePage(title);
    loadNew(this.getAttribute("data-url"));
  }
}
function loadGame(e) {
  let tar = e.target;
  if (!tar.hasAttribute("data-url")) return;
  let title = tar.nextElementSibling.innerText;
  let state = {
    url: tar.getAttribute("data-url"),
    title: title,
    xPage: window.pageYOffset || document.documentElement.scrollTop,
  };
  history.pushState(state, state.title, state.url);
  document.getElementById("rename_page-js").scrollIntoView();
  loadNew(tar.getAttribute("data-url"));
}

function contentHand(e) {
  let tar = e.target;
  let url = parseURL(location.search);
  url.delete("page");
  if (tar.hasAttribute("data-url")) {
    tar.addEventListener("click", loadGame);
    tar.click();
  }
}
function formSearch(e) {
  e.preventDefault();
  let form = new FormData(this);
  let noUi = slideForm.noUiSlider.get();
  form.append("min", Number(noUi[0]));
  form.append("max", Number(noUi[1]));
  let params =
    "?sort=" +
    form.get("sort") +
    "&cat=" +
    form.get("cat") +
    "&lang=" +
    form.get("lang") +
    "&min=" +
    form.get("min") +
    "&max=" +
    form.get("max");
  let state = {
    url: params,
    title: "Результаты",
    xPage: window.pageYOffset || document.documentElement.scrollTop,
  };
  history.pushState(state, state.title, state.url);
  // rnamePage(state.title);
  loadNew(params);
}
function searchWord(e) {
  e.preventDefault();
  let params = "?search=" + document.getElementById("string").value;
  let state = {
    url: params,
    title: "Результаты",
    xPage: window.pageYOffset || document.documentElement.scrollTop,
  };
  history.pushState(state, state.title, state.url);
  // rnamePage(state.title);
  loadNew(params);
}
function autoComplete(e) {
  if (!this.value.length > 3) return;
  if (window.timer) {
    clearTimeout(timer);
  }
  window.timer = setTimeout(() => {
    fetch("autocomplete.php?search=" + this.value)
      .then(fetchHandler)
      .then(text)
      .then((res) => {
        res = JSON.parse(res);
        console.log(res);
        generCompl(res);
      })
      .catch((error) => {
        console.log(error);
        noticeAll("Код ошибки: " + error.message, 1);
      });
  }, 1000);
}
function focusAutoCompl(e) {
  let auto = document.getElementById("autocomplete");
  auto.style.visibility = "visible";
  auto.classList.add("auto-active");
  this.addEventListener(
    "blur",
    (e) =>
      setTimeout(() => {
        auto.classList.remove("auto-active");
        auto.addEventListener(
          "transitionend",
          () => (auto.style.visibility = "hidden"),
          { once: true }
        );
      }, 300),
    { once: true }
  );
}

function userMenu(e) {
  let tar = e.target;
  if (tar.classList.contains("tit-user_menu")) {
    document.querySelector(".active-tit").classList.remove("active-tit");
    tar.classList.add("active-tit");
    if (tar.id == "login__menu") {
      document.querySelector(".register").classList.remove("active-menu");
      document.querySelector(".login").classList.add("active-menu");
    } else if (tar.id == "register__menu") {
      document.querySelector(".login").classList.remove("active-menu");
      document.querySelector(".register").classList.add("active-menu");
    }
  }
}
function openMenu(e) {
  let block = this.classList.contains("login-true")
    ? document.querySelector(".user_authorized")
    : document.querySelector(".for-reg_login");
  if (this.classList.contains("img-act-js")) {
    block.classList.remove("img-active");
    this.classList.remove("img-act-js");
  } else {
    block.classList.add("img-active");
    this.classList.add("img-act-js");
  }
}
function loadYoutube(elem) {
  let yout = elem.parentElement.parentElement;
  let videoId = yout.id;
  yout.innerHTML =
    '<iframe class="iframe" id="ytplayer" type="text/html" width="720" height="405" src="https://www.youtube.com/embed/' +
    videoId +
    '?autoplay=1&end=180" frameborder="0" allowfullscreen></iframe>';
}
function showPass(elem, inpt) {
  console.log(elem, inpt);
  let inpPass = document.getElementById(inpt);
  if (elem.classList.contains("off")) {
    elem.classList.remove("off");
    inpPass.type = "text";
  } else {
    elem.classList.add("off");
    inpPass.type = "password";
  }
}

function handUser(e) {
  let tar = e.target;
  if (!tar.matches(".title_profile:not(.active-title)")) return;
  this.querySelector(".active-title").classList.remove("active-title");
  document.querySelector(".act_user-menu").classList.remove("act_user-menu");
  document
    .getElementById(tar.getAttribute("data-target"))
    .classList.add("act_user-menu");
  tar.classList.add("active-title");
}
function sendAuth(e) {
  e.preventDefault();
  let form = new FormData(this);
  fetch("auth.php", { method: "POST", body: form })
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      res = JSON.parse(res);
      console.log(res);
      let statText = {
        pass: "Неправильный пароль или никнейм",
        nick: "Такой никнейм уже существует",
        email: "Такой email уже существует",
      };
      if (res.err) {
        noticeAll(statText[res.err], 1);
        return;
      }
      if (res.act == "log") {
        let img = document.getElementById("img_user-js");
        img.click();
        img.src = res.picture;
        img.classList.add("login-true");
        window.location.reload();
      } else if (res.act == "reg") {
        noticeAll("Аккаунт зарегистрирован. Войдите");
      }
    })
    .catch((err) => {
      if (err.message == 401) {
        noticeAll("Произошла неизвестная ошибка", 1);
      }
    });
}
function loadProfile(e) {
  let name = document.getElementById("usnick");
  if (!name) return;

  let state = {
    url: "?do=profile",
    title: name.innerText,
    xPage: window.pageYOffset || document.documentElement.scrollTop,
  };
  history.pushState(state, state.title, state.url);
  document.getElementById("rename_page-js").scrollIntoView();
  loadNew("?do=profile");
}
function loadUlist(list, page) {
  fetch("desktop/user_list.php?" + list + "=" + page)
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      let result = JSON.parse(res);
      console.log(result);
      let block = document.getElementById("content-" + list);
      block.append(generList(result));
    })
    .catch((err) => {
      console.log(err);
    });
}
function exitProfile(e) {
  fetch("desktop/exit.php")
    .then(fetchHandler)
    .then((res) => {
      let profile = document.getElementById("img_user-js");
      Cookies.remove("_uida");
      Cookies.remove("_uide");
      profile.click();
      profile.classList.remove("login-true");
      profile.src = "asset/user.svg";
      document.getElementById("usnick").innerText = "";
      if (location.search == "?do=profile") {
        location.href = "http://realese";
      }
    })
    .catch((err) => {
      noticeAll("Код ошибки: " + err.message, 1);
      console.error(err);
    });
}
function downloadEv(e) {
  let tar = e.target;
  if (!tar.classList.contains("bl_file")) return;
  let fileUrl = tar.getAttribute("data-fileg");
  fetch("desktop/download.php?down=" + fileUrl)
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      let link = document.createElement("a");
      link.setAttribute("href", fileUrl);
      link.setAttribute("download", fileUrl);
      link.click();
    })
    .catch((error) => {
      console.log(error);
    });
}
function reviewLike(e) {
  let id = this.firstChild;
  let num = this.lastChild;
  fetch("desktop/review_like.php?liked=" + id.getAttribute("review-id"))
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      res = JSON.parse(res);
      if (res.error) {
        noticeAll(res.error, 1);
        return;
      }
      if (!res.act) {
        id.classList.remove("rev_like");
        num.innerText = Number(num.innerText) - 1;
      } else {
        id.classList.add("rev_like");
        num.innerText = Number(num.innerText) + 1;
      }
    })
    .catch((err) => {
      noticeAll("Код ошибки: " + err.message, 1);
    });
}
function generRev(json) {
  let frag = document.createDocumentFragment();
  let groupRat = create("div", "group_ratings");
  for (let index = 1; index < 6; index++) {
    if (Number(json[1]) >= index) {
      let star = create("img", "icon-star__rat");
      star.src = "asset/star-on.svg";
      groupRat.append(star);
    } else {
      let star = create("img", "icon-star__rat");
      star.src = "asset/star-off.svg";
      groupRat.append(star);
    }
  }
  let text = create("div", "text-value__rat");
  text.innerText = json[2];
  let edit = create("div", "block__edit-review");
  edit.innerHTML =
    "<button onclick='delRev(this)' data-revid='" +
    json[0] +
    "' class='edit-review__btn' id='delete-review'>Удалить</button><button onclick=\"showReview('leave-review')\" class='edit-review__btn' id='edit-review'>Изменить</button>";
  frag.append(groupRat, text, edit);
  console.log(frag);
  return frag;
}
function loadRev() {
  fetch("user-rev.php")
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      res = JSON.parse(res);
      window.res;
      let cont = document.getElementById("content-review");
      let yours = document.getElementById("yours-review");
      let comm = document.getElementById("content-comment");
      if (!("no" in res)) {
        let last = comm.querySelector("[data-revid='" + res[0][0] + "']");
        yours.innerHTML = "";
        yours.append(generRev(res[0]));
        cont.querySelector(".bl__write-review").hidden = true;
        document.getElementById("general-" + res[0][1]).checked = true;
        document.querySelector(".text-review").innerText = res[0][2];
        if (last != undefined) {
          last.querySelector(".comm-value").innerText = res[0][1];
          last.querySelector(".comm-text").innerText = res[0][2];
        }
      } else {
        cont.querySelector(".bl__write-review").hidden = false;
        yours.innerHTML = "";
      }
    });
}
function sendReview(e) {
  // Send user review (save)
  e.preventDefault();
  fetch("desktop/review.php", {
    method: "post",
    body: new FormData(this),
  })
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      res = JSON.parse(res);
      loadRev();
      if (res[0][3]) {
        loadComment("user");
      }
      noticeAll("Отзыв отправлен");
    })
    .catch((err) => {
      noticeAll("Отзыв отправлен");
    });
}
function showReview(tar) {
  let leaveRev = document.querySelector("." + tar);
  if (leaveRev.classList.contains("hidden-rev")) {
    leaveRev.classList.remove("hidden-rev");
  } else {
    leaveRev.classList.add("hidden-rev");
  }
}
function hideRev(e) {
  if (e.target.classList.contains(this.classList[0])) {
    this.classList.add("hidden-rev");
  }
}
function delRev(e) {
  // Delete User review
  fetch("desktop/revdel.php")
    .then(fetchHandler)
    .then((res) => {
      let comm = document.getElementById("content-comment");
      loadRev();
      comm
        .querySelector("[data-revid='" + e.getAttribute("data-revid") + "']")
        .remove();
      noticeAll("Отзыв удален");
    })
    .catch((err) => {
      noticeAll("Произошла ошибка", 1);
    });
}
function updateProf(e) {
  e.preventDefault();
  let data = new FormData(this);
  fetch("duseredit.php", {
    method: "post",
    body: data,
  })
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      res = JSON.parse(res);

      if (res.err) {
        noticeAll(res.err, 1);
      } else {
        sessionStorage.removeItem("?do=profile");
        if (res.nick) {
          noticeAll("Никнейм изменен");
        }
        if (res.pass) {
          noticeAll("Пароль обновлен");
        } else {
          noticeAll("Профиль обновлен");
        }
      }
    })
    .catch((err) => {
      console.log(err);
      noticeAll("Ошибка обновления профиля", 1);
    });
}
function updateAvatar(e) {
  let data = new FormData();
  let file_attach = document.getElementById("img-upload__input");
  data.append("image", file_attach.files[0]);
  fetch("duseredit.php", {
    method: "post",
    body: data,
  })
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      this.style.display = "none";
      sessionStorage.removeItem("?do=profile");
      noticeAll("Аватар обновлен");
    })
    .catch((err) => {
      noticeAll("Ошибка обновления аватара", 1);
    });
}
function handleFileSelect(evt) {
  var file = evt.target.files; // FileList object
  var f = file[0];
  // Only process image files.
  if (!f.type.match("image.*")) {
    noticeAll("Можно загружать только изображения", 1);
    return;
  }
  // проверим размер файла (<2 Мб)
  if (f.size > 2 * 1024 * 1024) {
    noticeAll("Размер файла не больше 2мб", 1);
    return;
  }
  var reader = new FileReader();
  // Closure to capture the file information.
  reader.onload = (function (theFile) {
    return function (e) {
      // Render thumbnail
      let avatarThumb = document.getElementById("avatar-user");
      document.querySelector(".submit-avatar").style.display = "";
      avatarThumb.src = e.target.result;
      avatarThumb.alt = escape(theFile.name);
    };
  })(f);
  // Read in the image file as a data URL.
  reader.readAsDataURL(f);
}
function nickCheck(nick) {
  fetch("nick-check.php?nick=" + nick.value)
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      res = JSON.parse(res);
      console.log(res);
      if (res.nick == "1") {
        noticeAll("Такой никнейм уже существует");
        nick.classList.add("invalid");
      } else {
        nick.classList.remove("invalid");
      }
      console.log(res);
    })
    .catch((err) => {
      console.log(err);
    });
}
function feedLoad(e) {
  let state = {
    url: "?do=feedback",
    title: "Обратная связь",
    xPage: window.pageYOffset || document.documentElement.scrollTop,
  };
  history.pushState(state, state.title, state.url);
  document.getElementById("rename_page-js").scrollIntoView();
  loadNew(state.url);
}
(function () {
  loadNew(location.search);
  document.getElementById("js-load_cat").addEventListener("click", loadCat);
  document
    .getElementById("slider__wrapper")
    .addEventListener("click", loadGame);
  document.getElementById("wait_content").addEventListener("click", loadGame);
  document.getElementById("content-js").addEventListener("click", contentHand);
  document.getElementById("search_grid").addEventListener("submit", formSearch);
  document
    .getElementById("search_string")
    .addEventListener("submit", searchWord);
  document.getElementById("string").addEventListener("input", autoComplete);
  document.getElementById("string").addEventListener("focus", focusAutoCompl);
  document.getElementById("user-hand-js").addEventListener("click", userMenu);
  document.getElementById("img_user-js").addEventListener("click", openMenu);
  document.getElementById("register").addEventListener("submit", sendAuth);
  document.getElementById("login").addEventListener("submit", sendAuth);
  document
    .getElementById("profile-link")
    .addEventListener("click", loadProfile);
  document.getElementById("exit-link").addEventListener("click", exitProfile);
  document.getElementById("feedback").addEventListener("click", feedLoad);
})();
window.onpopstate = (e) => {
  loadNew(location.search);
  rnamePage(history.state.title);
  window.scrollTo(0, history.state.xPage);
};
function favoritSet(tar) {
  let art = tar.hasAttribute("data-url")
    ? tar.getAttribute("data-url")
    : tar.parentNode.previousElementSibling.getAttribute("data-url");
  let nameGame = tar.hasAttribute("data-url")
    ? tar.parentNode.previousElementSibling.alt
    : tar.parentNode.nextElementSibling.alt;
  fetch("desktop/favorit.php" + art)
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      res = JSON.parse(res);
      if (res.error) {
        noticeAll(res.error, 1);
        return;
      }
      if (res.act) {
        tar.classList.remove("dislike");
        tar.classList.add("like");
        noticeAll(
          "<span class='temp-fav'> " +
            nameGame +
            "<br><span class='commd-text'>Добавлено в избранное</span></span>"
        );
      } else {
        tar.classList.add("dislike");
        tar.classList.remove("like");
        noticeAll(
          "<span class='temp-fav'> " +
            nameGame +
            "<br><span class='commd-text'>Удалено из избранного</span></span>"
        );
      }
    })
    .catch((err) => {
      noticeAll("Код ошибки: " + err.message, 1);
    });
}
