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
$locations = get_nav_menu_locations();

if( $locations && isset($locations[ 'header_menu' ]) ){
  wp_enqueue_script('mainMenu', get_template_directory_uri() . '/js/main.js');

  $menu = wp_get_nav_menu_object( $locations[ 'header_menu' ] );
  $menuItems = wp_get_nav_menu_items($menu, array());

  wp_localize_script('mainMenu', 'MainMenuFromWP', $menuItems);
}
?>