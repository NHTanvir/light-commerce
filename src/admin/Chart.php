<?php
namespace LightCommerce\Admin;
use LightCommerce\Admin\Database;

class Chart {
    private $db;

    public function __construct() {
        $this->db = new Database();
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts() {
        // Enqueue Chart.js library
        wp_enqueue_script('chart-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js', [], '3.7.0');

        wp_enqueue_script('light-commerce-chart', LIGHT_COMMERCE_PLUGIN_URL . '/assets/js/chart.js', array('jquery'), time(), true);
    }

    public function get_status_data() {
        $orders = $this->db->get_all_orders();
    

        $status_counts = [];
    
        foreach ($orders as $order) {
            $status = $order->status;
            if (isset($status_counts[$status])) {
                $status_counts[$status]++;
            } else {
                $status_counts[$status] = 1;
            }
        }

        $labels = array_keys($status_counts);
        $counts = array_values($status_counts);
    
        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }

    
    public function get_payment_method_data() {

        $orders = $this->db->get_all_orders();
    
 
        $payment_counts = [];
    

        foreach ($orders as $order) {
            $payment_method = $order->payment_method;
            if (isset($payment_counts[$payment_method])) {
                $payment_counts[$payment_method]++;
            } else {
                $payment_counts[$payment_method] = 1;
            }
        }
    
        $labels = array_keys($payment_counts);
        $counts = array_values($payment_counts);
    
        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }
    
}
