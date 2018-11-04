<?php
add_action( 'after_setup_theme', 'services_setup' );
function services_setup()
{
load_theme_textdomain( 'services', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-logo' );
$defaults = array( 'header-text' => false );
add_theme_support( 'custom-header', $defaults );
add_theme_support( 'custom-background' );
add_theme_support( 'html5', array( 'search-form' ) );
global $content_width;
if ( ! isset( $content_width ) ) $content_width = 1920;
register_nav_menus(
array( 'main-menu' => esc_html__( 'Main Menu', 'services' ), 'footer-menu' => esc_html__( 'Footer Menu', 'services' ) )
);
}
add_action( 'after_setup_theme', 'services_woocommerce_support' );
function services_woocommerce_support() {
add_theme_support( 'woocommerce' );
}
require_once ( get_template_directory() . '/about.php' );
add_action( 'wp_enqueue_scripts', 'services_load_scripts' );
function services_load_scripts()
{
wp_enqueue_style( 'services-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
wp_register_script( 'services-videos', get_template_directory_uri() . '/js/videos.js' );
wp_enqueue_script( 'services-videos' );
wp_add_inline_script( 'services-videos', 'jQuery(document).ready(function($){$("#wrapper").vids();});' );
}
add_action( 'wp_footer', 'services_footer_scripts' );
function services_footer_scripts() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
$(":checkbox").on("click", function() {
$(this).parent().toggleClass("checked");
});
});
</script>
<?php
}
add_action( 'wp_footer', 'services_bbpress_inline_script' );
function services_bbpress_inline_script() {
if ( class_exists( 'bbPress' ) && bbp_is_single_forum() ) {
?>
<script>
jQuery(document).ready(function($){
if(!$('#new-post').length > 0){
$('#new-topic').hide();
}
});
</script>
<?php
}
}
add_filter( 'document_title_separator', 'services_document_title_separator' );
function services_document_title_separator( $sep ) {
$sep = "|";
return $sep;
}
add_filter( 'the_title', 'services_title' );
function services_title( $title ) {
if ( $title == '' ) {
return '&rarr;';
} else {
return $title;
}
}
function services_read_more_link() {
if ( ! is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">...</a>';
}
}
add_filter( 'the_content_more_link', 'services_read_more_link' );
function services_excerpt_read_more_link( $more ) {
if ( ! is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">...</a>';
}
}
add_filter( 'excerpt_more', 'services_excerpt_read_more_link' );
add_action( 'widgets_init', 'services_widgets_init' );
function services_widgets_init()
{
register_sidebar( array (
'name' => esc_html__( 'Header Widget Area', 'services' ),
'id' => 'header-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => "</li>",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array (
'name' => esc_html__( 'Footer Widget Area', 'services' ),
'id' => 'footer-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => "</li>",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array (
'name' => esc_html__( 'Sidebar Widget Area', 'services' ),
'description' => esc_html__( 'Does not display for single posts.', 'services' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => "</li>",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'services_pingback_header' );
function services_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'services_enqueue_comment_reply_script' );
function services_enqueue_comment_reply_script()
{
if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
function services_custom_pings( $comment )
{
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php
}
add_filter( 'get_comments_number', 'services_comment_count', 0 );
function services_comment_count( $count ) {
if ( ! is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}
function services_customizer( $wp_customize ) {
$wp_customize->add_setting(
'services_accent_color',
array(
'default' => '#00b4ff',
'sanitize_callback' => 'sanitize_hex_color',
'transport' => 'postMessage'
)
);
$wp_customize->add_control(
new WP_Customize_Color_Control(
$wp_customize,
'accent_color',
array(
'label' => esc_html__( 'Theme Accent Color', 'services' ),
'section' => 'colors',
'settings' => 'services_accent_color'
)
)
);
$wp_customize->add_setting(
'services_link_color',
array(
'default' => '#00b4ff',
'sanitize_callback' => 'sanitize_hex_color',
'transport' => 'postMessage'
)
);
$wp_customize->add_control(
new WP_Customize_Color_Control(
$wp_customize,
'link_color',
array(
'label' => esc_html__( 'Link Color', 'services' ),
'section' => 'colors',
'settings' => 'services_link_color'
)
)
);
$wp_customize->add_setting(
'services_header_color',
array(
'default' => '#00b4ff',
'sanitize_callback' => 'sanitize_hex_color',
'transport' => 'postMessage'
)
);
$wp_customize->add_control(
new WP_Customize_Color_Control(
$wp_customize,
'header_color',
array(
'label' => esc_html__( 'Header Text Color', 'services' ),
'section' => 'colors',
'settings' => 'services_header_color'
)
)
);
$wp_customize->add_section(
'services_fonts',
array(
'title' => 'Fonts',
'priority' => 25
)
);
$wp_customize->add_setting(
'services_header_font',
array(
'default' => 'Helvetica',
'sanitize_callback' => 'sanitize_text_field',
'transport' => 'postMessage'
)
);
$wp_customize->add_control(
new WP_Customize_Control(
$wp_customize,
'header_font',
array(
'label' => esc_html__( 'Header Text Font', 'services' ),
'description' => esc_html__( 'If adding a Google font, make sure to capitalize all words, save, and then refresh to preview.', 'services' ),
'section' => 'services_fonts',
'settings' => 'services_header_font'
)
)
);
}
add_action( 'customize_register', 'services_customizer', 20 );
function services_customizer_css() {
?>
<style type="text/css">
a, h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a, pre, code{color:<?php echo esc_html( get_theme_mod( 'services_accent_color' ) ); ?>}
hr, .button, button, input[type="submit"], .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover{background-color:<?php echo esc_html( get_theme_mod( 'services_accent_color' ) ); ?>}
blockquote, #content .gallery img, .box, .box-2, .box-3, .box-4, .box-5, .box-6, .box-1-3, .box-2-3{border-color:<?php echo esc_html( get_theme_mod( 'services_accent_color' ) ); ?>}
@media all and (min-width:769px){#menu .current-menu-item a, #menu .current_page_parent a{border-color:<?php echo esc_html( get_theme_mod( 'services_accent_color' ) ); ?>}}
a{color:<?php echo esc_html( get_theme_mod( 'services_link_color' ) ); ?>}
h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a{font-family:"<?php echo esc_html( str_replace( '+', ' ', get_theme_mod( 'services_header_font' ) ) ); ?>";color:<?php echo esc_html( get_theme_mod( 'services_header_color' ) ); ?>}
</style>
<?php
}
add_action( 'wp_head', 'services_customizer_css' );
function services_customizer_preview() {
wp_enqueue_script(
'services-theme-customizer',
get_template_directory_uri() . '/js/customizer.js',
array( 'jquery', 'customize-preview' ),
'0.3.0',
true
);
}
add_action( 'customize_preview_init', 'services_customizer_preview' );
function services_customizer_fonts_preview() {
wp_enqueue_style( 'services-google-fonts', 'https://fonts.googleapis.com/css?family=' . esc_html( ucwords( str_replace( ' ', '+', get_theme_mod( 'services_header_font' ) ) ) ) . '' );
}
add_action( 'customize_preview_init', 'services_customizer_fonts_preview' );
add_action( 'wp_enqueue_scripts', 'services_customizer_fonts_preview' );