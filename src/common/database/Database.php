<?php
namespace LightCommerce\Admin;

class Database {
    protected $wpdb;
    protected $cache_duration = 5;

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
        $this->create_cart_table();
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
            customer_address TEXT NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            status VARCHAR(50) NOT NULL,
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

    private function create_cart_table() {
        $table_name = $this->wpdb->prefix . 'lightcommerce_cart';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(255) NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES {$this->wpdb->prefix}lightcommerce_product(id)
        )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


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

    public function delete_product($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_product',
            ['id' => $id],
            ['%d']
        );
    }

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

    public function delete_product_meta($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_product_meta',
            ['id' => $id],
            ['%d']
        );
    }

    public function get_all_products() {
        $cache_key = 'lightcommerce_all_products';
        $products = get_transient($cache_key);

        if (false === $products) {

            $table_name = $this->wpdb->prefix . 'lightcommerce_product';
            $products = $this->wpdb->get_results("SELECT * FROM $table_name");

            set_transient($cache_key, $products, $this->cache_duration);
        }

        return $products;
    }

    public function add_order($customer_name, $customer_address, $customer_email, $payment_method, $total_amount, $status) {
        $this->wpdb->insert(
            $this->wpdb->prefix . 'lightcommerce_order',
            [
                'customer_name' => $customer_name,
                'customer_address' => $customer_address,
                'customer_email' => $customer_email,
                'payment_method' => $payment_method,
                'total_amount' => $total_amount,
                'status' => $status
            ],
            [
                '%s', '%s', '%s', '%s', '%f', '%s'
            ]
        );
        return $this->wpdb->insert_id;
    }

    public function update_order($id, $customer_name, $customer_address, $customer_email, $payment_method, $total_amount, $status) {
        return $this->wpdb->update(
            $this->wpdb->prefix . 'lightcommerce_order',
            [
                'customer_name' => $customer_name,
                'customer_address' => $customer_address,
                'customer_email' => $customer_email,
                'payment_method' => $payment_method,
                'total_amount' => $total_amount,
                'status' => $status
            ],
            ['id' => $id],
            [
                '%s', '%s', '%s', '%s', '%f', '%s'
            ],
            ['%d']
        );
    }

    public function delete_order($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_order',
            ['id' => $id],
            ['%d']
        );
    }

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

    public function get_all_orders() {
        $cache_key = 'lightcommerce_all_orders';
        $orders = get_transient($cache_key);

        if (false === $orders) {
            // If not found in cache, fetch from the database
            $table_name = $this->wpdb->prefix . 'lightcommerce_order';
            $orders = $this->wpdb->get_results("SELECT * FROM $table_name");

            // Store fetched data in cache
            set_transient($cache_key, $orders, $this->cache_duration);
        }

        return $orders;
    }

    public function delete_order_meta($id) {
        return $this->wpdb->delete(
            $this->wpdb->prefix . 'lightcommerce_order_meta',
            ['id' => $id],
            ['%d']
        );
    }

    public function get_product($id) {
        return $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}lightcommerce_product WHERE id = %d",
            $id
        ));
    }

    public function get_order($id) {
        return $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}lightcommerce_order WHERE id = %d",
            $id
        ));
    }

    public function get_cart_items($session_id) {
        $table_name = $this->wpdb->prefix . 'lightcommerce_cart';
        $sql = $this->wpdb->prepare("SELECT * FROM $table_name WHERE session_id = %s", $session_id);
        return $this->wpdb->get_results($sql);
    }

    public function add_to_cart($session_id, $product_id, $quantity = 1) {
        $table_name = $this->wpdb->prefix . 'lightcommerce_cart';
        $existing_item = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM $table_name WHERE session_id = %s AND product_id = %d", $session_id, $product_id
        ));

        if ($existing_item) {
            $new_quantity = $existing_item->quantity + $quantity;
            $this->wpdb->update($table_name, ['quantity' => $new_quantity], ['id' => $existing_item->id]);
        } else {
            $this->wpdb->insert($table_name, [
                'session_id' => $session_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);
        }
    }

    public function remove_from_cart($session_id, $product_id) {
        $table_name = $this->wpdb->prefix . 'lightcommerce_cart';
        $this->wpdb->delete($table_name, ['session_id' => $session_id, 'product_id' => $product_id]);
    }

    public function page_exists_by_title($title) {
        global $wpdb;
        $page_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'page'", $title));
        return $page_id ? true : false;
    }
}
