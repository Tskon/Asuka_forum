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
    windowWidth: window.innerWidth
  },
  computed: {
    isBurger(){
      return this.windowWidth < 600;
    }
  },
  methods: {
    isActive(url){
      if (url === location.href || url === location.pathname) return 'active';
    },
    getWidth(){
      this.windowWidth = window.innerWidth;
    }
  },
  mounted() {
    window.addEventListener('resize', e => {
      this.getWidth();
    });
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


