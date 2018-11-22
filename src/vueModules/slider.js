import Vue from 'vue/dist/vue.esm.js';

const vm = new Vue({
  el: document.getElementById('jsAsukaSlider'),
  data: {
    slideCounter: 0,
    // posts: indexSlidesFromWP.wp.posts
  },
  computed: {
    slidesLength() {
      return this.posts.length
    },
    posts(){
      return indexSlidesFromWP.wp.posts.map((item,i)=>{
        item.imgUrl = 'background-image: url(' + this.chooseImg(i) + ')';
        return item
      })
    }
  }
  ,
  methods: {
    chooseImg(i){
      if (indexSlidesFromWP.imgUrl[i] === ''){
        return "/wp-content/themes/asuka/img/news_preview/" + this.randomInteger(1,8) + '.jpg'
      } else {
        return indexSlidesFromWP.imgUrl[i];
      }
    },
    prevSlide() {
      if (this.slideCounter !== 0) this.slideCounter--;
    },
    nextSlide() {
      if (this.slideCounter !== this.posts.length - 1) this.slideCounter++;
    },
    isNeedToShow(i){
      return i === this.slideCounter
    },
    randomInteger(min, max) {
      let rand = min - 0.5 + Math.random() * (max - min + 1);
      rand = Math.round(rand);
      return rand;
    }
  }
});

// "/wp-content/themes/asuka/img/news_preview/"