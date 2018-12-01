<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta charset="<?php bloginfo('charset'); ?>">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/main.css">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="headerWrapper">
  <header class="header">

    <a class="logo" href="/">
      <div class="logo__img">&nbsp</div>
      <span class="logo__title">Asuka</span>
    </a>

    <nav class="mainMenu"></nav>

  </header>
</div>
<main class="indexPage">

<?php

// передача переменных в js
wp_enqueue_script('dataFromWP', get_template_directory_uri() . '/js/main.js');

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

$locations = get_nav_menu_locations();

if( $locations && isset($locations[ 'header_menu' ]) ){
  wp_enqueue_script('mainMenu', get_template_directory_uri() . '/js/main.js');

  $menu = wp_get_nav_menu_object( $locations[ 'header_menu' ] );
  $menuItems = wp_get_nav_menu_items($menu, array());

  $dataToBePassed['mainMenu'] = $menuItems;

//  wp_localize_script('mainMenu', 'MainMenuFromWP', $menuItems);
}

wp_localize_script('dataFromWP', 'dataFromWP', $dataToBePassed);
?>