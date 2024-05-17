<?php
if (!defined('ABSPATH')) {
    exit;
}

use LightCommerce\Admin\Database;

$db = new Database();

$products = $db->get_all_products();
?>

<div class="wrap">
    <h1><?php _e('Products', 'light-commerce'); ?></h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('ID', 'light-commerce'); ?></th>
                <th><?php _e('Name', 'light-commerce'); ?></th>
                <th><?php _e('Price', 'light-commerce'); ?></th>
                <th><?php _e('Description', 'light-commerce'); ?></th>
                <th><?php _e('Created At', 'light-commerce'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($products) : ?>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?php echo esc_html($product->id); ?></td>
                        <td><?php echo esc_html($product->name); ?></td>
                        <td><?php echo esc_html($product->price); ?></td>
                        <td><?php echo esc_html($product->description); ?></td>
                        <td><?php echo esc_html($product->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5"><?php _e('No products found.', 'light-commerce'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
