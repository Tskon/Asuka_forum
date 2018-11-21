<main class="indexPage">
  <!--  <div class="news" id="jsAsukaNewsSlider">-->
  <!--    <div class="news__previewImg"></div>-->
  <!--    <div class="news__description" v-show="isNeedToShowNews">-->
  <!--      <h2>Заголовок новости</h2>-->
  <!--      Текст новости <br>-->
  <!--      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus atque eveniet itaque nisi obcaecati-->
  <!--      quasi rem-->
  <!--      veritatis? Alias architecto ducimus id nihil numquam? Ad dolorem eum itaque repellat similique sunt?-->
  <!--    </div>-->
  <!--  </div>-->

  <?php
  // запрос
  $wpb_all_query = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 2)); ?>

  <?php if ($wpb_all_query->have_posts()) : ?>

    <!-- the loop -->
    <?php while ($wpb_all_query->have_posts()) : $wpb_all_query->the_post(); ?>
      <div class="news jsAsukaSlider" style="display: none">
        <div class="news__previewImg"
          <?php
          $thumbnail_attributes = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); // возвращает массив параметров миниатюры
          if ($thumbnail_attributes[0] != false) :
          ?>
             style="background-image: url('<?php
             echo $thumbnail_attributes[0]; // URL миниатюры
             endif;
             ?>')"></div>
        <div class="news__description" v-show="isNeedToShowNews">
          <h2><?php the_title(); ?></h2>
          <?php the_content(); ?>

          <!--            <p><a href="--><?php //the_permalink(); ?><!--">Подробнее</a></p>-->
        </div>
      </div>
    <?php endwhile; ?>
    <!-- end of the loop -->
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

  <?php
  if (is_active_sidebar('index_widgets')) : ?>
    <div class="contentBlocks">
      <?php dynamic_sidebar('index_widgets'); ?>
    </div>
  <?php endif; ?>
</main>


