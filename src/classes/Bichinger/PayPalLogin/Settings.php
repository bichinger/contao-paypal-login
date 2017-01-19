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


/**
 * Class Settings
 *
 * @copyright  Bichinger Software & Consulting 2017
 * @author     Bichinger Software & Consulting
 * @package    Devtools
 */
/**
 * Class Settings
 * @package Bichinger\PayPalLogin
 */
/**
 * Class Settings
 * @package Bichinger\PayPalLogin
 */
class Settings
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
    private $transaction_description;

    /** @var */
    private $mode;

    /**
     * @return Settings|null
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
     * @return Settings
     */
    private static function createSettingsObject($settings_array)
    {
        $settings = new Settings();
        $settings->setClientId($settings_array['paypal_client_id']);
        $settings->setSecret($settings_array['paypal_secret']);
        $settings->setTransactionDescription($settings_array['paypal_transaction_description']);
        $settings->setItemAmount($settings_array['paypal_item_amount']);
        $settings->setItemName($settings_array['paypal_item_name']);
        $settings->setCurrencyCode($settings_array['paypal_currency_code']);
        $settings->setMode($settings_array['paypal_mode']);

        return $settings;
    }

    private static function getEmptySettingsClass()
    {
        $settings = new Settings();
        $settings->setClientId("");
        $settings->setSecret("");
        $settings->setTransactionDescription("");
        $settings->setItemAmount("");
        $settings->setItemName("");
        $settings->setCurrencyCode("");
        $settings->setMode("");

        return $settings;
    }

    /**
     * @param $settings
     */
    public static function save($posted_settings)
    {
        $settings = self::createSettingsObject($posted_settings);

        $errors = self::is_valid($settings);
        if (count($errors) == 0) {
            \Database::getInstance()->prepare("REPLACE INTO tl_paypal_login_settings (id, paypal_client_id, paypal_secret, paypal_transaction_description, paypal_item_name, paypal_item_amount, paypal_currency_code, paypal_mode) VALUES (1, ?, ?, ?, ?, ?, ?, ?)")->execute($settings->getClientId(), $settings->getSecret(), $settings->getTransactionDescription(), $settings->getItemName(), $settings->getItemAmount(), $settings->getCurrencyCode(), $settings->getMode());
        } else {
            throw new ValidationException($errors);
        }
    }

    /**
     * @param $posted_settings
     * @return array
     */
    private static function is_valid($settings)
    {
        $errors = array();
        $credentialsValid = PayPalRequest::checkCredentials($settings);

        if (!$credentialsValid) {
            $errors[] = $GLOBALS['TL_LANG']['tl_paypal_login_settings']['errors']['invalid_credentials'];
        }

        return $errors;
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
    public function getTransactionDescription()
    {
        return $this->transaction_description;
    }

    /**
     * @param mixed $transaction_description
     */
    public function setTransactionDescription($transaction_description)
    {
        $this->transaction_description = $transaction_description;
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
