<?php
/**
 * Add a settings link on the plugins page
 */
if (!function_exists('stuffs_maintenance_plugin_settings_link')) {
    function stuffs_maintenance_plugin_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=stuffs-maintenance-plugin">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}
$plugin = plugin_basename(__FILE__);
add_filter('plugin_action_links', 'stuffs_maintenance_plugin_settings_link');
