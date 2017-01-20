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
 * Namespace
 */
namespace Bichinger\PayPalLogin;

use \Bichinger\PayPalLogin\Exception\ValidationException;


/**
 * Class PayPalSettings
 * @package Bichinger\PayPalLogin
 */
class PayPalSettings
{

    /** @var null */
    protected static $_instance = null;

    /** @var */
    private $client_id;
    /** @var */
    private $secret;

    /** @var */
    private $item_amount;
    /** @var */
    private $item_name;

    /** @var */
    private $currency_code;
    /** @var */
    private $mode;


    /** @var */
    private $member_group;
    /** @var */
    private $redirect_after_approval;
    /** @var */
    private $redirect_after_error;
    /** @var */
    private $redirect_after_cancel;

    /**
     * @return mixed
     */
    public function getRedirectAfterCancel()
    {
        return $this->redirect_after_cancel;
    }

    /**
     * @param mixed $redirect_after_cancel
     */
    public function setRedirectAfterCancel($redirect_after_cancel)
    {
        $this->redirect_after_cancel = $redirect_after_cancel;
    }


    /**
     * @return mixed
     */
    public function getMemberGroup()
    {
        return $this->member_group;
    }

    /**
     * @param mixed $member_group
     */
    public function setMemberGroup($member_group)
    {
        $this->member_group = $member_group;
    }

    /**
     * @return mixed
     */
    public function getRedirectAfterApproval()
    {
        return $this->redirect_after_approval;
    }

    /**
     * @param mixed $redirect_after_approval
     */
    public function setRedirectAfterApproval($redirect_after_approval)
    {
        $this->redirect_after_approval = $redirect_after_approval;
    }

    /**
     * @return mixed
     */
    public function getRedirectAfterError()
    {
        return $this->redirect_after_error;
    }

    /**
     * @param mixed $redirect_after_error
     */
    public function setRedirectAfterError($redirect_after_error)
    {
        $this->redirect_after_error = $redirect_after_error;
    }

    /**
     * Validates paypal credentials
     *
     * @param $oDc
     */
    public function validateCredentials($oDc)
    {
        $settings = PayPalSettings::getInstance();
        $settings->setClientId($oDc->activeRecord->paypal_client_id);
        $settings->setSecret($oDc->activeRecord->paypal_secret);

        $valid = PayPalRequest::checkCredentials($settings);
        if (!$valid) {
            \Contao\Message::addError($GLOBALS['TL_LANG']['tl_paypal_login_settings']['errors']['invalid_credentials']);
        }
    }


    /**
     * Initialize settings entry
     */
    public function initSettings(){
        $result = \Database::getInstance()->prepare("SELECT count(id) as settings_count FROM tl_paypal_login_settings")->execute()->fetchAssoc();
        if($result['settings_count'] == 0){
            $settings = self::getEmptySettingsClass();
            // create paypal-approved-member-group
            $result = \Database::getInstance()->prepare("INSERT INTO `tl_member_group` (`tstamp`, `name`, `redirect`, `jumpTo`, `disable`, `start`, `stop`)VALUES(".time().", 'PayPal Paid', '', 0, '', '', '');")->execute();
            // set created memer group as default
            $settings->setMemberGroup($result->insertId);

            // create initial settings row
            \Database::getInstance()->prepare("REPLACE INTO tl_paypal_login_settings (id, paypal_client_id, paypal_secret, paypal_item_name, paypal_item_amount, paypal_currency_code, paypal_mode, member_group, redirect_after_approval, redirect_after_error, redirect_after_cancel) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute($settings->getClientId(), $settings->getSecret(), $settings->getItemName(), $settings->getItemAmount(), $settings->getCurrencyCode(), $settings->getMode(), $settings->getMemberGroup(), $settings->getRedirectAfterApproval(), $settings->getRedirectAfterError(), $settings->getRedirectAfterCancel());
        }

    }

    /**
     * @return PayPalSettings|null
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            $settings_result = \Database::getInstance()->prepare('SELECT * FROM tl_paypal_login_settings WHERE id = 1')->execute()->fetchAssoc();
            if ($settings_result !== false) {
                $settings = self::createSettingsObject($settings_result);
                self::$_instance = $settings;
            } else {
                return self::getEmptySettingsClass();
            }
        }
        return self::$_instance;
    }

    /**
     * @param $settings
     * @return PayPalSettings
     */
    private static function createSettingsObject($settings_array)
    {
        $settings = new PayPalSettings();
        $settings->setClientId($settings_array['paypal_client_id']);
        $settings->setSecret($settings_array['paypal_secret']);
        $settings->setItemAmount($settings_array['paypal_item_amount']);
        $settings->setItemName($settings_array['paypal_item_name']);
        $settings->setCurrencyCode($settings_array['paypal_currency_code']);
        $settings->setMode($settings_array['paypal_mode']);
        $settings->setMemberGroup($settings_array['member_group']);
        $settings->setRedirectAfterApproval($settings_array['redirect_after_approval']);
        $settings->setRedirectAfterError($settings_array['redirect_after_error']);

        return $settings;
    }

    /**
     * @return PayPalSettings
     */
    private static function getEmptySettingsClass()
    {
        $settings = new PayPalSettings();
        $settings->setClientId("");
        $settings->setSecret("");
        $settings->setItemAmount("");
        $settings->setItemName("");
        $settings->setCurrencyCode("");
        $settings->setMode("");
        $settings->setMemberGroup(0);
        $settings->setRedirectAfterApproval(0);
        $settings->setRedirectAfterError(0);
        $settings->setRedirectAfterCancel(0);

        return $settings;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getItemName()
    {
        return $this->item_name;
    }

    /**
     * @param mixed $item_name
     */
    public function setItemName($item_name)
    {
        $this->item_name = $item_name;
    }

    /**
     * @return mixed
     */
    public function getItemAmount()
    {
        return $this->item_amount;
    }

    /**
     * @param mixed $amount
     */
    public function setItemAmount($amount)
    {

        // if only comma exists, replace with dot
        if (stristr($amount, ",") !== false && stristr($amount, ".") === false) {
            $amount = preg_replace('/([\,])/', ".", $amount);
        }

        // if dot and comma exists
        if (stristr($amount, ",") !== false && stristr($amount, ".") !== false) {
            // if comma behind dot (german notation)
            if (strpos($amount, ",") > strpos($amount, ".")) {
                $amount = preg_replace('/([\.])/', "", $amount);
                $amount = preg_replace('/([\,])/', ".", $amount);
            }
        }

        $this->item_amount = floatval($amount);
    }

    /**
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @param mixed $currency_code
     */
    public function setCurrencyCode($currency_code)
    {
        $this->currency_code = $currency_code;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

}
