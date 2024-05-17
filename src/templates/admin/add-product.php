<?php
if (!defined('ABSPATH')) {
    exit;
}

use LightCommerce\Admin\Database;

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $product_name = sanitize_text_field($_POST['product_name']);
    $product_price = sanitize_text_field($_POST['product_price']);
    $product_description = sanitize_textarea_field($_POST['product_description']);

    // Add the product to the database
    $product_id = $db->add_product($product_name, $product_price, $product_description);

    if ($product_id) {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Product added successfully.', 'light-commerce') . '</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>' . __('Failed to add product.', 'light-commerce') . '</p></div>';
    }
}
?>

<div class="wrap">
    <h1><?php _e('Add New Product', 'light-commerce'); ?></h1>
    <form method="POST" action="">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="product_name"><?php _e('Product Name', 'light-commerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="product_name" name="product_name" class="regular-text" required />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="product_price"><?php _e('Product Price', 'light-commerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="product_price" name="product_price" class="regular-text" required />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="product_description"><?php _e('Product Description', 'light-commerce'); ?></label>
                </th>
                <td>
                    <textarea id="product_description" name="product_description" class="regular-text"></textarea>
                </td>
            </tr>
        </table>
        <?php submit_button(__('Add Product', 'light-commerce')); ?>
    </form>
</div>
