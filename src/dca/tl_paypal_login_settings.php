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
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array(''),
            'flag'                    => 1
        ),
        'label' => array
        (
            'fields'                  => array(''),
            'format'                  => '%s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
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
        '__selector__'                => array(''),
        'default'                     => '{title_legend},title;'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        ''                            => ''
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'paypal_client_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_client_id'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'default'                 => '',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'paypal_secret' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_secret'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'default'                 => '',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'paypal_transaction_description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_transaction_description'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        'paypal_item_amount' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_item_amount'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        'paypal_item_name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_item_name'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'paypal_currency_code' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_currency_code'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'default'                 => 'EUR',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>3),
            'sql'                     => "varchar(3) NOT NULL default 'EUR'"
        ),
        'paypal_mode' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_paypal_login_settings']['paypal_mode'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'default'                 => 'sandbox',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>12),
            'sql'                     => "varchar(12) NOT NULL default 'sandbox'"
        )
    )
);
