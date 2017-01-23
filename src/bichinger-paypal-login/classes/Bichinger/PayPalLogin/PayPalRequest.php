<?php

namespace Bichinger\PayPalLogin;


use Bichinger\Helper\UrlHelper;
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


    /**
     * Check paypal credentials by trying to get payments from account
     *
     * @param PayPalSettings $paypalLoginSettings
     * @return bool
     */
    public static function checkCredentials(&$paypalLoginSettings)
    {
        // create api context with settings
        $apiContext = self::getApiContext($paypalLoginSettings);
        try {
            // query paypal to get payments by filter criteria
            $params = array('count' => 1, 'start_index' => 1);
            $payments = Payment::all($params, $apiContext);

            return true;
        } catch (PayPalInvalidCredentialException $ex) {
            return false;
        } catch (\Exception $ex) {
            return false;
        }

    }

    /**
     * Create API-context-object to authenticate api calls.
     *
     * @param $paypalLoginSettings
     * @return ApiContext
     */
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
     * Redirect Member to paypal after registration
     *
     * @param PayPalSettings $paypalLoginSettings
     */
    public static function redirectMemeberToPayPal($member_id, PayPalSettings $paypalLoginSettings)
    {

        $apiContext = self::getApiContext($paypalLoginSettings);

        // Create new payer and method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // create item to display at paypal cart
        $item1 = new Item();
        $item1->setName($paypalLoginSettings->getItemName());
        $item1->setCurrency($paypalLoginSettings->getCurrencyCode());
        $item1->setQuantity(1);
        $item1->setPrice($paypalLoginSettings->getItemAmount());

        // create itemlist for paypal cart
        $itemList = new ItemList();
        $itemList->setItems(array($item1));


        /*
         *  build transaction object
         */
        // create details
        $details = new Details();
        $details->setShipping(0);
        $details->setTax(0);
        $details->setSubtotal($paypalLoginSettings->getItemAmount());
        // create amount
        $amount = new Amount();
        $amount->setCurrency($paypalLoginSettings->getCurrencyCode());
        $amount->setTotal($paypalLoginSettings->getItemAmount());
        $amount->setDetails($details);
        // create transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setItemList($itemList);

        // Set redirect urls
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(\Environment::get('base') . "/system/modules/bichinger-paypal-login/endpoint/success.php");
        $redirectUrls->setCancelUrl(\Environment::get('base') . "/system/modules/bichinger-paypal-login/endpoint/cancel.php");


        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent('sale');
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions(array($transaction));


        try {

            // make api call to create payment
            $payment->create($apiContext);
            // save payment id to member
            Paygate::updateMemberPaymentId($payment->getId(), $member_id);

            // Get PayPal redirect URL and redirect user
            $approvalUrl = $payment->getApprovalLink();

            // redirect member to approvalUrl
            header('Location: ' . $approvalUrl);
            exit();

        } catch (PayPal\Exception\PayPalConnectionException $ex) {

            \System::log($ex->getMessage(), __METHOD__, TL_ERROR);
            $url = UrlHelper::getUrlByPageId($paypalLoginSettings->getRedirectAfterError());
            header('Location: ' . $url);
            exit();

        } catch (Exception $ex) {

            \System::log($ex->getMessage(), __METHOD__, TL_ERROR);
            $url = UrlHelper::getUrlByPageId($paypalLoginSettings->getRedirectAfterError());
            header('Location: ' . $url);
            exit();

        }
    }
}