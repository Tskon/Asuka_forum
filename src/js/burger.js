(function () {
  const burgerBtn = document.querySelector('.mainMenu__burgerBtn');
  const menu = document.querySelector('.menu-main-container');

  document.body.addEventListener('click', e => {
    if (menu.classList.contains('menu-main-container--show')) {
      menu.classList.remove('menu-main-container--show');
    }
  });

  burgerBtn.addEventListener('click', e => {
    setTimeout(() => {
      menu.classList.toggle('menu-main-container--show');
    }, 0);
  })

}());