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


// передача переменных в js
wp_enqueue_script('slider', get_template_directory_uri() . '/js/main.js');

$wpb_all_query = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 10));
$dataToBePassed = array(
  'wp' => $wpb_all_query,
  'imgUrl' => array()
);

$i = 0;
while ($wpb_all_query->have_posts()) : $wpb_all_query->the_post();
  $thumbnail_attributes = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); // возвращает массив параметров миниатюры
  if ($thumbnail_attributes[0]):
    $dataToBePassed['imgUrl'][$i] = $thumbnail_attributes[0];
  else:
    $dataToBePassed['imgUrl'][$i] = "";
  endif;
  $i++;
endwhile;

wp_localize_script('slider', 'indexSlidesFromWP', $dataToBePassed);

$locations = get_nav_menu_locations();

if( $locations && isset($locations[ 'header_menu' ]) ){
  wp_enqueue_script('mainMenu', get_template_directory_uri() . '/js/main.js');

  $menu = wp_get_nav_menu_object( $locations[ 'header_menu' ] );
  $menuItems = wp_get_nav_menu_items($menu, array());

  wp_localize_script('mainMenu', 'MainMenuFromWP', $menuItems);
}
