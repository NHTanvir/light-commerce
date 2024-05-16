<?php
namespace LightCommerce\Admin;

class Menu {

     public function __construct() {
        add_action( 'admin_menu', [$this, 'register_menu_pages'] );
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

        add_submenu_page(
            'lightcommerce',
            __('Products', 'light-commerce'),
            __('Products', 'light-commerce'),
            'manage_options',
            'lightcommerce-products',
            [$this, 'render_products_page']
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
        echo "home";
    }

    public function render_products_page() {
        echo "products";
    }

    public function render_orders_page() {
        echo "orders";
    }
}
