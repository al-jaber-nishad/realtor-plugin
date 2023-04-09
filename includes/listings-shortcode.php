<?php

function realtor_plugin_listings_shortcode()
{
  ob_start();
  // Set the number of listings to show per page
  $listings_per_page = 12;

  // Get the current page number
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

  // Calculate the offset
  $offset = ($paged - 1) * $listings_per_page;

  // Get the list of listings
  $args = array(
    'post_type' => 'listing',
    'posts_per_page' => $listings_per_page,
    'offset' => $offset,
    'order' => 'DESC',
    'orderby' => 'date',
  );
  $listings = get_posts($args);

  // API endpoint to get the listings data, 
  // $response = wp_remote_get('esc_url_raw(rest_url('realtor-plugin/v1/listings'))');

  // // Check if the request was successful
  // if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
  //   return '<p>Error retrieving listings.</p>';
  // }

  // // Get the response data as an array of listings
  // $listings = json_decode(wp_remote_retrieve_body($response), true);


  // Get the total number of listings
  $total_listings = wp_count_posts('listing')->publish;

  // Calculate the total number of pages
  $total_pages = ceil($total_listings / $listings_per_page);

?>

  <div class="listing-grid">
    <?php foreach ($listings as $listing) : ?>
      <div class="listing-card">
        <div class="listing-image">
          <a href="<?php echo get_permalink($listing); ?>"><?php echo get_the_post_thumbnail($listing, 'medium'); ?></a>
        </div>
        <div class="listing-details">
          <h2 class="listing-title"><a href="<?php echo get_permalink($listing); ?>"><?php echo get_the_title($listing); ?></a></h2>
          <div class="listing-excerpt"><?php echo get_the_excerpt($listing); ?></div>
          <div class="listing-meta">
            <div class="listing-author">By <?php echo get_the_author_meta('display_name', $listing->post_author); ?></div>
            <div class="listing-date"><?php echo get_the_date('', $listing); ?></div>
            <div class="listing-status"><?php echo get_post_status($listing); ?></div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>


  <div class="pagination">
    <?php
    echo paginate_links(array(
      'base' => get_pagenum_link(1) . '%_%',
      'format' => 'page/%#%',
      'current' => $paged,
      'total' => $total_pages,
      'prev_text' => __('&laquo; Prev'),
      'next_text' => __('Next &raquo;'),
      'type' => 'plain',
      'mid_size' => 1,
      'end_size' => 2,
    ));
    ?>
  </div>


<?php
  return ob_get_clean();
}
add_shortcode('realtor_plugin_listings', 'realtor_plugin_listings_shortcode');

?>