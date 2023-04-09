<?php 

function create_custom_post_type()
{
  $labels = array(
    'name' => 'Listings',
    'singular_name' => 'Listing',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Listing',
    'edit_item' => 'Edit Listing',
    'new_item' => 'New Listing',
    'view_item' => 'View Listing',
    'search_items' => 'Search Listings',
    'not_found' => 'No listings found',
    'not_found_in_trash' => 'No listings found in trash',
    'parent_item_colon' => '',
    'menu_name' => 'Listings'
  );

  $args = array(
    'labels' => $labels,
    'description' => 'A custom post type for realtor listings',
    'public' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-admin-home',
    'supports' => array('title', 'editor', 'thumbnail', 'author', 'custom-fields'),
    'has_archive' => true,
    'rewrite' => array('slug' => 'listings'),
    'show_in_rest' => true,
    'show_in_menu' => true,
    'rest_base' => 'listings',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'capability_type' => 'listing',
    'map_meta_cap' => true,
    'capabilities' => array(
      'publish_posts' => 'publish_listings',
      'edit_posts' => 'edit_listings',
      'edit_others_posts' => 'edit_others_listings',
      'delete_posts' => 'delete_listings',
      'delete_others_posts' => 'delete_others_listings',
      'read_private_posts' => 'read_private_listings',
      'edit_post' => 'edit_listing',
      'delete_post' => 'delete_listing',
      'read_post' => 'read_listing'
    )
  );

  register_post_type('listing', $args);
}

add_action('init', 'create_custom_post_type');

?>