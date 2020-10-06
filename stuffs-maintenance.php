<?php

/**
 * Plugin Name: Making Stuffs Maintenance
 * Description: A simple plugin to provide a maintenance mode with a Mailchimp signup form and Google reCaptcha v3.
 * Plugin URI: https://github.com/makingstuffs/stuffs-maintenance-plugin
 * Text Domain: stuffs-maintenance 
 * Author: Paul Singh
 * Author URI: https://makingstuffs.co.uk
 * License: MIT
 * Version: 1.0.4
 */

define('PLUGIN_DIR', dirname(__FILE__) . '/');

/**
 * Register custom action hooks
 */
require_once(plugin_dir_path(__FILE__)  . 'inc/actions.php');

/**
 * Import admin settings
 */
require_once(plugin_dir_path(__FILE__) . 'inc/admin-settings.php');

/**
 * Check wether maintenance is active within the plugin and then check whether the 
 * wp .maintenance file is present in the home directory. Create one if it isnt there. 
 * 
 * If maintenance is inactive then remove the .maintenance file from the directory
 */
if (!function_exists('stuffs_maintenance_check')) {
    function stuffs_maintenance_check()
    {
        $options = get_option('stuffs_maintenance_plugin_options');

        if ($options['active']) {

            if (file_exists(ABSPATH . '.maintenance')) {
                include_once plugin_dir_path(__FILE__) . 'maintenance.php';
                die();
            } else {
                $maintenance_file = fopen('.maintenance', 'w');
                copy($maintenance_file, get_template_directory_uri() . '.maintenance');
            }
        } else {
            if (file_exists(ABSPATH . '.maintenance')) {
                unlink('.maintenance', get_template_directory_uri());
            }
        }
    }
}
add_action('wp', 'stuffs_maintenance_check');

/**
 * Dynamically add the css and js files with the version hashes
 */

if (!function_exists('stuffs_maintenance_enqueue_scripts')) {
    function stuffs_maintenance_enqueue_scripts()
    {
        $options = get_option('stuffs_maintenance_plugin_options');

        wp_register_script('stuffs-maintenance-script', plugin_dir_url(__FILE__) . 'assets/js/main.bundle.js');
        wp_enqueue_script('stuffs-maintenance-script');

        wp_register_style('stuffs-maintenance-style', plugin_dir_url(__FILE__) . 'assets/css/styles.css');
        wp_enqueue_style('stuffs-maintenance-style');

        $js_vars = [
            "recaptcha_site_key"    => $options['recaptcha_site_key'],
            "recaptcha_active"      => $options['recaptcha_active']
        ];

        wp_localize_script('stuffs-maintenance-script', 'stuffs_maintenance_vars', $js_vars);
    }
}
add_action('wp_enqueue_scripts', 'stuffs_maintenance_enqueue_scripts');

/**
 * Setup
 */

if (!function_exists('stuffs_maintenance_activation')) {
    function stuffs_maintenance_activation()
    {
        if (null !== get_option('stuffs_maintenance_plugin_options')) {
            add_option('stuffs_maintenance_plugin_options', [
                "heading" => "Coming Soon",
                "information" => "Our website is currently under construction, join our VIP list to be informed when we launch",
                "apiKey" => "",
                "server" => "",
                "list_id" => ""
            ]);
        }
    }
}
register_activation_hook(__FILE__, 'stuffs_maintenance_activation');
