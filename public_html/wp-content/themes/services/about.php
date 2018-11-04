<?php
add_action( 'admin_menu', 'services_add_page' );
function services_add_page()
{
global $services_theme_page;
$services_theme_page = add_theme_page( esc_html__( 'Services', 'services' ), esc_html__( 'Services', 'services' ), 'edit_theme_options', 'theme_options', 'services_options_do_page' );
}
function services_options_do_page()
{
?>
<div class="wrap">
<?php global $services_theme_page; ?>
<?php $current_theme = wp_get_theme(); ?>
<h1><?php printf( esc_html__( 'Services', 'services' ) ); ?></h1>
<p><?php printf( esc_html__( 'Thank you for choosing Services.', 'services' ) ); ?></p>
<p><?php printf( esc_html__( 'You can customize Services under %1$sAppearance%2$s > %1$sCustomize%2$s.', 'services' ), '<em>', '</em>' ); ?></p>
<p><?php printf( esc_html__( 'If at any point you need assistance with Services, please visit the %1$ssupport forum%2$s.', 'services' ), '<a href="https://serviceswp.co/forum/" target="_blank">', '</a>' ); ?></p>
<p><?php printf( esc_html__( 'Looking for more features and support? %1$sUpgrade to Pro%2$s', 'services' ), '<a href="https://serviceswp.co/#choices" target="_blank" class="button-primary">', '</a>' ); ?></p>
</div>
<?php
}