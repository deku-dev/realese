<header>
  <div class="head_search">
    <div class="inner_header">
      <img class="icon_site" src="asset/hotpng.com.png" alt="mySite" />
      <div class="str_search">
        <form id="search_string">
          <input id="string" name="search" type="search" value="<?echo htmlspecialchars($_GET['search']) ?>"
            placeholder="Искать здесь..." autocomplete="off" />
          <button id="btn_string" type="submit">
            <svg class="search_icon" fill="#bd2c2c" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="20px"
              height="20px">
              <path
                d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z" />
            </svg>
          </button>
          <div id="autocomplete" class="auto-hidden" style="visibility:hidden;">
          </div>
        </form>
      </div>
      <div id="user-hand-js" class="for_user">
        <div class="menu-for"><img onerror="this.src='asset/user.svg'" id="img_user-js"
            src="<?echo $_SESSION['auth_flag'] ? $_SESSION['picture'] : " asset/user.svg"; ?>" class="img-user
          <?echo $_SESSION['auth_flag'] ? " login-true" : ""; ?>">
        </div>
        <div class="for-reg_login">
          <div class="top_menu-user">
            <span id="login__menu" class="tit-user_menu active-tit">Войти</span>
            <span id="register__menu" class="tit-user_menu">Регистрация</span>
          </div>
          <div class="block-us_menu">
            <div class="register block-cont">
              <form class="form-for-reg" id="register">
                <input required oninput="nickCheck(this)" autocomplete="username" placeholder="Никнейм"
                  class="input-user" type="text" name="nick" id="rnick">
                <input required autocomplete="email" placeholder="Email" class="input-user" type="email" name="mail"
                  id="email">
                <div class="block-pass">
                  <input required autocomplete="new-password" placeholder="Пароль" class="input-user" type="password"
                    name="rpass" id="rpass">
                  <label class="check-lab"><input onclick="showPass(this, 'rpass')" name="vsp" type="checkbox"
                      class="pass-check off">
                    Показать
                    пароль</label>
                </div>
                <input class="input-user" type="submit" value="Регистрация">
              </form>
            </div>
            <div class="login block-cont active-menu">
              <form class="form-for-reg" id="login">
                <input required autocomplete="username" placeholder="Никнейм" class="input-user" type="text" name="nick"
                  id="lnick">
                <div class="block-pass">
                  <input required autocomplete="current-password" placeholder="Пароль" class="input-user"
                    type="password" name="lpass" id="lpass">
                  <label class="check-lab"><input onclick="showPass(this, 'lpass')" name="vsp" type="checkbox"
                      class="pass-check off">
                    Показать
                    пароль</label>
                </div>
                <input class="input-user" type="submit" value="Войти">
              </form>
            </div>
          </div>
        </div>
        <div class="user_authorized">
          <div class="top-user menu-user_prof">
            <span class="user-nickname" id="usnick">
              <?echo $_SESSION['user_name'] ?>
            </span>
          </div>
          <div class="menu-user_profile menu-user_prof">
            <span class="link-for-prof" id="profile-link"><img src="asset/user.svg" class="svg-link"></span>
            <span class="link-for-prof" id="exit-link"><img src="asset/exit-account.svg" class="svg-link"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- Правка при обновлении страницы -->