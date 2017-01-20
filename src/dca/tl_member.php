<?php


/**
 * Add fields to tl_user_group
 */
$GLOBALS['TL_DCA']['tl_member']['fields']['paypal_payment_id'] = array
(
    'sql' => "varchar(255) NOT NULL default ''"
);
