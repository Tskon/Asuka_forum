<?php
function asuka_setup() {
    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 600, 360, true );

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus( array(
      'header_menu' => 'Меню в шапке',
//      'social'  => __( 'Social Links Menu', 'twentyfifteen' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
      'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     */
    add_theme_support( 'post-formats', array(
      'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
    ) );

    /*
     * Enable support for custom logo.
     *
     * @since Twenty Fifteen 1.5
     */
    add_theme_support( 'custom-logo', array(
      'height'      => 248,
      'width'       => 248,
      'flex-height' => true,
    ) );

//    $color_scheme  = twentyfifteen_get_color_scheme();
//    $default_color = trim( $color_scheme[0], '#' );

    // Setup the WordPress core custom background feature.

    /**
     * Filter Twenty Fifteen custom-header support arguments.
     *
     * @since Twenty Fifteen 1.0
     *
     * @param array $args {
     *     An array of custom-header support arguments.
     *
     *     @type string $default-color     		Default color of the header.
     *     @type string $default-attachment     Default attachment of the header.
     * }
     */
    add_theme_support( 'custom-background', apply_filters( 'twentyfifteen_custom_background_args', array(
      'default-color'      => $default_color,
      'default-attachment' => 'fixed',
    ) ) );

    // Indicate widget sidebars can use selective refresh in the Customizer.
    add_theme_support( 'customize-selective-refresh-widgets' );
  }

add_action( 'after_setup_theme', 'asuka_setup' );

function asuka_widgets_init() {
  register_sidebar( array(
    'name'          => 'index_widgets',
    'id'            => 'index_widgets',
//    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//    'after_widget'  => '</aside>',
//    'before_title'  => '<h2 class="widget-title">',
//    'after_title'   => '</h2>',
  ) );
}
add_action( 'widgets_init', 'asuka_widgets_init' );