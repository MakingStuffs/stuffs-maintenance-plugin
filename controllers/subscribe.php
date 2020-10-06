<?php

$user = $_POST["email"];
$token = $_POST["token"];

require_once('../../../../wp-load.php');

if (!isset($user)) {
    echo json_encode([
        "success"   => false,
        "title"     =>  "No Email",
        "message"   => "It looks as though no email address was provided"
    ]);
    die;
}
if (!isset($token)) {
    echo json_encode([
        "success"   => false,
        "title"     =>  "Failed reCaptcha",
        "message"   => "You seem to have failed a reCaptcha test by Google. If you're not a bot try again"
    ]);
    die;
}

/**
 * Hooked 
 * stuffs_maintenance_plugin_recaptcha_test, 1, 10
 */

do_action('stuffs_maintenance_plugin_recaptcha_test', $token);

/**
 * Hooked 
 * stuffs_maintenance_add_mailchimp_member, 1, 10
 */
do_action('stuffs_maintenance_mailchimp_subscribe', $user);
