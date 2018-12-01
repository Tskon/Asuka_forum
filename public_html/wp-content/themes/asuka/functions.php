<?php
function asuka_setup() {
  add_theme_support('title-tag');

  /*
   * Enable support for Post Thumbnails on posts and pages.
   * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
   */
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(600, 360, true);

  register_nav_menus(array(
    'header_menu' => 'Меню в шапке',
//      'social'  => __( 'Social Links Menu', 'twentyfifteen' ),
  ));

  add_theme_support('html5', array(
    'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
  ));

  /*
   * Enable support for Post Formats.
   * See: https://codex.wordpress.org/Post_Formats
   */
  add_theme_support('post-formats', array(//      'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
  ));
}

add_action('after_setup_theme', 'asuka_setup');

function asuka_widgets_init() {
  register_sidebar(array(
    'name' => 'Виджеты на главной странице',
    'id' => 'index_widgets',
    'before_widget' => '<div class="contentBlocks__block">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
  ));
}

add_action('widgets_init', 'asuka_widgets_init');
