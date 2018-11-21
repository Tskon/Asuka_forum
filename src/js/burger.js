(function () {
  const burgerBtn = document.querySelector('.mainMenu__burgerBtn');
  const menu = document.querySelector('.menu-main-menu-container');

  document.body.addEventListener('click', e => {
    if (menu.classList.contains('menu-main-menu-container--show')) {
      menu.classList.remove('menu-main-menu-container--show');
    }
  });

  burgerBtn.addEventListener('click', e => {
    setTimeout(() => {
      menu.classList.toggle('menu-main-menu-container--show');
    }, 0);
  })

}());