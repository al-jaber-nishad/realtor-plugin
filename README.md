# Realtor Plugin

## Installation

1. Download the `realtor-plugin` folder and zip it then upload it in the "Add new Plugin" Section.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. The REST API endpoint will be available at `/wp-json/realtor-plugin/v1/create-blog-post`.

## Usage

To add a new listing to your website, you can use a tool like Postman to make a POST request to the `/wp-json/realtor-plugin/v1/create-blog-post` endpoint. The request should include the following parameters:

- `post_title`: The title of the property listing.
- `post_content`: A description of the property.
- `post_author`: The ID of the user creating the listing.
- `post_image`: An image of the property.

If the request is successful, a new listing will be created on your website and the ID of the new post will be returned in the response.
