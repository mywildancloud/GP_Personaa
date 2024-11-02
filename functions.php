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

// * Remove submenu patterns *//
function myw_remove_appearance_submenus() {
    remove_submenu_page('themes.php', 'site-editor.php?path=/patterns');
}
add_action('admin_menu', 'myw_remove_appearance_submenus', 999);

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

/* Customize Hamburger Menu */ 
add_filter( 'generate_svg_icon', function( $output, $icon ) {
    if ( 'menu-bars' === $icon ) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
  <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
</svg>';
        return sprintf(
            '<span class="gp-icon %1$s">
                %2$s
            </span>',
            $icon,
            $svg
        );
    }
    return $output;
}, 15, 2 );

/* add dark/light mode switcher */
add_action('wp_footer', function() {
?>
<script>
	function colorModeSwitcher() {
	  var element = document.getElementById("c-light-mode");
  	document.body.classList.toggle("light");
		}
		// Dark Mode
	var cookieStorage = {
	    setCookie: function setCookie(key, value, time, path) {
	        var expires = new Date();
	        expires.setTime(expires.getTime() + time);
	        var pathValue = '';
	        if (typeof path !== 'undefined') {
	            pathValue = 'path=' + path + ';';
	        }
	        document.cookie = key + '=' + value + ';' + pathValue + 'expires=' + expires.toUTCString();
	    },
	    getCookie: function getCookie(key) {
	        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
	        return keyValue ? keyValue[2] : null;
	    },
	    removeCookie: function removeCookie(key) {
	        document.cookie = key + '=; Max-Age=0; path=/';
	    }
	};

	jQuery('.c-light-mode').click(function() {
	    //Show either moon or sun
	    jQuery('.c-light-mode').toggleClass('active');
	    //If dark mode is selected
	    if (jQuery('.c-light-mode').hasClass('active')) {
	        //Add dark mode class to the body
	        jQuery('body').addClass('light');
	        cookieStorage.setCookie('dark', 'true', 2628000000, '/');
	    } else {
	        jQuery('body').removeClass('light');
	        setTimeout(function() {
	            cookieStorage.removeCookie('dark');
	        }, 100);
	    }
	})

	//Check Storage. Display user preference 
	if (cookieStorage.getCookie('dark')) {
	    jQuery('body').addClass('light');
	    jQuery('.c-light-mode').addClass('active');
	}
	// End Dark Mode
</script>
<?php
});