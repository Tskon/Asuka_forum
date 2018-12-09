import Vue from 'vue/dist/vue.esm.js';

let mainMenuFromWP = [];
try {
  if (dataFromWP.mainMenu.length) {
    mainMenuFromWP = dataFromWP.mainMenu;
  }
} catch (e) {
  console.log(e.message)
}

new Vue({
  el: document.querySelector(".mainMenu"),
  data: {
    menuList: mainMenuFromWP,
    isOpen: false,
    windowWidth: window.innerWidth,
    htx: false
  },
  computed: {
    isBurger() {
      return this.windowWidth < 650;
    }
  },
  methods: {
    isActive(url) {
      if (url === location.pathname) {
        return 'active';
      } else if(location.href.includes(url) && url !== '/' && location.href !== 'https://eva-wows.ru/forum/forum-group/verbovochnyj-punkt/'){
        console.log(url)
        return 'active';
      } else if(url === '/forum/forum-group/verbovochnyj-punkt/' && location.href.includes(url)){
        return 'active';
      }
    },
    getWidth() {
      this.windowWidth = window.innerWidth;
    },
    burgerClick(){
      this.htx = !this.htx;
      this.isOpen = !this.isOpen;
      document.body.style.overflowY = (this.isOpen) ? 'hidden' : '';
    }
  },
  mounted() {
    window.addEventListener('resize', e => {
      this.getWidth();
    });
  },
  template: `
   <nav class="mainMenu">
    <div 
    v-if="isBurger"
    class="mi-burger mi-burger--htra" 
    :class="{ active: htx }" 
    @click="burgerClick">
      <span>htx</span>
    </div>
    <ul 
      class="mainMenu__list"
      v-if="!isBurger || isOpen">
      <li class="menu-item" 
        v-for="item in menuList"
        :class="isActive(item.url)"
        >
        <a :href="item.url">{{ item.title }}</a>
      </li>  
    </ul>
  </nav>
  `
});