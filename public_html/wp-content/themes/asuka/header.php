<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta charset="<?php bloginfo('charset'); ?>">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/main.css">
  <?php wp_head(); ?>
</head>
<body>
<div class="headerWrapper">
  <header class="header">

    <a class="logo" href="/">
      <div class="logo__img">&nbsp</div>
      <span class="logo__title">Asuka</span>
    </a>

    <?php if (has_nav_menu('header_menu')) : ?>
      <nav class="mainMenu" role="navigation">
        <?php
        wp_nav_menu(array(
          'theme_location' => 'header_menu',
          'menu_class' => '',
          'depth' => 1,
        ));
        ?>
      </nav>
    <?php endif; ?>

  </header>
</div>
<main class="indexPage">