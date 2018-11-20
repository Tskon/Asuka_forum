<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php
  if (is_single()) :
    the_title('<h1 class="entry-title">', '</h1>');
  else :
    the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
  endif;
  ?>

  <div class="entry-content">
    <?php
    the_content('Продолжить читать');

    wp_link_pages(array(
      'before' => '<div class="page-links"><span class="page-links-title">' . 'Страницы:' . '</span>',
      'after' => '</div>',
      'link_before' => '<span>',
      'link_after' => '</span>',
      'pagelink' => '<span class="screen-reader-text">' . 'Страница:' . ' </span>%',
      'separator' => '<span class="screen-reader-text">, </span>',
    ));
    ?>
  </div><!-- .entry-content -->
  <?php edit_post_link('Редактировать', '<div class="edit-link">', '</div>'); ?>

</article><!-- #post-## -->
