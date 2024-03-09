<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file. 
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

function generatepress_child_enqueue_scripts() {
	if ( is_rtl() ) {
		wp_enqueue_style( 'generatepress-rtl', trailingslashit( get_template_directory_uri() ) . 'rtl.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 100 );

/* Enqueue Child Theme style.css to editor */
add_filter('block_editor_settings_all', function($editor_settings) {
    // Get the URL of the child theme's style.css
    $child_theme_style_url = get_stylesheet_directory_uri() . '/style.css';

    $editor_settings['styles'][] = array('css' => wp_remote_get($child_theme_style_url)['body']);
    return $editor_settings;
});

/* Eunqueue Customizer CSS to editor */ 
add_filter( 'block_editor_settings_all', function( $editor_settings ) {
    $css = wp_get_custom_css_post()->post_content;
    $editor_settings['styles'][] = array( 'css' => $css );
    return $editor_settings;
} );

/* Remove WordPress Core default block patterns */
add_action( 'after_setup_theme', 'my_remove_patterns' );
function my_remove_patterns() {
   remove_theme_support( 'core-block-patterns' );
}

/* Patterns accessible in backend */
function be_reusable_blocks_admin_menu() {
    add_menu_page( 'Patterns', 'Patterns', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-layout', 22 );
}
add_action( 'admin_menu', 'be_reusable_blocks_admin_menu' );
