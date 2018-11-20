<?php
/**
 * The template for displaying link post formats
 * Used for both single and index/archive/search.
 */

function asuka_get_link_url() {
  $has_url = get_url_in_content(get_the_content());
  return $has_url ? $has_url : apply_filters('the_permalink', get_permalink());
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header>
    <?php
    if (is_single()) :
      the_title(sprintf('<h1><a href="%s">', esc_url(asuka_get_link_url())), '</a></h1>');
    else :
      the_title(sprintf('<h2><a href="%s">', esc_url(asuka_get_link_url())), '</a></h2>');
    endif;
    ?>
  </header>
  <!-- .entry-header -->

  <div class="entry-content">
    <?php
    /* translators: %s: Name of current post */
    the_content(sprintf(
      'Продолжить читать',
      the_title('<span>', '</span>', false)
    ));

    wp_link_pages(array(
      'before' => '<div><span>' . 'Страницы:' . '</span>',
      'after' => '</div>',
      'link_before' => '<span>',
      'link_after' => '</span>',
      'pagelink' => '<span>' . 'Страница' . ' </span>%',
      'separator' => '<span>, </span>',
    ));
    ?>
  </div>
  <!-- .entry-content -->

  <?php
  // Author bio.
  if (is_single() && get_the_author_meta('description')) :
    get_template_part('author-bio');
  endif;

  edit_post_link('Редактировать', '<span class="edit-link">', '</span>');
  ?>
</article><!-- #post-## -->
