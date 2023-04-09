<?php


// Register the REST API endpoint
add_action('rest_api_init', function () {
  register_rest_route('realtor-plugin/v1', 'listings', array(
    'methods' => 'GET',
    'callback' => 'realtor_plugin_listings',
  ));
});


function realtor_plugin_listings(WP_REST_Request $request)
{
  // Query the listings
  $args = array(
    'post_type' => 'listing',
    'posts_per_page' => $listings_per_page,
    'offset' => $offset,
    'order' => 'DESC',
    'orderby' => 'date',
  );
  $listings = get_posts($args);

  // Reset the post data
  wp_reset_postdata();

  // Return the listings
  return $listings;
}
