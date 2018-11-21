import Vue from 'vue/dist/vue.esm.js';

const vm = new Vue({
  el: '#jsAsukaSlider',
  data: {
    slideCounter: 0,
    slides: document.querySelectorAll('#jsAsukaSlider .news')
  },
  computed: {
    slidesLength() {
      return this.slides.length
    }
  }
  ,
  methods: {
    prevSlide() {
      if (this.slideCounter !== 0) this.slideCounter--;
      showCurrentSlide();
    },
    nextSlide() {
      if (this.slideCounter !== this.slides.length - 1) this.slideCounter++;
      showCurrentSlide();
    },
  },
  created() {
    this.slides.forEach((slide, i) => {
      if (i === this.slideCounter) {
        slide.style.display = 'flex';
      } else {
        slide.style.display = 'none';
      }
    })
  },

});

const slides = document.querySelectorAll('#jsAsukaSlider .news');

function showCurrentSlide() {
  slides.forEach((slide, i) => {
    if (i === vm.slideCounter) {
      slide.style.display = 'flex';
    } else {
      slide.style.display = 'none';
    }
  })
}