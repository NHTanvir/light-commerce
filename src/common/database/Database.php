<?php
namespace Tanvir10\LightCommerce;

class Database {
    public static function setup() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();


        $table_definitions = array(
            'lightcommerce_product' => array(
                'id' => 'mediumint(9) NOT NULL AUTO_INCREMENT',
                'name' => 'varchar(100) NOT NULL',
                'description' => 'text',
                'price' => 'decimal(10,2) NOT NULL',
                'PRIMARY KEY' => '(id)'
            ),
            'lightcommerce_product_meta' => array(
                'meta_id' => 'bigint(20) NOT NULL AUTO_INCREMENT',
                'product_id' => 'bigint(20) NOT NULL',
                'meta_key' => 'varchar(255)',
                'meta_value' => 'longtext',
                'PRIMARY KEY' => '(meta_id)',
                'KEY product_id' => '(product_id)',
                'KEY meta_key' => '(meta_key)'
            ),

        );


        foreach ($table_definitions as $table_name => $columns) {
            self::create_table($table_name, $columns, $charset_collate);
        }
    }

    private static function create_table($table_name, $columns, $charset_collate) {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (";

        foreach ($columns as $column => $definition) {
            $sql .= "$column $definition, ";
        }

        $sql .= ") $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
