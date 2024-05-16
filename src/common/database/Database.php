<?php
namespace LightCommerce\Admin;

class Database {
    protected $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        add_action('plugins_loaded', [$this, 'activate']);
    }

    public function activate() {
        $this->create_product_table();
        $this->create_product_meta_table();
        $this->create_order_table();
        $this->create_order_meta_table();
    }

    private function create_product_table() {
        $table_name = $this->wpdb->prefix . 'lightcommerce_product';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private function create_product_meta_table() {
        $table_name = $this->wpdb->prefix . 'lightcommerce_product_meta';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            meta_key VARCHAR(255) NOT NULL,
            meta_value TEXT,
            FOREIGN KEY (product_id) REFERENCES " . $this->wpdb->prefix . "lightcommerce_product(id) ON DELETE CASCADE
        )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private function create_order_table() {
        $table_name = $this->wpdb->prefix . 'lightcommerce_order';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(255) NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private function create_order_meta_table() {
        $table_name = $this->wpdb->prefix . 'lightcommerce_order_meta';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            meta_key VARCHAR(255) NOT NULL,
            meta_value TEXT,
            FOREIGN KEY (order_id) REFERENCES " . $this->wpdb->prefix . "lightcommerce_order(id) ON DELETE CASCADE
        )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
