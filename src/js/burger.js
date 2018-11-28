(function () {

  setTimeout(() => {
    const burgerBtn = document.querySelector('.mainMenu__burgerBtn');
    const menu = document.querySelector('.menu-main-menu-container');

    if (burgerBtn && menu) {
      burgerBtn.addEventListener('click', e => {
        menu.classList.toggle('menu-main-menu-container--show');
      })
    }

    try {
      console.log(MainMenuFromWP);
    } catch (e) {
      console.log(e.message)
    }

  }, 100);
}());