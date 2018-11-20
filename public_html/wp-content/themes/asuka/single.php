<?php
/**
 * The template for displaying all single posts and attachments
 */

get_header(); ?>
<main>
  <?php
  // Start the loop.
  while (have_posts()) : the_post();

    /*
     * Include the post format-specific template for the content. If you want to
     * use this in a child theme, then include a file called content-___.php
     * (where ___ is the post format) and that will be used instead.
     */
    get_template_part('content', get_post_format());

    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) :
      comments_template();
    endif;

    // Previous/next post navigation.
    the_post_navigation(array(
      'next_text' => '<span aria-hidden="true">Следующий</span> ' .
        '<span>Следующий пост:</span> ' .
        '<span>%title</span>',
      'prev_text' => '<span aria-hidden="true">Предыдущий</span> ' .
        '<span>Предыдущий пост:</span> ' .
        '<span>%title</span>',
    ));

    // End the loop.
  endwhile;
  ?>
</main>
<?php get_footer(); ?>
