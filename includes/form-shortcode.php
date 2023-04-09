<?php
function create_post_form_shortcode()
{
  // Check if the user is logged in
  if (!is_user_logged_in()) {
    return '<p>Please log in to access the form.</p>';
  }

  $basename = basename(ABSPATH);
  ob_start();
?>

  <!-- HTML form -->
  <h4>Realtor Plugin Post Form</h4>
  <form id="listings-form" enctype="multipart/form-data">
    <label for="post_title">Title:</label>
    <input type="text" id="post_title" name="post_title"><br>

    <label for="post_content">Content:</label>
    <textarea id="post_content" name="post_content"></textarea><br>

    <label for="post_image">Image:</label>
    <div class="file-input-container">
      Input you picture here!
      <input type="file" id="post_image" name="post_image" onchange="previewImage(event)">
    </div>
    <img id="preview">

    <button type="submit">Create Post</button>
  </form>

  <!-- JS script to handle form submission -->
  <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
  <script>
    // Image Preview
    function previewImage(event) {
      const fileInput = event.target;
      const previewImg = document.getElementById("preview");

      // Check if a file is selected
      if (fileInput.value) {
        const file = fileInput.files[0];
        const reader = new FileReader();

        // Load the selected image file to the preview image element
        reader.onload = function(e) {
          previewImg.src = e.target.result;
        }

        reader.readAsDataURL(file);
      } else {
        // Remove the preview image if no file is selected
        previewImg.src = "";
      }
    }

    // Calling the API to send the form data
    var basename = "<?php echo $basename; ?>"
    jQuery('#listings-form').submit(function(event) {
      event.preventDefault();
      var post_title = jQuery('#post_title').val();
      var post_content = jQuery('#post_content').val();
      var post_image = jQuery('#post_image').prop('files')[0];
      var post_author = "<?php echo get_current_user_id() ?>";
      console.log(post_title);
      console.log(post_author);

      // Create a FormData object to send the form data
      var form_data = new FormData();
      form_data.append('post_title', post_title);
      form_data.append('post_content', post_content);
      form_data.append('post_image', post_image);
      form_data.append('post_author', post_author);

      // Send the form data to the REST API endpoint
      jQuery.ajax({
        url: '<?php echo esc_url_raw(rest_url('realtor-plugin/v1/create-blog-post')); ?>',
        method: 'POST',
        data: form_data,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response.status === 'success') {
            alert(response.message);
            console.log('New post ID: ' + response.post_id);
          } else {
            alert('Success: ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          var errorMessage = xhr.status + ': ' + xhr.statusText;
          alert('Error - ' + errorMessage);
        }
      });
    });
  </script>
<?php

  return ob_get_clean();
}

add_shortcode('realtor-post-form', 'create_post_form_shortcode');

?>