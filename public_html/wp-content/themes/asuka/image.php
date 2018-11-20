<?php
/**
 * The template for displaying image attachments
 */

get_header(); ?>
<main>
  <?php
  // Start the loop.
  while (have_posts()) : the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <nav id="image-navigation">
        <div>
          <div><?php previous_image_link(false, 'Предыдущее изображение'); ?></div>
          <div><?php next_image_link(false, 'Следующее изображение'); ?></div>
        </div>
      </nav>

      <header>
        <?php the_title('<h1>', '</h1>'); ?>
      </header>

      <div>

        <div>
          <?php
          echo wp_get_attachment_image(get_the_ID(), 150);
          ?>

          <?php if (has_excerpt()) : ?>
            <div>
              <?php the_excerpt(); ?>
            </div>
          <?php endif; ?>

        </div>

        <?php
        the_content();
        wp_link_pages(array(
          'before' => '<div>' . 'Страницы:',
          'after' => '</div>',
          'link_before' => '<span>',
          'link_after' => '</span>',
          'pagelink' => '<span>' . 'Страница' . ' </span>%',
          'separator' => '<span>, </span>',
        ));
        ?>
      </div>

      <footer class="entry-footer">
        <?php twentyfifteen_entry_meta(); ?>
        <?php edit_post_link('Редактировать', '<span>', '</span>'); ?>
      </footer>

    </article><!-- #post-## -->

    <?php
    // If comments are open or we have at least one comment, load up the comment template
    if (comments_open() || get_comments_number()) :
      comments_template();
    endif;

    // Previous/next post navigation.
    the_post_navigation(array(
      'prev_text' => 'Published in Parent post link',
    ));

    // End the loop.
  endwhile;
  ?>

</main>

<?php get_footer(); ?>
