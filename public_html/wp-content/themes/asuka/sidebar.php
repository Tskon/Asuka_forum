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
          <h2>{{ post.post_title }} <span class="news__date">({{ post.post_date.split(' ')[0] }})</span></h2>
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

<?php
$locations = get_nav_menu_locations();

if( $locations && isset($locations[ 'header_menu' ]) ){
  wp_enqueue_script('mainMenu', get_template_directory_uri() . '/js/main.js');

  $menu = wp_get_nav_menu_object( $locations[ 'header_menu' ] );
  $menuItems = wp_get_nav_menu_items($menu, array());

  wp_localize_script('mainMenu', 'MainMenuFromWP', $menuItems);
}
?>
