<?php

namespace Bichinger\PayPalLogin;


use Bichinger\UniqueId;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
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


    public static function checkCredentials(&$paypalLoginSettings)
    {
        $apiContext = self::getApiContext($paypalLoginSettings);
        try {
            $params = array('count' => 1, 'start_index' => 1);
            $payments = Payment::all($params, $apiContext);

            return true;
        } catch (PayPalInvalidCredentialException $ex) {
            return false;
        } catch (\Exception $ex) {
            return false;
        }

    }

    public static function getApiContext(&$paypalLoginSettings)
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

    /**
     * @param PayPalSettings $paypalLoginSettings
     */
    public static function redirectMemeberToPayPal($member_id, PayPalSettings $paypalLoginSettings)
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

            Paygate::updateMemberPaymentId($payment->getId(), $member_id);

            // Get PayPal redirect URL and redirect user
            $approvalUrl = $payment->getApprovalLink();

            // REDIRECT USER TO $approvalUrl
            header('Location: ' . $approvalUrl);

        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo "<pre>";
            var_dump($ex->getData());
            var_dump($ex->getMessage());
            die();
        } catch (Exception $ex) {
            echo "<pre>";
            var_dump($ex->getMessage());
            die();
        }
    }
}