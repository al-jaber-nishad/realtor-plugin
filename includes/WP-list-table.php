<?php

if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH.'/wp-admin/includes/class-wp-list-table.php');
}

// Register the WP List Table for the submitted listings
class Listings_Backend_Table extends WP_List_Table
{
  // Define table columns
  function get_columns()
  {
    $columns = array(
      'cb' => '<input type="checkbox" />',
      'title' => 'Title',
      'author' => 'Author',
      'status' => 'Status',
      'date' => 'Date',
      'image' => 'Image',
    );
    return $columns;
  }
  // Define the shortable columns
  protected function get_sortable_columns()
  {
    $sortable_columns = array(
      'title'  => array('title', false),
      'author' => array('author', false),
      'date'   => array('date', true)
    );
    return $sortable_columns;
  }
  // Sorting function
  function usort_reorder($a, $b)
  {
    // If no sort, default to user_login
    $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'user_login';

    // If no order, default to asc
    $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';

    // Determine sort order
    $result = strcmp($a[$orderby], $b[$orderby]);

    // Send final sort direction to usort
    return ($order === 'desc') ? $result : -$result;
  }


  function column_default($item, $column_name)
  {
    switch ($column_name) {
      case 'id':
      case 'title':
      case 'author':
      case 'status':
      case 'date':
      case 'image':
      default:
        return $item[$column_name];
    }
  }

  function column_cb($item)
  {
    return sprintf(
      '<input type="checkbox" name="element[]" value="%s" />',
      $item['id']
    );
  }

  // Adding action links to column
  function column_title($item) {
    $actions = array(
      'edit' => sprintf('<a href="?page=%s&action=%s&listing=%s">Edit</a>', $_REQUEST['page'], 'edit', absint($item['ID'])),
      'delete' => sprintf('<a href="?page=%s&action=%s&listing=%s">Delete</a>', $_REQUEST['page'], 'delete', absint($item['ID'])),
    );
    return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
  }

  // To show bulk action dropdown
  function get_bulk_actions()
  {
    $actions = array(
      'delete_all'    => __('Delete', 'listing'),
      'draft_all' => __('Move to Draft', 'listing')
    );
    return $actions;
  }

  // Bind table with columns, data and all
  function prepare_items()
  {
    // Get the submitted listings from the database
    $args = array(
      'post_type' => 'listing',
      'posts_per_page' => -1,
      'order' => 'DESC',
      'orderby' => 'date'
    );
    $listings = get_posts($args);

    // Set the table data
    $data = array();
    foreach ($listings as $listing) {
      $author = $listing->post_author ? get_the_author_meta('display_name', $listing->post_author) : 'Unknown';
      $data[] = array(
        'id' => $listing->ID,
        'title' => $listing->post_title,
        'author' => $author,
        'status' => $listing->post_status,
        'date' => $listing->post_date,
        'image' => '<img src="' . get_the_post_thumbnail_url($listing->ID) . '" width=50px/>',
      );
    }

    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();
    $primary  = 'date';
    $this->_column_headers = array($columns, $hidden, $sortable, $primary);

    usort($data, array(&$this, 'usort_reorder'));


    // Pagination
    $per_page = 10;
    $current_page = $this->get_pagenum();
    $total_items = count($data);

    $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

    $this->set_pagination_args(array(
      'total_items' => $total_items, // total number of items
      'per_page'    => $per_page, // items to show on a page
      'total_pages' => ceil($total_items / $per_page) // use ceil to round up
    ));


    $this->items = $data;
  }
}
