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


/**
 * Table tl_paypal_login_settings
 */
$GLOBALS['TL_DCA']['tl_paypal_login_settings'] = array
(

    // Config
    'config' => array
    (
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'notCreatable' => true,
        'notCopyable' => true,
        'notDeletable' => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        ),
        'onsubmit_callback' => array(
            // validate credentials on save
            array('Bichinger\PayPalLogin\PayPalSettings', "validateCredentials")
        ),
        'onload_callback' => array(
            // check for first initialisation
            array('Bichinger\PayPalLogin\PayPalSettings', "initSettings")
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode' => 0,
            'fields' => array('paypal_client_id'),
            'flag' => 1
        ),
        'label' => array
        (
            'fields' => array('paypal_client_id'),
            'format' => 'PayPal Settings'
        ),
        'global_operations' => array
        (),
        'operations' => array
        (
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
        )
    ),

    // Select
    'select' => array
    (
        'buttons_callback' => array()
    ),

    // Edit
    'edit' => array
    (
        'buttons_callback' => array()
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__' => array(),
        'default' => 'paypal_client_id,paypal_secret,paypal_item_amount,paypal_item_name;paypal_currency_code,paypal_mode;member_group,redirect_after_approval,redirect_after_error, redirect_after_cancel'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        '' => ''
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'paypal_client_id' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_client_id'],
            'exclude' => true,
            'inputType' => 'text',
            'default' => '',
            'eval' => array('mandatory' => true, 'maxlength' => 255),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'paypal_secret' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_secret'],
            'exclude' => true,
            'inputType' => 'text',
            'default' => '',
            'eval' => array('mandatory' => true, 'maxlength' => 255),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'paypal_item_amount' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_item_amount'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'rgxp' => 'digit'),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'paypal_item_name' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_item_name'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'paypal_currency_code' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_currency_code'],
            'exclude' => true,
            'inputType' => 'select',
            'options' => array('EUR', 'USD'),
            'default' => 'EUR',
            'eval' => array('mandatory' => true, 'maxlength' => 3),
            'sql' => "varchar(3) NOT NULL default 'EUR'"
        ),
        'paypal_mode' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_mode'],
            'exclude' => true,
            'inputType' => 'select',
            'options' => array('sandbox' => 'Sandbox', 'production' => 'Production'),
            'default' => 'sandbox',
            'eval' => array('mandatory' => true, 'maxlength' => 12),
            'sql' => "varchar(12) NOT NULL default 'sandbox'"
        ),
        'member_group' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['member_group'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_member_group.name',
            'eval' => array('multiple' => false, 'mandatory' => true,),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'redirect_after_approval' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['redirect_after_approval'],
            'exclude' => true,
            'inputType' => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval' => array('fieldType' => 'radio', 'mandatory' => true,),
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => array('type' => 'hasOne', 'load' => 'lazy')
        ),
        'redirect_after_error' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['redirect_after_error'],
            'exclude' => true,
            'inputType' => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval' => array('fieldType' => 'radio', 'mandatory' => true,),
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => array('type' => 'hasOne', 'load' => 'lazy')
        ),
        'redirect_after_cancel' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['redirect_after_cancel'],
            'exclude' => true,
            'inputType' => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval' => array('fieldType' => 'radio', 'mandatory' => true,),
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => array('type' => 'hasOne', 'load' => 'lazy')
        ),
    )
);
