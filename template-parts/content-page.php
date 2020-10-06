<main>
    <?php

    $options = get_option('stuffs_maintenance_plugin_options');

    /**
     * Hooked
     * stuffs_maintenance_background_slider 0, 10
     */
    do_action('stuffs_maintenance_background_image_slider');

    /**
     * Hooked
     * stuffs_maintenance_before_content_render, 0, 10
     */

    do_action('stuffs_maintenance_before_content');
    /**
     * Hooked
     */
    do_action('stuffs_maintenance_before_title');

    /**
     * Hooked
     * stuffs_maintenance_title_render, 0, 10
     */
    do_action('stuffs_maintenance_title');
    /**
     * Hooked
     * stuffs_maintenance_title_render, 0, 10
     */
    do_action('stuffs_maintenance_after_title');

    if (isset($options['mailchimp_active']) && $options['mailchimp_active'] === "1") {

        /**
         * Hooked
         * stuffs_maintenance_before_form, 0, 10
         */

        do_action('stuffs_maintenance_before_form');

        /**
         * Hooked
         * stuffs_maintenance_form_render, 0, 10
         */
        do_action('stuffs_maintenance_form');

        /**
         * Hooked
         * 
         */
        do_action('stuffs_maintenance_after_form');
    }
    /**
     * Hooked
     * stuffs_maintenance_after_content_render, 0, 10
     */
    do_action('stuffs_maintenance_after_content');

    ?>
</main>