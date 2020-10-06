<?php

/**
 * stuffs_maintenance_background_slider
 */
if (!function_exists('stuffs_maintenance_background_slider')) {
    function stuffs_maintenance_background_slider()
    { ?>
        <div class="stuffs-maintenance__slider">
            <div class="stuffs-maintenance__slide" style="background-image: url('<?php echo plugin_dir_url(__DIR__) . "/assets/img/avocado-salad.jpg"; ?>');" active data-index="0">
            </div>
            <div class="stuffs-maintenance__slide" style="background-image: url('<?php echo plugin_dir_url(__DIR__) . "/assets/img/espresso-martini.jpg"; ?>');" data-index="1">
            </div>
            <div class="stuffs-maintenance__slide" style="background-image: url('<?php echo plugin_dir_url(__DIR__) . "/assets/img/poached-egg.jpg"; ?>');" data-index="2">
            </div>
            <div class="stuffs-maintenance__slide" style="background-image: url('<?php echo plugin_dir_url(__DIR__) . "/assets/img/rainbow-salad.jpg"; ?>');" data-index="3">
            </div>
        </div>
    <?php }
}
add_action('stuffs_maintenance_background_image_slider', 'stuffs_maintenance_background_slider', 0, 10);
/**
 * Action before content in page
 */
if (!function_exists('stuffs_maintenance_before_content_render')) {
    function stuffs_maintenance_before_content_render()
    { ?>
        <div class="stuffs-maintenance__content">
        <?php }
}
add_action('stuffs_maintenance_before_content', 'stuffs_maintenance_before_content_render', 0, 10);
/**
 * Render the header component 
 * 
 * Hooked in content-page
 */

if (!function_exists('stuffs_maintenance_title_render')) {
    function stuffs_maintenance_title_render()
    { ?>
            <header class="stuffs-maintenance__header">
                <?php if (get_option('stuffs_maintenance_plugin_options')['show_logo']) {
                    echo the_custom_logo();
                } else { ?>
                    <h1><?php echo get_option('stuffs_maintenance_plugin_options')['heading']; ?></h1>
                <?php } ?>
                <p><?php echo get_option('stuffs_maintenance_plugin_options')['information']; ?></p>
            </header>
        <?php }
}
add_action('stuffs_maintenance_title', 'stuffs_maintenance_title_render', 0, 10);

/**
 * Run before the form is rendered
 */
if (!function_exists('stuffs_maintenance_before_form')) {
    function stuffs_maintenance_before_form()
    {
    }
}
add_action('before_stuffs_maintenance_form', 'stuffs_maintenance_before_form', 0, 10);

/**
 * Render the form component
 * 
 * Hooked in content-page
 */
if (!function_exists('stuffs_maintenance_form_render')) {
    function stuffs_maintenance_form_render()
    { ?>
            <form class="stuffs-maintenance__form" id="maintenance_subscription" method="POST" action="<?php echo plugin_dir_url(__DIR__) . 'controllers/subscribe.php'; ?>">
                <p class="stuffs-maintenance-form__title">Join Our VIP List</p>
                <div class="stuffs-maintenance__field">
                    <label>Email</label>
                    <input name="email" type="email" inputmode="email" required>
                </div>
                <p class="disclaimer">This site is protected by reCAPTCHA and the <a href="https://policies.google.com/privacy" title="Google Privacy Policy" rel="noopener noreferrer">Google Privacy Policy</a> and <a href="https://policies.google.com/terms" title="Google terms of service" rel="noopener noreferrer">Terms of Service</a> apply.</p>
                <input class="stuffs-maintenance__submit" type="submit">
                <div class="stuffs-maintenance-form__response">
                    <p class="title"></p>
                    <p class="content"></p>
                    <button type="button">Close</button>
                </div>
            </form>
        <?php }
}
add_action('stuffs_maintenance_form', 'stuffs_maintenance_form_render', 0, 10);

/**
 * Action after content in page
 */
if (!function_exists('stuffs_maintenance_after_content_render')) {
    function stuffs_maintenance_after_content_render()
    { ?>
        </div>
<?php }
}
add_action('stuffs_maintenance_after_content', 'stuffs_maintenance_after_content_render', 0, 10);

/**
 * Action to add new member to mailchimp form
 * 
 * @param String $form_id The list's ID
 * @param String $user The new user object
 * @return Object $response from the server
 */
if (!function_exists('stuffs_maintenance_add_mailchimp_member')) {
    function stuffs_maintenance_add_mailchimp_member($user)
    {
        try {
            require_once(plugin_dir_path(__DIR__) . 'inc/MailchimpMarketing/vendor/autoload.php');

            $api_info = get_option('stuffs_maintenance_plugin_options');

            $client = new \MailchimpMarketing\ApiClient();

            $client->setConfig([
                'apiKey' => $api_info['apiKey'],
                'server' => $api_info['server'],
            ]);

            $hash = md5(strtolower($user));

            $member = $client->lists->getListMember($api_info['list_id'], [
                "subscriber_hash"    => $hash
            ]);

            if (isset($member)) {
                echo json_encode([
                    "success"   => true,
                    "title"     => "You're Already Registered",
                    "message"   => "Your email is already on our mailing list. You will be emailed as soon as we open."
                ]);
                return;
            }

            $response = $client->lists->addListMember($api_info['list_id'], [
                "email_address" => $user,
                "status" => "subscribed",
            ]);

            if (isset($response->id)) {
                $return_array = [
                    "success"   => true,
                    "title"     =>  "You're All Set",
                    "message"   => "Your email has been added to our VIP list, keep an eye out for exciting news from our team"
                ];
                echo json_encode($return_array);
                return;
            } else {
                echo json_encode([
                    "success"   =>  false,
                    "title"     =>  "Something Went Wrong",
                    "message"   =>  "It looks like something has gone wrong. Check you've entered your email correctly and try again"
                ]);
                return;
            }
        } catch (Exception $e) {
            echo json_encode([
                "success"   =>  false,
                "title"     =>  "Internal Error",
                "message"   =>  "We seem to be having a bit of trouble processing your request. Sorry about that."
            ]);
            die;
        }
    }
}
add_action('stuffs_maintenance_mailchimp_subscribe', 'stuffs_maintenance_add_mailchimp_member', 1, 10);

/**
 * Run Google recaptcha verification
 */
if (!function_exists('stuffs_maintenance_plugin_run_recaptcha_test')) {
    function stuffs_maintenance_plugin_run_recaptcha_test($token)
    {
        try {
            $secret = get_option(['stuffs_maintenance_plugin_options'])['recaptcha_secret'];
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $request_body = [
                "secret"    => $secret,
                "response"  => $token
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_response = curl_exec($ch);
            curl_close($ch);

            if (!$server_response['success']) {
                echo json_encode([
                    "success"   =>  false,
                    "title"     =>  "Failed Recaptcha Test",
                    "message"   =>  "Google seems to have marked you down as a bot. Give it another go."
                ]);
                die;
            }

        } catch (Exception $e) {
            echo json_encode([
                "success"   =>  false,
                "title"     =>  "Failed Recaptcha Test",
                "message"   =>  $e->getMessage()
            ]);
            die;
        }
    }
}
add_action('stuffs_maintenance_plugin_recaptcha_test', 'stuffs_maintenance_plugin_run_recaptcha_test', 1, 10);

/**
 * Styling for admin pages
 */
if (!function_exists('stuffs_maintenance_plugin_admin_scripts')) {
    function stuffs_maintenance_plugin_admin_scripts()
    {
        wp_register_style('stuffs_maintenance_admin', plugin_dir_url(__DIR__) . 'assets/css/admin.css');
        wp_enqueue_style('stuffs_maintenance_admin', plugin_dir_url(__DIR__) . 'assets/css/admin.css');
    };
};
add_action('admin_enqueue_scripts', 'stuffs_maintenance_plugin_admin_scripts');
