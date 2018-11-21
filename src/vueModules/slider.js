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
        item.imgUrl = 'background-image: url(' + indexSlidesFromWP.imgUrl[i] + ')';
        return item
      })
    }
  }
  ,
  methods: {
    prevSlide() {
      if (this.slideCounter !== 0) this.slideCounter--;
    },
    nextSlide() {
      if (this.slideCounter !== this.posts.length - 1) this.slideCounter++;
    },
    isNeedToShow(i){
      return i === this.slideCounter
    }
  }
});