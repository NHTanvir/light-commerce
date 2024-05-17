<?php
namespace LightCommerce\Common;

class Helpers {

    /**
     * Load a template file.
     *
     * @param string $template_name The name of the template file (without extension).
     * @param array $args Optional. An associative array of variables to pass to the template.
     * @param string $type Optional. The type of template to load ('admin' or 'front'). Default is 'admin'.
     */
    public static function get_template($template_name, $args = [], $type = 'admin') {
        $template_path = plugin_dir_path(__FILE__) . '../Templates/' . $type . '/' . $template_name . '.php';

        if (file_exists($template_path)) {
            // Extract the args array to variables
            if (!empty($args) && is_array($args)) {
                extract($args);
            }

            // Include the template file
            include $template_path;
        } else {
            echo sprintf(__('Template %s not found in %s templates.', 'light-commerce'), $template_name, $type);
        }
    }
}
