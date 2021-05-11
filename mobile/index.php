<?php
require_once 'connection.php';
if (isset($_GET['exit'])) {
  require_once "exit.php";
}
require_once 'head.php';
require_once 'header.php';
?>
<div id="main">
  <div class="block__register">
    <div class="inner__register" id="block_reg">
      <div class="filter_back">
        <span id="prof_exit" data-lst="block_reg"><img src="asset/close.svg" alt="" class="close"></span>
        <div id="data-reg" class="link__reg-log">
          <span data-reg="login" id="log" class="link__register reg__active-link">Войти</span>
          <span data-reg="register" id="reg" class="link__register">Регистрация</span>
        </div>
        <div class="js-load"></div>
        <div id="login" class="target__reg target--active">
          <form class="form-reg" action="register.php?login" method="post" id="log_form">
            <input autocomplete="username" class="input-reg" type="text" placeholder="Никнейм" name="lnick" id="lnick"
              required>
            <label class="open_pass"><input class="input-reg" autocomplete="current-password" type="password"
                placeholder="Пароль" name="lpass" id="lpass" required><span class="pass_control"><span
                  class="icon_aye"></span></span>
            </label>
            <input class="input-reg submit_reg" type="submit" value="Войти">
          </form>
        </div>
        <div id="register" class="target__reg">
          <form class="form-reg" action="register.php?register" method="post" id="reg_form">
            <input autocomplete="username" class="input-reg" type="text" placeholder="Никнейм" name="rnick"
              id="reg_nick" required>
            <input autocomplete="email" class="input-reg" type="email" placeholder="Email" name="rmail" id="mail"
              required>
            <label class="open_pass"><input class="input-reg" autocomplete="new-password" type="password"
                placeholder="Пароль" minlength="8" name="rpass" id="reg_pass" required>
              <span class="pass_control"><span class="icon_aye"></span></span></label>
            <input id="reg_submit" class="input-reg submit_reg" type="submit" disabled value="Зарегистрироваться">
          </form>
        </div>

      </div>
    </div>
  </div>

  <div id="block__name-page">
    <hr class="hrc">
    <div id="name__page"></div>
    <hr class="hrc">
  </div>


  <div id="sort__block">
    <select class="sort__fom" id="s">
      <option value="">Сортировка</option>
      <option value="n">По имени</option>
      <option value="d">По дате</option>
      <option value="v">По просмотрам</option>
    </select>
    <select class="sort__fom" id="st">
      <option value="asc">По возрастанию</option>
      <option value="desc">По убыванию</option>
    </select>
  </div>
  <div id="content">
  </div>
  <div id="nav__page">
    <div class="btn__page start">
      <span class="arrow hover arr__left"></span><span class="page__text hover">Начало</span>
    </div>
    <div class=" btn__page end"><span class="page__text hover">Конец</span><span class="arrow arr__right hover"></span>
    </div>
  </div>

  <div id="num__pages">
  </div>

</div>
<?php
require_once 'footer.php';
?>