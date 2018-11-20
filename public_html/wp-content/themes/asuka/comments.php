<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
  return;
}
?>

<div id="comments" class="comments-area">

  <?php if (have_comments()) : ?>
    <h2 class="comments-title">
      <?php
      $comments_number = get_comments_number();
      if ('1' === $comments_number) {
        /* translators: %s: post title */
        printf('Коммент:', get_the_title());
      } else {
        printf('Комменты:', get_the_title());
      }
      ?>
    </h2>

    <?php
    function asuka_comment_nav() {
      // Are there comments to navigate through?
      if (get_comment_pages_count() > 1 && get_option('page_comments')) :
        ?>
        <nav class="navigation comment-navigation" role="navigation">
          <h2 class="screen-reader-text">Навигация по комментариям</h2>
          <div class="nav-links">
            <?php
            if ($prev_link = get_previous_comments_link("Старые комментарии")) :
              printf('<div class="nav-previous">%s</div>', $prev_link);
            endif;

            if ($next_link = get_next_comments_link('Свежие комментарии')) :
              printf('<div class="nav-next">%s</div>', $next_link);
            endif;
            ?>
          </div><!-- .nav-links -->
        </nav><!-- .comment-navigation -->
      <?php
      endif;
    }

    asuka_comment_nav();
    ?>

    <ol class="comment-list">
      <?php
      wp_list_comments(array(
        'style' => 'ol',
        'short_ping' => true,
        'avatar_size' => 56,
      ));
      ?>
    </ol><!-- .comment-list -->

    <?php asuka_comment_nav(); ?>

  <?php endif; // have_comments() ?>

  <?php
  // If comments are closed and there are comments, let's leave a little note, shall we?
  if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
    ?>
    <p class="no-comments"><?php _e('Comments are closed.', 'twentyfifteen'); ?></p>
  <?php endif; ?>

  <?php comment_form(); ?>

</div><!-- .comments-area -->
