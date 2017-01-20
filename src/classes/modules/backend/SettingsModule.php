<?php

/**
 * Namespace
 */
namespace Bichinger\PayPalLogin\Modules\Backend;

use \Bichinger\PayPalLogin\PayPalRequest;
use \Bichinger\PayPalLogin\PayPalSettings;
use Bichinger\PayPalLogin\Exception\ValidationException;


/**
 * Class SettingsModule
 *
 * @copyright  Bichinger Software & Consulting 2017
 * @author     Bichinger Software & Consulting
 * @package    Devtools
 */
class SettingsModule extends \BackendModule
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_paypal_settings';


    /**
     * Generate the module
     */
    protected function compile()
    {
        $GLOBALS['TL_CSS'][] = 'system/modules/bichinger-paypal-login/assets/css/settings.css';
        $act = \Input::get('act');
        if (empty($act)) {
            header("Location: " . \Environment::get('base') . "/contao/main.php?do=paypal-login-settings&act=edit&id=1");
        }
    }
}
