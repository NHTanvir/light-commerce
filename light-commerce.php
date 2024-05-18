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
        new Front\Shortcode();
        new Front\Shop();
        new Front\Cart();
    }
}
new ClassLoader;


define('LIGHT_COMMERCE_VERSION', '1.0.0');
define('LIGHT_COMMERCE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LIGHT_COMMERCE_PLUGIN_URL', plugin_dir_url(__FILE__));


