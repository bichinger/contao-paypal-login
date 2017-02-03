<?php

// add checkbox to define paygate state
$GLOBALS['TL_DCA']['tl_module']['fields']['enable_paypal_login'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['enable_paypal_login'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array(),
    'sql' => "char(1) NOT NULL default ''"
);


$GLOBALS['TL_DCA']['tl_module']['palettes']['registration'] = str_replace('{config_legend},', '{config_legend},enable_paypal_login,', $GLOBALS['TL_DCA']['tl_module']['palettes']['registration']);