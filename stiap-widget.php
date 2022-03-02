<?php
/**
 * * Plugin Name
 *
 * @package    Show Thumbnail Image In Admin Post / Page List (STIAP Plugin)
 * @author     Bjorn Inge Vaarvik
 * @copyright  2022 Bjorn Inge Vaarvik
 * @license    GPL-3.0-or-later
 * 
 * @Wordpress-plugin
 * 
 * Plugin Name: Show Thumbnail Image In Admin Post / Page List (STIAP Plugin)
 * Plugin URI: http://www.vaarvik.com/stiap-plugin
 * Description: Show featured thumbnail image from post and page in the post / page list in admin area.
 * Version: 1.0
 * Author: Bjorn Inge Vaarvik
 * Author URI: http://www.vaarvik.com
 * Text domain:  stiap-lang
 * Domain path:  /languages
 */


/**
 * Load plugin textdomain.
 */
function stiapwidget_init() {
    load_plugin_textdomain( 'stiap-lang', false, dirname(plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'stiapwidget_init' );



/*
 * ---------------------------------- *
 * constants
 * ---------------------------------- *
*/

if ( ! defined( 'STIAP_PLUGIN_DIR' ) ) {
	define( 'STIAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'STIAP_PLUGIN_URL' ) ) {
	define( 'STIAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}



/* 
 * ------------------------------------------------------------- *
 * Show featured thumbnail image column to post in Admin Screen
 * ------------------------------------------------------------- *
 */

// Set thumbnail size
add_image_size( 'stiap_admin-featured-image', 60, 60, false );

// Add the posts and pages columns filter. Same function for both.
add_filter('manage_posts_columns', 'stiap_add_thumbnail_column', 2);
add_filter('manage_pages_columns', 'stiap_add_thumbnail_column', 2);
function stiap_add_thumbnail_column($stiap_columns){
  $stiap_columns['stiap_thumb'] = __('Image');
  return $stiap_columns;
}
 

// Add featured image thumbnail to the WP Admin table.
add_action('manage_posts_custom_column', 'stiap_show_thumbnail_column', 5, 2);
add_action('manage_pages_custom_column', 'stiap_show_thumbnail_column', 5, 2);
function stiap_show_thumbnail_column($stiap_columns, $stiap_id){
  switch($stiap_columns){
    case 'stiap_thumb':
    if( function_exists('the_post_thumbnail') )
      echo the_post_thumbnail( 'stiap_admin-featured-image' );
    break;
  }
}


// Move the new column at the first place.
add_filter('manage_posts_columns', 'stiap_column_order');
function stiap_column_order($columns) {
  $n_columns = array();
  $move = 'stiap_thumb'; // which column to move
  $before = 'title'; // move before this column

  foreach($columns as $key => $value) {
    if ($key==$before){
      $n_columns[$move] = $move;
    }
    $n_columns[$key] = $value;
  }
  return $n_columns;
}


// Format the column width with CSS
add_action('admin_head', 'stiap_add_admin_styles');
function stiap_add_admin_styles() {
  echo '<style>.column-stiap_thumb {width: 60px;}</style>';
}


// Add shortcode
add_shortcode('stiap-plugin', 'stiap_output_plugin'); 
// Enable shortcode execution inside text widgets
add_filter('widget_text', 'do_shortcode');







?>