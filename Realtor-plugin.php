<?php
/*
* Plugin Name: Realtor Plugin
* Description: Handle data listing easily.
* Version: 1.0.0
* Author: Al Jaber Nishad
* Author URI: https://nishad.pythonanywhere.com/
*/


function custom_styles() {
    wp_enqueue_style( 'custom-styles', plugins_url( '/css/styles.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'custom_styles' );


include(plugin_dir_path(__FILE__) . 'includes/WP-list-table.php');
include(plugin_dir_path(__FILE__) . 'includes/Create-post-API.php');
include(plugin_dir_path(__FILE__) . 'includes/Listing_API.php');
include(plugin_dir_path(__FILE__) . 'includes/form-shortcode.php');
include(plugin_dir_path(__FILE__) . 'includes/custom-post-type.php');
include(plugin_dir_path(__FILE__) . 'includes/listings-shortcode.php');


// Adding Assessment to the Menu List
function my_plugin_menu_page()
{
  $page_title = 'Assessment';
  $menu_title = 'Assessment';
  $capability = 'manage_options';
  $menu_slug = 'assessment';
  $function = 'assessment';
  $icon_url = 'dashicons-media-code';
  $position = 20;
  add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
}
add_action('admin_menu', 'my_plugin_menu_page');


// Plugin menu callback function
function assessment()
{
  // Creating an instance
  $table = new Listings_Backend_Table();

  echo '<div class="wrap"><h2>Listings</h2>';
  // Prepare table
  $table->prepare_items();
  // Search form
  $table->search_box('search', 'search_id');
  // Display table
  $table->display();
  echo '</div>';
}





