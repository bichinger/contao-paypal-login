<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   PayPalLogin
 * @author    Bichinger Software & Consulting
 * @license   -
 * @copyright Bichinger Software & Consulting 2017
 */

// Backend Modules

$GLOBALS['BE_MOD']['bichinger-paypal-login'] = array(
    'paypal-login-settings' => array(
        'icon' => 'system/modules/bichinger-paypal-login/assets/icon.png',
        'callback' => 'Bichinger\PayPalLogin\Modules\Backend\SettingsModule',
    )
);


// Hooks

$GLOBALS['TL_HOOKS']['createNewUser'][] = array('Bichinger\PayPalLogin\Paygate', 'redirectMemberToPayPal');