"use strict";
console.time("hello");
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
function replClass() {
  this.classList.toggle("menu-icon--open");
  if (
    !document
      .getElementById(this.getAttribute("data-lst"))
      .classList.contains("nav--active")
  ) {
    document.body.style.overflow = "hidden";
  } else {
    document.body.style.overflow = "";
  }
  document
    .getElementById(this.getAttribute("data-lst"))
    .classList.toggle("nav--active");
}

function pageError(error) {
  document.getElementById("content").innerHTML =
    '<div class="err__cont"><div class="err__block"><div class="err__text">Страницы НЕТ</div><div class="err__code">404</div></div><nav class="err__menu">  <span class="err__block-link"><a href="/" class="err__link home">На главную</a></span><span class="err__block-link"><a href="?top" class="err__link top">Топ 100</a></span><span class="err__block-link"><a href="" class="err__link lucky">Мне повезёт!</a></span></nav><div class="recom__block"><h2 class="recom__title">Возможно вам понравится:</h2>  <div id="recommend"></div></div></div>';
  let recom = document.getElementById("recommend");
  fetch("game.php?err")
    .then(resStatus)
    .then(text)
    .then((html) => {
      recom.innerHTML = html;
      document.getElementById("sort__block").style.display = "none";
      document.getElementById("nav__page").style.display = "none";
      document.getElementById("num__pages").innerHTML = "";
    });
}
function hideMenu(a, b) {
  a.style.height = 0;
  b.classList.remove("nav__link--minus");
  b.classList.add("nav__link--plus");
  b.parentNode.classList.remove("link_parent");
  a.addEventListener(
    "transitionend",
    function () {
      a.style.visibility = "hidden";
      a.classList.remove("active");
    },
    {
      once: true,
    }
  );
}

function showMenu(a, b) {
  a.classList.add("active");
  let height = a.getAttribute("data-height");
  a.style.visibility = "visible";
  a.style.height = height;
  b.classList.remove("nav__link--plus");
  b.classList.add("nav__link--minus");
  b.parentNode.classList.add("link_parent");
}
function randInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min)) + min;
}

function resStatus(response) {
  let cou = response.headers.get("CountPages");
  if (cou == "no-page") {
    buildPage(1);
  } else {
    buildPage(cou);
    document.getElementById("count").innerText =
      document.querySelector(".page:last-child") == null
        ? 1
        : document.querySelector(".page:last-child").innerText;
  }
  // console.log("hhh" + document.getElementById("count").innerText);
  if (response.ok) {
    return Promise.resolve(response);
  } else {
    return Promise.reject(new Error(response.status));
  }
}

function text(response) {
  return response.text();
}

function loadUrl(url, parseUrl) {
  let progBlock = document.getElementById("block_progr");
  let progres = document.getElementById("top_progr");
  progBlock.style.display = "inherit";
  progres.style.left = randInt(10, 20) + "%";
  fetch(url)
    .then((response) => {
      progres.style.left = randInt(30, 40) + "%";
      return resStatus(response);
    })
    .then((response) => {
      progres.style.left = randInt(45, 60) + "%";
      return text(response);
    })
    .then(function (text) {
      progres.style.left = randInt(61, 90) + "%";
      console.log(text);
      let geturl = new URL("http://" + url);
      let clickEvent = new Event("completeLoad");
      document.getElementById(parseUrl).innerHTML = text;
      document.getElementById("main").dispatchEvent(clickEvent);
      console.log(url);
      if (geturl.searchParams.has("art")) {
        document
          .querySelector(".download")
          .addEventListener("click", downloadEv);
        document
          .getElementById("review-send")
          .addEventListener("submit", reviewSend);
        document
          .getElementById("load-new_review")
          .addEventListener("click", reviewGet);
        document.getElementById("load-new_review").click();
        document
          .getElementById("review_list-js")
          .addEventListener("click", reviewLike);
        document
          .getElementById("rev__hand-js")
          .addEventListener("click", (e) => {
            let tar = e.target;
            if (tar.classList.contains("review_edit")) {
              var formRev = document.getElementById("review-send");
              var editRev = document.getElementById("user__feedback");
              editRev.classList.add("hidden_form");
              let cancelEl = document.createElement("span");
              cancelEl.className = "review__submit";
              cancelEl.id = "cancel-edit__review";
              cancelEl.innerText = "Отмена";
              cancelEl.addEventListener("click", (e) => {
                formRev.classList.add("hidden_form");
                editRev.classList.remove("hidden_form");
                cancelEl.remove();
              });
              if (!document.getElementById("cancel-edit__review")) {
                document.querySelector(".review__send").append(cancelEl);
              }
              formRev.classList.remove("hidden_form");
            } else if (tar.classList.contains("review_delete")) {
              let delConf = confirm("Действительно хотите удалить отзыв?");
              if (delConf) {
                easyFetch(
                  "revdel.php",
                  "rid=" + tar.getAttribute("review-id"),
                  (res) => {
                    console.log(res);
                    document
                      .getElementById("user__feedback")
                      .classList.add("hidden_form");
                    document
                      .getElementById("review-send")
                      .classList.remove("hidden_form");
                    if (
                      document.querySelector(
                        "[review-id='" + tar.getAttribute("review-id") + "']"
                      )
                    ) {
                      let parentList = document.getElementById(
                        "review_list-js"
                      );
                      let removeReview = parentList.querySelector(
                        "[review-id='" + tar.getAttribute("review-id") + "']"
                      );
                      let parentRemove = removeReview.closest(".review__elem");
                      parentList.removeChild(parentRemove);
                    }
                  },
                  (error) => {
                    console.log(error);
                  }
                );
              }
            }
          });
      }
      if (geturl.searchParams.has("do")) {
        let optget = geturl.searchParams.get("do");
        if (optget == "feedback") {
          document
            .getElementById("feed__form")
            .addEventListener("submit", formSend);
        } else if (optget == "profile") {
          let editSub = document.getElementById("form-edit__js");
          if (editSub) {
            editSub.addEventListener("submit", editSubmit);
          }
          document
            .getElementById("btn_edit-user")
            .addEventListener("click", showEdit);
          document
            .getElementById("user-image__js")
            .addEventListener("change", handleFileSelect, false);
          document
            .getElementById("profile__nav-js")
            .addEventListener("click", profileMenu);
          document;
          document
            .querySelectorAll(".profile_pass-eye")[0]
            .addEventListener("click", openPass);
          document
            .querySelectorAll(".profile_pass-eye")[1]
            .addEventListener("click", openPass);
          document.getElementById("recent").addEventListener("click", loadGame);
          document
            .getElementById("favorites")
            .addEventListener("click", loadGame);
          let userName = document.querySelector(".header__user-name").innerText;
          let state = {
            url: "?do=profile",
            title: userName,
            count: 1,
            page: 1,
            xPage: 0,
          };
          history.pushState(state, state.title, state.url);
          document.title = userName;
          hideSort();
        }
      }
      progBlock.style.display = "none";
    })
    .catch((error) => {
      console.error(error);
      pageError(error);
      progBlock.style.display = "none";
    });
}
function removeClass(rclass, matches) {
  let selected = matches.querySelectorAll("." + rclass);
  for (let i = 0; i < selected.length; i++) {
    selected[i].classList.remove(rclass);
  }
}

function handler(link, select) {
  let state = {
    url: link.getAttribute("href"),
    title: link.innerText,
    count: link.getAttribute("data-count"),
    page: 1,
    xPage: window.pageXOffset || document.documentElement.scrollTop,
  };
  if (!link.classList.contains(select)) {
    loadUrl("game.php" + state.url, "content");
    history.pushState(state, state.title, state.url);
    renamePage(state.title);
  }
  link.classList.add(select);
}

function handlerAnchors() {
  removeClass("select__link", document.getElementById("nav_list"));
  handler(this, "select__link");
  document.getElementById("main").addEventListener(
    "completeLoad",
    () => {
      window.scrollTo(0, 0);
      if (
        document.getElementById("toggle-nav").classList.contains("nav--active")
      ) {
        document.getElementById("menu").click();
      }
    },
    { once: true }
  );
}
function renamePage(name) {
  document.getElementById("name__page").innerHTML = name;
  document.getElementById("block__name-page").style.display = "";
  document.title = name;
}
function updateUrlPage(e) {
  e.preventDefault();
  let eTarget = e.target;
  if (
    eTarget.classList.contains("select__link") ||
    !eTarget.classList.contains("page")
  )
    return;
  let selected = this.querySelectorAll(".select__link");
  for (let i = 0; i < selected.length; i++) {
    selected[i].classList.remove("select__link");
  }
  let params = new URL(document.location);
  params.searchParams.set("page", eTarget.getAttribute("href"));
  loadUrl("game.php" + params.search, "content");
  let pageTitle = document.title.includes("|")
    ? document.title.slice(0, document.title.indexOf("|") - 1)
    : document.title;
  let state = {
    url: params.search,
    title: pageTitle + " | Страница " + eTarget.getAttribute("href"),
    count: document.getElementById("count").innerText,
    page: eTarget.getAttribute("href"),
    xPage: window.pageXOffset || document.documentElement.scrollTop,
  };
  history.pushState(state, state.title, state.url);
  document.getElementById("main").addEventListener(
    "completeLoad",
    () => {
      renamePage(pageTitle + " | Страница " + state.page);
      eTarget.classList.add("select__link");
      window.scrollTo(0, 0);
    },
    { once: true }
  );
}
function parseURL(url) {
  return new URLSearchParams(url);
}
function menuAnim(e) {
  e.preventDefault();
  let elClick = e.target;
  let animateElem = elClick.nextElementSibling;
  if (elClick.classList.contains("link_setup")) {
    elClick.addEventListener("click", handlerAnchors);
  }
  if (!elClick.hasAttribute("data-menu")) return;
  if (!animateElem.classList.contains("active")) {
    let act = document.querySelectorAll(".nav__sub-list");
    for (let navSub of act) {
      if (navSub.classList.contains("active")) {
        hideMenu(navSub, navSub.previousElementSibling);
      }
    }
    showMenu(animateElem, elClick);
  } else {
    hideMenu(animateElem, elClick);
  }
}

function buildPage(count) {
  console.log("hhttt" + count);
  let urlSort = parseURL(location.search);
  let currentP = Number(urlSort.get("page"));
  console.log(currentP);
  let page = document.getElementById("num__pages");
  if (urlSort.has("art")) {
    document.getElementById("sort__block").style.display = "none";
    document.getElementById("nav__page").style.display = "none";
    page.innerHTML = "";
    return;
  }
  if (count == 1) {
    document.getElementById("nav__page").style.display = "none";
    page.innerHTML = "";
    return;
  }
  document.getElementById("sort__block").style.display = "";
  document.getElementById("nav__page").style.display = "";

  if (currentP <= 0) currentP = 1;
  page.innerHTML = "";
  let pagehtml = "";
  let first = currentP >= 3 ? currentP - 2 : 1;
  let second = count - currentP >= 2 ? Number(currentP) + 2 : count;
  for (let i = first; i <= count; i++) {
    if (i == second && second != count) {
      pagehtml += "<a class='page' href='" + i + "'>" + i + "</a>";
      pagehtml += "<span class='space'>...</span>";
      i = count;
    }
    if (i == currentP) {
      pagehtml += "<a class='page select__link' href='" + i + "'>" + i + "</a>";
      continue;
    }
    pagehtml += "<a class='page' href='" + i + "'>" + i + "</a>";
  }
  page.innerHTML = pagehtml;
}
function search(e) {
  let inputBtn = this.previousElementSibling.value;
  if (inputBtn == "") return;
  let searStr = parseURL(location.search);
  searStr.set("search", inputBtn);
  let offsetX = window.pageXOffset || document.documentElement.scrollTop;
  loadUrl("game.php?search=" + inputBtn, "content");
  document.getElementById("main").addEventListener(
    "completeLoad",
    function () {
      let state = {
        url: "?" + searStr.toString(),
        title: "Результаты по поиску:" + inputBtn,
        count: document.getElementById("count").innerText,
        page: 1,
        xPage: offsetX,
      };
      window.scrollTo(0, 0);
      history.pushState(state, state.title, state.url);
      if (
        document.getElementById("toggle-nav").classList.contains("nav--active")
      ) {
        document.getElementById("menu").click();
      }
    },
    { once: true }
  );
}

function btnPage(a) {
  let url = parseURL(location.search);
  url.set("page", a);
  window.location.search = "?" + url;
}
function handPage(e) {
  let tar = e.target.parentElement;
  if (tar.classList.contains("end")) {
    btnPage(document.getElementById("count").innerText);
  } else if (tar.classList.contains("start")) {
    btnPage(1);
  }
}
function loadYoutube(e) {
  let clVid = e.target;
  console.log(e.target);
  if (!clVid.classList.contains("play_icon")) return;
  let parTube = clVid.classList.contains("play_icon")
    ? clVid.parentElement.parentElement
    : clVid.classList.contains("filter")
    ? clVid.parentElement
    : null;
  if (parTube === null) return;
  let indVid = parTube.id;
  parTube.innerHTML =
    '<iframe class="iframe" id="ytplayer" type="text/html" width="720" height="405" src="https://www.youtube.com/embed/' +
    indVid +
    '?autoplay=1&end=180" frameborder="0" allowfullscreen></iframe>';
}
function sort(e) {
  let sortUrl = parseURL(location.search);
  sortUrl.set(this.id, this.value);
  let state = {
    url: "?" + sortUrl.toString(),
    title: document.title,
    count: document.getElementById("count").innerText,
    page: sortUrl.get("page") == null ? 1 : sortUrl.get("page"),
    xPage: window.pageXOffset || document.documentElement.scrollTop,
  };
  console.log(state);
  history.pushState(state, state.title, state.url);
  loadUrl("game.php" + state.url, "content");
}
function loadGame(e) {
  e.preventDefault;
  let link = e.target;
  if (!link.classList.contains("game_url")) return;

  let state = {
    url: link.getAttribute("data-games"),
    title: document.title,
    count: 1,
    page: 1,
    xPage: window.pageXOffset || document.documentElement.scrollTop,
  };
  console.log(state);
  document.getElementById("sort__block").style.display = "none";
  history.pushState(state, state.title, state.url);
  loadUrl("game.php" + state.url, "content");
  document.getElementById("main").addEventListener(
    "completeLoad",
    function () {
      document.title = document.getElementById("game_name").innerHTML;
      document.getElementById("block__name-page").style.display = "none";
      window.scrollTo(0, 0);
      document
        .getElementById("video_block")
        .addEventListener("click", loadYoutube);
      if (
        document.getElementById("toggle-nav").classList.contains("nav--active")
      ) {
        document.getElementById("menu").click();
      }
    },
    { once: true }
  );
}
function hideSort(opt = "none", count = 1) {
  document.getElementById("sort__block").style.display = opt;
  document.getElementById("block__name-page").style.display = opt;
  document.getElementById("nav__page").style.display = opt;
  buildPage(count);
}
function automate(e) {
  if (this.value.length < 3) return;
  loadUrl("autocompl.php?compl=" + this.value, "autocomplete");
}

var stateUrl = parseURL(location.search);
var pageLink = stateUrl.has("page") ? stateUrl.get("page") : 1;

document
  .getElementById("main")
  .addEventListener("completeLoad", initMenu, { once: true });
loadUrl("game.php" + location.search, "content");

function initMenu() {
  var mainLC = document.getElementById("count").innerText;
  if (pageLink > Number(mainLC) && !stateUrl.has("do")) {
    btnPage(mainLC);
  }
  stateUrl.delete("page");
  let stateLink = document.querySelector(
    '#nav_list .nav__link[href="?' + stateUrl.toString() + '"]'
  );
  if (!stateUrl.has("art")) {
    if (stateLink != null) {
      renamePage(
        pageLink == 1
          ? stateLink.innerHTML
          : stateLink.innerHTML + " | Страница " + pageLink
      );
    } else if (stateUrl.toString() == "") {
      renamePage(
        pageLink == 1 ? "Все игры" : "Все игры" + " | Страница " + pageLink
      );
    }
  }

  mainLC = stateLink !== null ? stateLink.getAttribute("data-count") : mainLC;
  document.getElementById("content").addEventListener("click", loadGame);
  document.getElementById("sear_btn").addEventListener("click", search);
  document.getElementById("menu").addEventListener("click", replClass);
  document.getElementById("nav_list").addEventListener("click", menuAnim, true);
  document.getElementById("s").addEventListener("change", sort);
  document.getElementById("st").addEventListener("change", sort);
  document.getElementById("autocomplete").addEventListener("click", loadGame);
  var autoCompr = document.getElementById("autocomplete");
  document.getElementById("search").addEventListener("blur", () => {
    setTimeout(() => (autoCompr.style.visibility = "hidden"), 200);
  });
  document.getElementById("search").addEventListener("input", automate);
  document.getElementById("search").addEventListener("focus", () => {
    autoCompr.style.visibility = "visible";
  });
  document
    .getElementById("num__pages")
    .addEventListener("click", updateUrlPage);
  document.getElementById("nav__page").addEventListener("click", handPage);
  if (stateUrl.has("do")) {
    hideSort();
    return;
  }
  if (stateUrl.has("art")) {
    hideSort();
    if (stateUrl.has("art")) {
      document
        .getElementById("video_block")
        .addEventListener("click", loadYoutube);
    } else {
      document
        .getElementById("feed__form")
        .addEventListener("submit", formSend);
      document.getElementById("sort__block").style.display = "none";
    }
    return;
  } else if (stateUrl.has("search")) {
    buildPage(document.getElementById("count_sear").innerText, pageLink);
  }
  if (stateUrl.has("s")) {
    document
      .querySelector('#s [value="' + stateUrl.get("s") + '"]')
      .setAttribute("selected", "");
  }
  if (stateUrl.has("st")) {
    document
      .querySelector('#st [value="' + stateUrl.get("st") + '"]')
      .setAttribute("selected", "");
  }
  if (location.search == "") {
    buildPage(mainLC, 1);
    return;
  }

  if (stateLink !== null) {
    stateLink.classList.add("select__link");
    buildPage(mainLC, pageLink);
  } else buildPage(mainLC, pageLink);

  let numpage = document.querySelector('.page[href="' + pageLink + '"]');

  if (numpage !== null) numpage.classList.add("select__link");
}
(function () {
  let heightElem = document.querySelectorAll(".nav__sub-list");
  for (let i = 0; i < heightElem.length; i++) {
    heightElem[i].setAttribute(
      "data-height",
      heightElem[i].scrollHeight + "px"
    );
    heightElem[i].style.height = 0;
  }
})();
let fix = document.querySelectorAll(".nav__sub-list:not(.active)");
for (let i = 0; i < fix.length; i++) {
  fix[i].style.height = 0;
}

window.onpopstate = function () {
  removeClass("select__link", document);
  let title = history.state == null ? "Главная" : history.state.title;
  renamePage(title);
  loadUrl("game.php" + history.state.url, "content");
  document.getElementById("main").addEventListener(
    "completeLoad",
    () => {
      let stateUrl = parseURL(location.search);
      let pageLink = stateUrl.has("page") ? stateUrl.get("page") : 1;
      stateUrl.delete("page");
      if (!stateUrl.has("search")) {
        document.getElementById("sort__block").style.display = "";
        document.getElementById("nav__page").style.display = "";
      }
      if (stateUrl.has("art")) {
        hideSort();
      } else {
        hideSort("", history.state.count);
      }

      var thisScroll = window.pageXOffset || document.documentElement.scrollTop;

      document.getElementById("content").addEventListener("load", () => {
        if (
          thisScroll == window.pageXOffset ||
          document.documentElement.scrollTop
        ) {
          setTimeout(window.scrollTo(0, history.state.xPage), 100);
        }
      }),
        { once: true };

      let stateLink = stateUrl.has("new")
        ? document.querySelector('#nav_list .nav__link[href="?new"]')
        : stateUrl.has("top")
        ? document.querySelector('#nav_list .nav__link[href="?top"]')
        : document.querySelector(
            '#nav_list .nav__sub-item .nav__link[href="?' +
              stateUrl.toString() +
              '"]'
          );
      if (stateLink !== null) {
        stateLink.classList.add("select__link");
      }
      if (!stateUrl.has("art") && !stateUrl.has("do")) {
        document
          .querySelector('.page[href="' + pageLink + '"]')
          .classList.add("select__link");
      }
    },
    { once: true }
  );
};
function exitAcc(e) {
  e.preventDefault();
  fetch("mobile/exit.php?exit")
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      let profile = document.getElementById("profile");
      Cookies.remove("_uida");
      Cookies.remove("_uide");
      profile.classList.remove("user_lock_regs");
      profile.classList.add("user_none_regs");
      document.getElementById("user-pic").src = "asset/user.svg";
      document.getElementById("nick-user").innerText = "";
      if (location.search == "?do=profile") {
        location.href = "http://realese/";
      }
    })
    .catch((error) => {
      console.error(error);
    });
}
function formSend(e) {
  e.preventDefault();
  let lds = document.querySelector(".lds");
  lds.classList.add("lds-ellipsis");
  let errorSpan = document.getElementById("res__status");
  fetch("feedback.php?send", {
    method: "post",
    body: new FormData(document.getElementById("feed__form")),
  })
    .then((res) => {
      if (res.ok) {
        return Promise.resolve(res);
      } else {
        return Promise.reject(new Error(res.status));
      }
    })
    .then((res) => {
      if (!res.redirected) {
        lds.classList.remove("lds-ellipsis");
        errorSpan.innerText = "Сообщение отправлено успешно";
        errorSpan.classList.add("success");
        console.log("is ok");
      } else {
        return Promise.reject(new Error(404));
      }
    })
    .catch((error) => {
      lds.classList.remove("lds-ellipsis");
      errorSpan.innerText = "Ошибка код:" + error;
      console.error(error.status);
      errorSpan.classList.add("error");
      setTimeout(() => {
        errorSpan.classList.remove("error");
      }, 6000);
    });
}
document.getElementById("profile-menu").addEventListener("click", replClass);
document.getElementById("set_user-js").addEventListener("click", exitAcc);
document.getElementById("prof_exit").addEventListener("click", replClass);
document.getElementById("reg_form").addEventListener("submit", FRL);
document.getElementById("reg_nick").addEventListener("input", isexist);
document.getElementById("mail").addEventListener("input", isexist);
document.getElementById("log_form").addEventListener("submit", FRL);
document
  .querySelectorAll(".pass_control")[0]
  .addEventListener("click", openPass);
document
  .querySelectorAll(".pass_control")[1]
  .addEventListener("click", openPass);
document.getElementById("data-reg").addEventListener("click", (e) => {
  if (!e.target.classList.contains("link__register")) return;
  document.querySelector(".target--active").classList.remove("target--active");
  document
    .querySelector(".reg__active-link")
    .classList.remove("reg__active-link");
  e.target.classList.add("reg__active-link");
  document
    .getElementById(e.target.getAttribute("data-reg"))
    .classList.toggle("target--active");
});
function isexist(e) {
  if (window.timer) {
    clearTimeout(timer);
  }
  let data = this.value,
    name = this.name,
    nick = document.getElementById("reg_nick"),
    mail = document.getElementById("mail"),
    lds = document.querySelector(".js-load"),
    submitR = document.getElementById("reg_submit");
  window.timer = setTimeout(() => {
    fetch("verify.php", {
      method: "post",
      headers: {
        "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
      },
      body: name + "=" + data,
    })
      .then(fetchHandler)
      .then(text)
      .then((res) => {
        // if (res == "") {
        this.setAttribute("data-verf", true);
        if (mail.hasAttribute("data-verf") && nick.hasAttribute("data-verf")) {
          lds.classList.remove("error");
          lds.innerText = "";
          submitR.removeAttribute("disabled");
        }
        // } else {
        //   lds.innerText = "Ошибка";
        //   console.error(res);
        //   lds.classList.add("error");
        //   submitR.setAttribute("disabled", true);
        //   setTimeout(() => {
        //     lds.classList.remove("error");
        //     lds.innerText = "";
        //   }, 3000);
        // }
      })
      .catch((error) => {
        let errorArr = {
          "Error: 601": "Email уже существует",
          "Error: 600": "Никнейм уже существует",
        };
        lds.innerText = errorArr[error];
        console.error(errorArr[error]);
        lds.classList.add("error");
        submitR.setAttribute("disabled", true);
        setTimeout(() => {
          lds.classList.remove("error");
          lds.innerText = "";
        }, 3000);
      });
  }, 1000);
}
function openPass(e) {
  let open = this.previousElementSibling;
  this.classList.toggle("hide");
  if (open.getAttribute("type") == "password") {
    open.setAttribute("type", "text");
  } else {
    open.setAttribute("type", "password");
  }
}
function fetchHandler(res) {
  if (res.ok && !res.redirected) {
    return Promise.resolve(res);
  } else {
    return Promise.reject(new Error(res.status));
  }
}
function FRL(e) {
  e.preventDefault();
  let lds = document.querySelector(".js-load");
  lds.classList.add("dual-ring");
  fetch("register.php", {
    method: "post",
    body: new FormData(this),
  })
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      if (!res.redirected) {
        console.log(res);
        lds.classList.remove("dual-ring");
        if (
          document.getElementById("block_reg").classList.contains("nav--active")
        ) {
          if (this.id == "reg_form") {
            afterReg(res, false);
            this.reset();
          } else {
            afterReg(res, true);
            this.reset();
          }
        }
      } else {
        return Promise.reject(new Error(404));
      }
    })
    .catch((error) => {
      let err401 =
        this.id != "reg_form"
          ? "Неправильный Пароль или Никнейм "
          : "Никнейм или Email уже существует";
      let errorArr = {
        "Error: 401": err401,
        "Error: 500": "Произошла ошибка!Повторите попытку позже",
      };

      console.error(error);
      lds.classList.remove("dual-ring");
      lds.innerText = errorArr[error];
      lds.classList.add("error");
      setTimeout(() => {
        lds.classList.remove("error");
        lds.innerText = "";
      }, 6000);
    });
}
function afterReg(res, state) {
  if (state) {
    let jsonData = JSON.parse(res);
    document.getElementById("user-pic").src = jsonData.picture;
    document.getElementById("nick-user").innerText = jsonData.nick;
    document.getElementById("profile").classList.remove("user_none_regs");
    document.getElementById("profile").classList.add("user_lock_regs");
  }

  document.getElementById("profile-menu").click();
}
function profileMenu(e) {
  let tar = e.target;
  if (!tar.classList.contains("profile__link")) return;
  document
    .querySelector(".check__profile-menu")
    .classList.remove("check__profile-menu");
  document
    .getElementById(tar.getAttribute("data-profile"))
    .classList.add("check__profile-menu");
}

function handleFileSelect(evt) {
  var file = evt.target.files; // FileList object
  var f = file[0];
  let errorVeri = document.getElementById("thumbnail-image");
  // Only process image files.
  if (!f.type.match("image.*")) {
    errorVeri.innerText = "Можно загружать только изображения";

    return;
  }
  // проверим размер файла (<2 Мб)
  if (f.size > 2 * 1024 * 1024) {
    errorVeri.innerText = "Размер файла не больше 2мб";
    return;
  }

  var reader = new FileReader();
  // Closure to capture the file information.
  reader.onload = (function (theFile) {
    return function (e) {
      // Render thumbnail
      var span = document.createElement("span");
      span.innerHTML = [
        '<img class="thumb" title="',
        escape(theFile.name),
        '" src="',
        e.target.result,
        '" />',
      ].join("");
      errorVeri.innerHTML = "";
      errorVeri.insertBefore(span, null);
    };
  })(f);
  // Read in the image file as a data URL.
  reader.readAsDataURL(f);
}

function favoritSet() {
  fetch("mobile/favorit.php", {
    method: "post",
    body: parseURL(location.search),
  })
    .then((res) => {
      if (res.ok) {
        return Promise.resolve(res);
      } else {
        return Promise.reject(new Error(res.status));
      }
    })
    .then(text)
    .then((res) => {
      let favIcon = document.getElementById("favorit-svg");
      if (res == "1") {
        favIcon.classList.remove("dislike");
        favIcon.classList.add("like");
      } else if (res == "0") {
        favIcon.classList.add("dislike");
        favIcon.classList.remove("like");
      }
    });
}
function reviewLike(e) {
  let tar = e.target;
  if (!tar.classList.contains("review__like-img")) return;
  fetch("mobile/review_like.php?liked=" + tar.getAttribute("review-id"))
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      console.log(res);
      if (res == "DELETE") {
        tar.classList.remove("rev_like");
        tar.nextElementSibling.innerText =
          Number(tar.nextElementSibling.innerText) - 1;
      } else if (res == "INSERT") {
        tar.classList.add("rev_like");
        tar.nextElementSibling.innerText =
          Number(tar.nextElementSibling.innerText) + 1;
      }
    })
    .catch((error) => {
      console.log(error);
    });
}
function easyFetch(url, params, complete, failed) {
  fetch(url, {
    method: "post",
    headers: {
      "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
    body: params,
  })
    .then(fetchHandler)
    .then(text)
    .then(complete)
    .catch(failed);
}
function reviewSend(e) {
  e.preventDefault();
  fetch("mobile/review.php", {
    method: "post",
    body: new FormData(this),
  })
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      let reviewJson = JSON.parse(res);
      console.log(reviewJson);
      let value = reviewJson[1]["value"];
      let text = reviewJson[1]["text_review"];
      let date = reviewJson[1]["date"];
      let textElem = document.getElementById("text_review-js");
      let valueElem = document.getElementById("value_review-js");
      let dateElem = document.getElementById("date_review-js");
      let revElemBlock = document.getElementById("user__feedback");
      let revId = revElemBlock.querySelector("#review_id-js");
      if (reviewJson[1]["action"] == "INSERT") {
        // TODO добавление отзывов + в весь список
        revId.setAttribute("review-id", reviewJson[1]["rev_id"]);
        textElem.innerText = text;
        valueElem.innerText = value;
        dateElem.innerText = date;
        this.classList.add("hidden_form");
        revElemBlock.classList.remove("hidden_form");
        document.getElementById("review_list-js").prepend(generRev(reviewJson));
      } else if (reviewJson[1]["action"] == "UPDATE") {
        // TODO Обновление отзыва
        let review = document.querySelector(
          ".review__elem[review-id='" + revId.getAttribute("review-id") + "']"
        );
        textElem.innerText = text;
        valueElem.innerText = value;
        this.classList.add("hidden_form");
        revElemBlock.classList.remove("hidden_form");
        if (review) {
          // TODO Исправление отзывов при изменении в списке всех отзывов
          review.querySelector(".review__value").innerText = value + " ★";
          review.querySelector(".review__text-value").innerText = text;
        }
      }
    })
    .catch((error) => {
      console.error(error);
    });
}
function generRev(json) {
  let frag = document.createDocumentFragment();
  let block = document.createElement("div");
  block.className = "review__elem";
  for (const key in json) {
    let cloneBlock = block.cloneNode();
    cloneBlock.style.order = key;
    cloneBlock.setAttribute("review-id", json[key]["rev_id"]);
    cloneBlock.innerHTML =
      "<div class='review__img-block'><img class='review__user-image' src='" +
      json[key]["picture"] +
      "' alt='" +
      json[key]["nickname"] +
      "' /></div><div class='text__review'><div class='review__username'>" +
      json[key]["nickname"] +
      "</div><div class='review__data'><span class='review__value'>" +
      json[key]["value"] +
      " ★</span><span class='review__date'>" +
      json[key]["date"] +
      "</span></div><div class='review__text-value'>" +
      json[key]["text_review"] +
      "</div><span class='review__like'><img review-id='" +
      json[key]["rev_id"] +
      "' class='review__like-img' src='asset/like.svg' alt='Полезный'/><span class='review__like-value'>" +
      json[key]["likes"] +
      "</span></span></div>";
    frag.append(cloneBlock);
  }
  return frag;
}
function reviewGet(e) {
  this.classList.add("loading-anim");
  fetch("rev_get.php?rev_id=" + this.getAttribute("data-revpage"))
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      this.classList.remove("loading-anim");
      this.setAttribute(
        "data-revpage",
        Number(this.getAttribute("data-revpage")) + 1
      );
      document
        .getElementById("review_list-js")
        .append(generRev(JSON.parse(res)));
    })
    .catch((error) => {
      this.classList.add("err_review");
      setTimeout(() => {
        this.classList.remove("err_review");
      }, 2000);
    });
}
function downloadEv(e) {
  let tar = e.target;
  if (!tar.classList.contains("bl_file")) return;
  let fileUrl = tar.getAttribute("data-fileg");
  fetch("mobile/download.php?down=" + fileUrl)
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
function showEdit(e) {
  let showForm = document.getElementById("form-edit__js");
  showForm.classList.toggle("edit-user__active");
}
function editSubmit(e) {
  e.preventDefault();
  let file_attach = document.getElementById("user-image__js");
  let fData = new FormData(this);
  // fData.append("image", file_attach.files[0]);
  fetch("museredit.php", {
    method: "post",
    body: fData,
  })
    .then(fetchHandler)
    .then(text)
    .then((res) => {
      this.reset();
      file_attach.value = "";
      if (!/safari/i.test(navigator.userAgent)) {
        file_attach.type = "";
        file_attach.type = "file";
      }
      document.getElementById("thumbnail-image").innerHTML = "";
    })
    .catch((error) => {
      console.error(error);
    });
}
console.timeEnd("hello");
