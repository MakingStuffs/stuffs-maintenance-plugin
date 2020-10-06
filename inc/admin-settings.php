<?php 

/**
 * Register Admin Menu Page
 */
if (!function_exists('stuffs_maintenance_register_admin_page')) {
    function stuffs_maintenance_register_admin_page()
    {
        add_menu_page(
            __('Stuffs Maintenance', 'stuffs-maintenance'),
            'Stuffs Maintenance',
            'manage_options',
            'stuffs-maintenance-plugin',
            'stuff_maintenance_admin_page'
        );
    }
}
add_action('admin_menu', 'stuffs_maintenance_register_admin_page');
/**
 * Content for the admin page
 */
if (!function_exists('stuff_maintenance_admin_page')) {
    function stuff_maintenance_admin_page()
    { ?>
        <h2><?php echo esc_html_e('Admin Page Test', 'textdomain'); ?></h2>
        <form action="options.php" method="POST">
            <?php
            settings_fields('stuffs_maintenance_plugin_options');
            do_settings_sections('stuffs_maintenance_plugin');
            ?>
            <input type="submit" value="save" />
        </form>
    <?php }
}
/**
 * Admin page settings register function
 */
if (!function_exists('stuffs_maintenance_register_settings')) {
    function stuffs_maintenance_register_settings()
    {
        register_setting('stuffs_maintenance_plugin_options', 'stuffs_maintenance_plugin_options', 'stuffs_maintenance_plugin_validate');

        add_settings_section('general_settings', 'General Settings', 'stuffs_maintenance_plugin_section_text', 'stuffs_maintenance_plugin');
        add_settings_field('active', 'Maintenance Active', "stuffs_maintenance_plugin_active", "stuffs_maintenance_plugin", "general_settings");
        add_settings_field('mailchimp_active', 'Show Mailchimp', "stuffs_maintenance_plugin_mailchimp_active", "stuffs_maintenance_plugin", "general_settings", array("Display Mailchimp signup"));
        add_settings_field('recaptcha_active', 'Use Recaptcha', "stuffs_maintenance_plugin_recaptcha_active", "stuffs_maintenance_plugin", "general_settings");
        add_settings_field('show_logo', 'Show Logo', "stuffs_maintenance_plugin_show_logo", "stuffs_maintenance_plugin", "general_settings");
        add_settings_field('stuffs_maintenance_plugin_heading', 'Heading Text', 'stuffs_maintenance_plugin_heading', 'stuffs_maintenance_plugin', 'general_settings');
        add_settings_field('stuffs_maintenance_plugin_information', 'Main Text', 'stuffs_maintenance_plugin_information', 'stuffs_maintenance_plugin', 'general_settings');


        add_settings_section('mail_chimp_settings', 'Mailchimp Settings', 'stuffs_maintenance_plugin_section_text', 'stuffs_maintenance_plugin');
        add_settings_field('stuffs_maintenance_plugin_chimp_api', 'API Key', 'stuffs_maintenance_plugin_chimp_api', 'stuffs_maintenance_plugin', 'mail_chimp_settings');
        add_settings_field('stuffs_maintenance_plugin_chimp_list_id', 'List ID', 'stuffs_maintenance_plugin_chimp_list_id', 'stuffs_maintenance_plugin', 'mail_chimp_settings');
        add_settings_field('stuffs_maintenance_plugin_chimp_server_prefix', 'Server Prefix', 'stuffs_maintenance_plugin_chimp_server_prefix', 'stuffs_maintenance_plugin', 'mail_chimp_settings');
        add_settings_field('recaptcha_site_key', 'reCaptcha Site Key', 'stuffs_maintenance_plugin_recaptcha_site_key', 'stuffs_maintenance_plugin', 'mail_chimp_settings');
        add_settings_field('recaptcha_secret', 'reCaptcha Secret', 'stuffs_maintenance_plugin_recaptcha_secret', 'stuffs_maintenance_plugin', 'mail_chimp_settings');
    }
}
add_action('admin_init', 'stuffs_maintenance_register_settings');
/**
 * Render admin page section text
 */
if (!function_exists('stuffs_maintenance_plugin_section_text')) {
    function stuffs_maintenance_plugin_section_text()
    { ?>
        <p>This is some text</p>
    <?php }
}
/**
 * Render Active Toggle
 */
if (!function_exists('stuffs_maintenance_plugin_mailchimp_active')) {
    function stuffs_maintenance_plugin_mailchimp_active()
    {
        $options = get_option('stuffs_maintenance_plugin_options');
    ?>
        <label class="stuffs-maintenance__toggle">
            <input type="checkbox" id="mailchimp_active" name="stuffs_maintenance_plugin_options[mailchimp_active]" value="1" <?php echo checked(1, $options['mailchimp_active'], false); ?> />
            <span class="stuffs-maintenance__slider"></span>
        </label>
    <?php }
}
/**
 * Render Active Toggle
 */
if (!function_exists('stuffs_maintenance_plugin_show_logo')) {
    function stuffs_maintenance_plugin_show_logo()
    {
        $options = get_option('stuffs_maintenance_plugin_options');
    ?>
        <label class="stuffs-maintenance__toggle">
            <input type="checkbox" id="show_logo" name="stuffs_maintenance_plugin_options[show_logo]" value="1" <?php echo checked(1, $options['show_logo'], false); ?> />
            <span class="stuffs-maintenance__slider"></span>
        </label>
    <?php }
}
/**
 * Render Active Toggle
 */
if (!function_exists('stuffs_maintenance_plugin_active')) {
    function stuffs_maintenance_plugin_active()
    {
        $options = get_option('stuffs_maintenance_plugin_options');
    ?>
        <label class="stuffs-maintenance__toggle">
            <input type="checkbox" id="active" name="stuffs_maintenance_plugin_options[active]" value="1" <?php echo checked(1, $options['active'], false); ?> />
            <span class="stuffs-maintenance__slider"></span>
        </label>
    <?php }
}
/**
 * Render Recaptcha Active Toggle
 */
if (!function_exists('stuffs_maintenance_plugin_recaptcha_active')) {
    function stuffs_maintenance_plugin_recaptcha_active()
    {
        $options = get_option('stuffs_maintenance_plugin_options');
    ?>
        <label class="stuffs-maintenance__toggle">
            <input type="checkbox" id="recaptcha_active" name="stuffs_maintenance_plugin_options[recaptcha_active]" value="1" <?php echo checked(1, $options['recaptcha_active'], false); ?> />
            <span class="stuffs-maintenance__slider"></span>
        </label>
    <?php }
}
/**
 * Render Heading Input
 */
if (!function_exists('stuffs_maintenance_plugin_heading')) {
    function stuffs_maintenance_plugin_heading()
    {
        $options = get_option('stuffs_maintenance_plugin_options');
    ?>
        <input type="text" id="stuffs_maintenance_plugin_heading" name="stuffs_maintenance_plugin_options[heading]" value="<?php echo esc_attr($options['heading']); ?>" />
    <?php }
}
/**
 * Render information
 */
if (!function_exists('stuffs_maintenance_plugin_information')) {
    function stuffs_maintenance_plugin_information()
    {
        $options = get_option('stuffs_maintenance_plugin_options'); ?>
        <input type="text" id="stuffs_maintenance_plugin_information" name="stuffs_maintenance_plugin_options[information]" value="<?php echo esc_attr($options['information']); ?>" />
    <?php }
}
/**
 * Render Chimp API
 */
if (!function_exists('stuffs_maintenance_plugin_chimp_api')) {
    function stuffs_maintenance_plugin_chimp_api()
    {
        $options = get_option('stuffs_maintenance_plugin_options'); ?>
        <input type="text" id="stuffs_maintenance_plugin_chimp_api" name="stuffs_maintenance_plugin_options[apiKey]" value="<?php echo esc_attr($options['apiKey']); ?>" />
    <?php }
}
/**
 * Render List ID
 */
if (!function_exists('stuffs_maintenance_plugin_chimp_list_id')) {
    function stuffs_maintenance_plugin_chimp_list_id()
    {
        $options = get_option('stuffs_maintenance_plugin_options'); ?>
        <input type="text" id="stuffs_maintenance_plugin_chimp_list_id" name="stuffs_maintenance_plugin_options[list_id]" value="<?php echo esc_attr($options['list_id']); ?>" />
    <?php }
}
/**
 * Render reCaptcha Key
 */
if (!function_exists('stuffs_maintenance_plugin_recaptcha_site_key')) {
    function stuffs_maintenance_plugin_recaptcha_site_key()
    {
        $options = get_option('stuffs_maintenance_plugin_options'); ?>
        <input type="text" id="recaptcha_site_key" name="stuffs_maintenance_plugin_options[recaptcha_site_key]" value="<?php echo esc_attr($options['recaptcha_site_key']); ?>" />
    <?php }
}
/**
 * Render reCaptcha Secret
 */
if (!function_exists('stuffs_maintenance_plugin_recaptcha_secret')) {
    function stuffs_maintenance_plugin_recaptcha_secret()
    {
        $options = get_option('stuffs_maintenance_plugin_options'); ?>
        <input type="text" id="recaptcha_secret" name="stuffs_maintenance_plugin_options[recaptcha_secret]" value="<?php echo esc_attr($options['recaptcha_secret']); ?>" />
    <?php }
}
/**
 * Render Server Prefix
 */
if (!function_exists('stuffs_maintenance_plugin_chimp_server_prefix')) {
    function stuffs_maintenance_plugin_chimp_server_prefix()
    {
        $options = get_option('stuffs_maintenance_plugin_options'); ?>
        <input type="text" id="stuffs_maintenance_plugin_chimp_server_prefix" name="stuffs_maintenance_plugin_options[server]" value="<?php echo esc_attr($options['server']); ?>" />
<?php }
}
