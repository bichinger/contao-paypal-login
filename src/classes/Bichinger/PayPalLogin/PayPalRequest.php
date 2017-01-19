<?php

namespace Bichinger\PayPalLogin;


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Exception\PayPalInvalidCredentialException;
use PayPal\Rest\ApiContext;

/**
 * Class PayPalRequest
 * @package Bichinger\PayPalLogin
 */
class PayPalRequest
{


    private static function getApiContext(&$paypalLoginSettings)
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypalLoginSettings->getClientId(),
                $paypalLoginSettings->getSecret()
            )
        );


        $apiContext->setConfig(
            array(
                'mode' => $paypalLoginSettings->getMode(),
                'log.LogEnabled' => false,
                'cache.enabled' => false,
            )
        );

        return $apiContext;
    }


    public static function checkCredentials(&$paypalLoginSettings)
    {
        $apiContext = self::getApiContext($paypalLoginSettings);
        try {
            $params = array('count' => 1, 'start_index' => 1);
            $payments = Payment::all($params, $apiContext);

            return true;
        } catch (PayPalInvalidCredentialException $ex) {
            echo "<pre>";
            var_dump($ex->getMessage());
            die();
            return false;
        } catch (\Exception $ex) {
            echo "<pre>";
            var_dump($ex->getMessage());
            die();
            return false;
        }

    }


    /**
     * @param Settings $paypalLoginSettings
     */
    public static function redirectToPayPal(Settings $paypalLoginSettings)
    {

        $apiContext = self::getApiContext($paypalLoginSettings);


        // Create new payer and method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");


        $item1 = new Item();
        $item1->setName($paypalLoginSettings->getItemName())
            ->setCurrency($paypalLoginSettings->getCurrencyCode())
            ->setQuantity(1)
            ->setPrice($paypalLoginSettings->getItemAmount());

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $details = new Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($paypalLoginSettings->getItemAmount());

        $amount = new Amount();
        $amount->setCurrency($paypalLoginSettings->getCurrencyCode())
            ->setTotal($paypalLoginSettings->getItemAmount())
            ->setDetails($details);


        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($paypalLoginSettings->getTransactionDescription());

        // Set redirect urls
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(\Environment::get('base') . "/system/modules/bichinger-paypal-login/endpoint/success.php")
            ->setCancelUrl(\Environment::get('base') . "/system/modules/bichinger-paypal-login/endpoint/cancel.php");


        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));


        try {
            $payment->create($apiContext);

            // Get PayPal redirect URL and redirect user
            $approvalUrl = $payment->getApprovalLink();

            header('Location: ' . $approvalUrl);

            // REDIRECT USER TO $approvalUrl
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }
}