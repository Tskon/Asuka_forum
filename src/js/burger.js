import Vue from 'vue/dist/vue.esm.js';

let mainMenuFromWP = [];
try {
  if (dataFromWP.mainMenu.length) {
    mainMenuFromWP = dataFromWP.mainMenu;
  }
} catch (e) {
  // console.log(e.message)
}

new Vue({
  el: document.querySelector(".mainMenu"),
  data: {
    menuList: mainMenuFromWP,
    isOpen: false,
    isBurger: false,
    windowWidth: window.innerWidth
  },
  methods: {
    isActive(url){
      if (url === location.href || url === location.pathname) return 'active';
    }
  },
  mounted(){
    window.onResize = () => {
      this.windowWidth = window.innerWidth;
    }
  },
  template: `
    <ul class="mainMenu">
      <li class="menu-item" 
        v-for="item in menuList"
        :class="isActive(item.url)"
        >
        <a :href="item.url">{{ item.title }}</a>
      </li>  
    </ul>
  `
});


