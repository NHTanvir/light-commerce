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

    // Add a new product
    public function add_product($name, $price, $description = '') {
        $this->wpdb->insert(
            $this->wpdb->prefix . 'lightcommerce_product',
            [
                'name' => $name,
                'price' => $price,
                'description' => $description
            ],
            [
                '%s', '%f', '%s'
            ]
        );
        return $this->wpdb->insert_id;
    }

    // Update an existing product
    public function update_product($id, $name, $price, $description = '') {
        return $this->wpdb->update(
            $this->wpdb->prefix . 'lightcommerce_product',
            [
                'name' => $name,
                'price' => $price,
                'description' => $description
            ],
            ['id' => $id],
            [
                '%s', '%f', '%s'
            ],
            ['%d']
        );
    }

    // Delete a product
    public function delete_product($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_product',
            ['id' => $id],
            ['%d']
        );
    }

    // Add a new product meta
    public function add_product_meta($product_id, $meta_key, $meta_value) {
        $this->wpdb->insert(
            $this->wpdb->prefix . 'lightcommerce_product_meta',
            [
                'product_id' => $product_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ],
            [
                '%d', '%s', '%s'
            ]
        );
        return $this->wpdb->insert_id;
    }

    // Update an existing product meta
    public function update_product_meta($id, $meta_key, $meta_value) {
        return $this->wpdb->update(
            $this->wpdb->prefix . 'lightcommerce_product_meta',
            [
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ],
            ['id' => $id],
            [
                '%s', '%s'
            ],
            ['%d']
        );
    }

    // Delete a product meta
    public function delete_product_meta($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_product_meta',
            ['id' => $id],
            ['%d']
        );
    }

    // Add a new order
    public function add_order($customer_name, $total_amount) {
        $this->wpdb->insert(
            $this->wpdb->prefix . 'lightcommerce_order',
            [
                'customer_name' => $customer_name,
                'total_amount' => $total_amount
            ],
            [
                '%s', '%f'
            ]
        );
        return $this->wpdb->insert_id;
    }

    // Update an existing order
    public function update_order($id, $customer_name, $total_amount) {
        return $this->wpdb->update(
            $this->wpdb->prefix . 'lightcommerce_order',
            [
                'customer_name' => $customer_name,
                'total_amount' => $total_amount
            ],
            ['id' => $id],
            [
                '%s', '%f'
            ],
            ['%d']
        );
    }

    // Delete an order
    public function delete_order($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_order',
            ['id' => $id],
            ['%d']
        );
    }

    // Add a new order meta
    public function add_order_meta($order_id, $meta_key, $meta_value) {
        $this->wpdb->insert(
            $this->wpdb->prefix . 'lightcommerce_order_meta',
            [
                'order_id' => $order_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ],
            [
                '%d', '%s', '%s'
            ]
        );
        return $this->wpdb->insert_id;
    }

    // Update an existing order meta
    public function update_order_meta($id, $meta_key, $meta_value) {
        return $this->wpdb->update(
            $this->wpdb->prefix . 'lightcommerce_order_meta',
            [
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ],
            ['id' => $id],
            [
                '%s', '%s'
            ],
            ['%d']
        );
    }

    // Delete an order meta
    public function delete_order_meta($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_order_meta',
            ['id' => $id],
            ['%d']
        );
    }
}
