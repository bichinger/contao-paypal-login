<?php

/**
 * Namespace
 */
namespace Bichinger\PayPalLogin\Modules\Backend;

use \Bichinger\PayPalLogin\PayPalRequest;
use \Bichinger\PayPalLogin\Settings;
use Bichinger\PayPalLogin\ValidationException;


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
        $form_data = \Input::post('paypal_data');


        if (!empty($form_data)) {

            try {
                Settings::save($form_data);
                header("Location: main.php?do=paypal-login-settings");
            } catch (ValidationException $e) {
                $this->Template->errors_headline = $e->getMessage();
                $this->Template->errors = $e->getErrors();
            }
        }

        $this->Template->settings = Settings::getInstance();
        PayPalRequest::redirectToPayPal($this->Template->settings);

    }
}
