<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: justg
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

 function vsstem_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
	    get_template_part('modul/gallery-carousel/gallery-carousel');
	    get_template_part('modul/v-basic/v-basic');
	    get_template_part('modul/v-testimoni/v-testimoni');
	}
}
add_action( 'init', 'vsstem_modul' );

 $inc = get_stylesheet_directory() . '/inc';
 $includes = [
	'breadcrumb.php',
	'enqueue.php',
	'function-child.php',
	'shortcodes.php'
 ];

 foreach( $includes as $include ) {
	 require_once( $inc . '/' . $include );
 }