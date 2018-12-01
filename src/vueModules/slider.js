import Vue from 'vue/dist/vue.esm.js';

try {
  if (dataFromWP) {
    initVue()
  }
} catch (e) {
  console.log(e.message);
}


function initVue() {
  const vm = new Vue({
      el: document.getElementById('jsAsukaSlider'),
      data: {
        slideCounter: 0,
      },
      computed: {
        slidesLength() {
          return this.posts.length
        },
        posts() {
          return dataFromWP.wp.posts.map((item, i) => {
            item.imgUrl = 'background-image: url(' + this.chooseImg(i) + ')';
            return item
          })
        }
      }
      ,
      methods: {
        chooseImg(i) {
          if (dataFromWP.imgUrl[ i ] === '') {
            return "/wp-content/themes/asuka/img/news_preview/" + this.randomInteger(1, 15) + '.jpg'
          } else {
            return dataFromWP.imgUrl[ i ];
          }
        },
        prevSlide() {
          if (this.slideCounter !== 0) this.slideCounter--;
        },
        nextSlide() {
          if (this.slideCounter !== this.posts.length - 1) this.slideCounter++;
        },
        isNeedToShow(i) {
          return i === this.slideCounter;
        },
        randomInteger(min, max) {
          let rand = min - 0.5 + Math.random() * (max - min + 1);
          rand = Math.round(rand);
          return rand;
        }
      },

      template: `
    <div id="jsAsukaSlider">
      <transition-group name="fade">
        <div class="news" v-for="(post,i) in posts" v-show="isNeedToShow(i)" :key="i">
          <div class="news__previewImg"
               :style="post.imgUrl"
          >
            <div class="news__controls">
              <button
                  @click="prevSlide"
                  :disabled="slideCounter === 0"
              > <<
              </button>
  
              <button
                  @click="nextSlide"
                  :disabled="slideCounter === slidesLength - 1"
              > >>
              </button>
            </div>
          </div>
  
          <div class="news__description">
            <h2>{{ post.post_title }} <span class="news__date">({{ post.post_date.split(' ')[0] }})</span></h2>
            <div v-html="post.post_content"></div>
  
          </div>
        </div>
      </transition-group>
    </div>
`
    })
  ;
}
