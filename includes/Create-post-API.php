<?php


// Register the REST API endpoint
add_action('rest_api_init', function () {
  register_rest_route('realtor-plugin/v1', 'create-blog-post', array(
    'methods' => 'POST',
    'callback' => 'my_plugin_create_blog_post',
  ));
});

// Define the REST API endpoint callback function
function my_plugin_create_blog_post(WP_REST_Request $request)
{
  // Log the request details
  error_log('HTTP method: ' . $request->get_method());
  error_log('Request URL: ' . $request->get_route() . $request->get_route());
  error_log('Headers: ' . print_r($request->get_headers(), true));
  error_log('Request data: ' . print_r($request->get_params(), true));


  // Get the form data from the request
  $post_title = $request->get_param('post_title');
  $post_content = $request->get_param('post_content');
  $post_author = $request->get_param('post_author');


  // Get the image data from the request
  $file = $request->get_file_params();
  $image_data = $file['post_image'];

  // Create a new blog post
  $new_post = array(
    'post_title' => $post_title,
    'post_content' => $post_content,
    'post_author' => $post_author,
    'post_status' => 'publish',
    'post_type' => 'listing',
  );

  $post_id = wp_insert_post($new_post);

  // Add the image attachment
  if ($image_data) {
    $upload_dir = wp_upload_dir();
    $file_name = $image_data['name'];
    $file_type = $image_data['type'];
    $file_tmp_name = $image_data['tmp_name'];

    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    if (in_array($file_ext, $allowed_types)) {
      $file_path = $upload_dir['path'] . '/' . $file_name;
      $file_url = $upload_dir['url'] . '/' . $file_name;

      if (move_uploaded_file($file_tmp_name, $file_path)) {
        $attachment = array(
          'post_mime_type' => $file_type,
          'post_title' => sanitize_file_name($file_name),
          'post_content' => '',
          'post_status' => 'inherit',
          'post_parent' => $post_id,
          'guid' => $file_url,
        );

        $attachment_id = wp_insert_attachment($attachment, $file_path, $post_id);
        if (!is_wp_error($attachment_id)) {
          require_once ABSPATH . 'wp-admin/includes/image.php';
          $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
          wp_update_attachment_metadata($attachment_id, $attachment_data);
          set_post_thumbnail($post_id, $attachment_id);
        }
      }
    }
  }

  // Return a success message with the new post ID
  return array('message' => 'Blog post created successfully!', 'post_id' => $post_id);
}

