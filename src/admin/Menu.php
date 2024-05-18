<?php
namespace LightCommerce\Admin;
use LightCommerce\Common\Helpers;

class Menu {
    private $chart;

     public function __construct() {
        add_action('admin_menu', [$this, 'register_menu_pages']);
        $this->chart = new Chart();
     }

    public function register_menu_pages() {

        add_menu_page(
            __('LightCommerce', 'light-commerce'),
            __('LightCommerce', 'light-commerce'),
            'manage_options',
            'lightcommerce',
            [$this, 'render_page'],
            'dashicons-cart'
        );

        add_menu_page(
            __('Products', 'light-commerce'),
            __('Products', 'light-commerce'),
            'manage_options',
            'lightcommerce-products',
            [$this, 'render_products_page']
        );

        add_submenu_page(
            'lightcommerce-products',
            __('Add Product', 'light-commerce'),
            __('Add Product', 'light-commerce'),
            'manage_options',
            'lightcommerce-add-product',
            [$this, 'render_add_product_page']
        );

        add_submenu_page(
            'lightcommerce',
            __('Orders', 'light-commerce'),
            __('Orders', 'light-commerce'),
            'manage_options',
            'lightcommerce-orders',
            [$this, 'render_orders_page']
        );
    }

    public function render_page() {
        // Render the chart containers
        ?>
        <div class="wrap">
            <h1><?php _e('Order Status Report', 'light-commerce'); ?></h1>
            <canvas id="status-chart" width="400" height="200"></canvas>

            <h1><?php _e('Payment Method Report', 'light-commerce'); ?></h1>
            <canvas id="payment-method-chart" width="400" height="200"></canvas>
        </div>
        <?php

        // Pass data to JavaScript for chart rendering
        wp_localize_script('light-commerce-chart', 'status_data', $this->chart->get_status_data());
        wp_localize_script('light-commerce-chart', 'payment_method_data', $this->chart->get_payment_method_data());
    }

    public function render_products_page() {
        Helpers::get_template('products', [], 'admin');
    }

    public function render_add_product_page() {
        Helpers::get_template('add-product', [], 'admin');
    }

    public function render_orders_page() {
        Helpers::get_template('orders', [], 'admin');
    }
}
