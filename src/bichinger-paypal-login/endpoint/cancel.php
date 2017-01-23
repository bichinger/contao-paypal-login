<?php

// index.php is a frontend script
define('TL_MODE', 'FE');
// Start the session so we can access known request tokens
@session_start();

// Allow to bypass the token check
if (!isset($_POST['REQUEST_TOKEN'])) {
    define('BYPASS_TOKEN_CHECK', true);
}


// Initialize the system
require('../../../../system/initialize.php');


// load language files
\System::loadLanguageFile('tl_paypal_login_settings');

// get token
$token = \Input::get('token');

if (!empty($token)) {
    // get paypal settings
    $settings = \Bichinger\PayPalLogin\PayPalSettings::getInstance();
    \System::log($GLOBALS['TL_LANG']['MSC']['payment_cancel_error'], __METHOD__, TL_ERROR);

    // redirect to configured page
    $url = \Bichinger\Helper\UrlHelper::getUrlByPageId($settings->getRedirectAfterCancel());
    header('Location: ' . $url);
    exit();
}