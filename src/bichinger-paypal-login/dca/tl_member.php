<?php


/**
 * Add paypal_payment_id field to tl_member
 */
$GLOBALS['TL_DCA']['tl_member']['fields']['paypal_payment_id'] = array
(
    'sql' => "varchar(255) NOT NULL default ''"
);
