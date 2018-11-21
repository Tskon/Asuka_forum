import Vue from 'vue/dist/vue.esm.js';

new Vue({
  el: '#jsAsukaSlider',
  data: {
    slideCounter: 0
  },
  computed: {
    slides(){
      return document.querySelectorAll('#jsAsukaSlider .news')
    },
  },
  methods:{
    showCurrentSlide(){
      this.slides.forEach((slide, i) => {
        if (i === this.slideCounter) slide.style.display = 'flex';
      })
    }
  },
  created(){
    this.showCurrentSlide();
  },
  updated(){
    this.showCurrentSlide();
  }
});