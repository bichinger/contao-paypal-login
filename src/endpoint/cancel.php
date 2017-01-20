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


$token = \Input::get('token');

if (!empty($token)) {

    $settings = \Bichinger\PayPalLogin\PayPalSettings::getInstance();
    \System::log($GLOBALS['TL_LANG']['MSC']['payment_cancel_error'], TL_ERROR);
    $url = \Bichinger\Helper\UrlHelper::getUrlByPageId($settings->getRedirectAfterCancel());
    header('Location: ' . $url);
    exit();
}

exit();