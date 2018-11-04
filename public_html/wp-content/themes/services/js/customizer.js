( function( $ ) {
"use strict";
wp.customize( 'services_accent_color', function( value ) {
value.bind( function( to ) {
$( '#container a, #container h1, h2, h3, h4, h5, h6, #container h1 a, h2 a, h3 a, h4 a, h5 a, h6 a, pre, code' ).css( 'color', to );
$( 'hr, .button, button, input[type="submit"], .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover' ).css( 'background-color', to );
$( 'blockquote, #content .gallery img, #menu .current-menu-item a, #menu .current_page_parent a, .box, .box-2, .box-3, .box-4, .box-5, .box-6, .box-1-3, .box-2-3' ).css( 'border-color', to );
} );
} );
wp.customize( 'services_link_color', function( value ) {
value.bind( function( to ) {
$( '#container a' ).css( 'color', to );
} );
} );
wp.customize( 'services_header_color', function( value ) {
value.bind( function( to ) {
$( '#container h1, h2, h3, h4, h5, h6, #container h1 a, h2 a, h3 a, h4 a, h5 a, h6 a' ).css( 'color', to );
} );
} );
wp.customize( 'services_header_font', function( value ) {
value.bind( function( to ) {
$( '#container h1, h2, h3, h4, h5, h6, #container h1 a, h2 a, h3 a, h4 a, h5 a, h6 a' ).css( 'font-family', to );
} );
} );
} )( jQuery );