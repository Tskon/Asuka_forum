<main class="indexPage">
  <?php
  // запрос
  $wpb_all_query = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 2)); ?>

  <?php if ($wpb_all_query->have_posts()) : ?>
    <div id="jsAsukaSlider">
      <!-- the loop -->
      <?php $id = 0;
      while ($wpb_all_query->have_posts()) : $wpb_all_query->the_post(); ?>
        <div class="news">
          <div class="news__previewImg"
            <?php
            $thumbnail_attributes = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); // возвращает массив параметров миниатюры
            ?>
               style="background-image: url('<?php
               echo ($thumbnail_attributes[0]) ? $thumbnail_attributes[0] : bloginfo('template_url')."/img/news_preview/first-news.jpg"; // URL миниатюры
               ?>')"
          ></div>
          <div class="news__description">
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>

            <!--            <p><a href="--><?php //the_permalink(); ?><!--">Подробнее</a></p>-->
          </div>
        </div>
      <?php endwhile; ?>
      <!-- end of the loop -->
    </div>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

  <?php
  if (is_active_sidebar('index_widgets')) : ?>
    <div class="contentBlocks">
      <?php dynamic_sidebar('index_widgets'); ?>
    </div>
  <?php endif; ?>
</main>


