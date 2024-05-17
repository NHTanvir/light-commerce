<?php
if (!defined('ABSPATH')) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission here
    $product_name = sanitize_text_field($_POST['product_name']);
    $product_price = sanitize_text_field($_POST['product_price']);
    // Add your code to save the product

    echo '<div class="notice notice-success is-dismissible"><p>Product added successfully.</p></div>';
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
        </table>
        <?php submit_button(__('Add Product', 'light-commerce')); ?>
    </form>
</div>
