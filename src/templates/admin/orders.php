<?php
if (!defined('ABSPATH')) {
    exit;
}

use LightCommerce\Admin\Database;

$db = new Database();

$orders = $db->get_all_orders();
?>

<div class="wrap">
    <h1><?php _e('Orders', 'light-commerce'); ?></h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('ID', 'light-commerce'); ?></th>
                <th><?php _e('Customer Name', 'light-commerce'); ?></th>
                <th><?php _e('Customer Address', 'light-commerce'); ?></th>
                <th><?php _e('Customer Email', 'light-commerce'); ?></th>
                <th><?php _e('Payment Method', 'light-commerce'); ?></th>
                <th><?php _e('Total Amount', 'light-commerce'); ?></th>
                <th><?php _e('Status', 'light-commerce'); ?></th>
                <th><?php _e('Created At', 'light-commerce'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders) : ?>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?php echo esc_html($order->id); ?></td>
                        <td><?php echo esc_html($order->customer_name); ?></td>
                        <td><?php echo esc_html($order->customer_address); ?></td>
                        <td><?php echo esc_html($order->customer_email); ?></td>
                        <td><?php echo esc_html($order->payment_method); ?></td>
                        <td><?php echo esc_html($order->total_amount); ?></td>
                        <td><?php echo esc_html($order->status); ?></td>
                        <td><?php echo esc_html($order->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8"><?php _e('No orders found.', 'light-commerce'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
