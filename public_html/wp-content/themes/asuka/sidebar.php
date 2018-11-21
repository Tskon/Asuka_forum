<main class="indexPage">

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
          <h2>{{ post.post_title }}</h2>
          <div v-html="post.post_content"></div>
        </div>
      </div>
    </transition-group>
  </div>
  </transition>

  <?php
  if (is_active_sidebar('index_widgets')) : ?>
    <div class="contentBlocks">
      <?php dynamic_sidebar('index_widgets'); ?>
    </div>
  <?php endif; ?>
</main>


