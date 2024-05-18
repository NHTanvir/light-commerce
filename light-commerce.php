<?php
/**
 * Plugin Name: LightCommerce
 * Plugin URI: https://naymul.com
 * Description: Lighter ecommerce plugin 
 * Version: 0.9
 * Author: Naymul Hasan tanvir
 * Author URI: https://naymul.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: light-commerce
 */
namespace LightCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once(dirname(__FILE__) . '/vendor/autoload.php');

class ClassLoader{
    public function __construct() {
        new Admin\Menu();
        new Admin\Database();
        new Front\Shop();
        new Front\Shortcode();
        new Front\Cart();
        new Front\Checkout();
        register_activation_hook(__FILE__, array($this, 'initialize_dummy_products'));
    }

    public function initialize_dummy_products() {

        if(  get_option( 'lightcommerce_dummy_products_initialized' ) ) return;

        $db = new Admin\Database();
        $dummy_products = array(
            array('name' => 'Product 1', 'price' => 10.99, 'description' => 'Description for Product 1'),
            array('name' => 'Product 2', 'price' => 19.99, 'description' => 'Description for Product 2'),

        );

        foreach ($dummy_products as $product) {
            $db->add_product( $product['name'], $product['price'] , $product['description'] );
        }
        
        update_option( 'lightcommerce_dummy_products_initialized', true );

    }
}
new ClassLoader;

define('LIGHT_COMMERCE_VERSION', '1.0.0');
define('LIGHT_COMMERCE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LIGHT_COMMERCE_PLUGIN_URL', plugin_dir_url(__FILE__));



