<?php


namespace Bichinger\PayPalLogin;


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

/**
 * Class Endpoint
 * @package Bichinger\PayPalLogin
 */
class Endpoint
{


    /** @var string */
    const PAYMENT_APPROVED = "approved";

    /** @var string */
    const PAYMENT_FAILED = "failed";


    /**
     * Handle payment redirection from paypal to execute payment
     *
     * @param string$paymentId
     * @param string $token
     * @param string $PayerID
     * @return Payment
     */

    public static function handlePayment($paymentId, $token, $PayerID)
    {
        // get paypal login settings
        $settings = PayPalSettings::getInstance();

        // create api context
        $apiContext = PayPalRequest::getApiContext($settings);

        // retrieve payment opbject by payment id
        $payment = Payment::get($paymentId, $apiContext);


        // create payment execution object and set payer_id
        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);

        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        // set details of transaction
        // set shipping costs
        $details->setShipping(0);
        // set tax values
        $details->setTax(0);
        // set subtotal
        $details->setSubtotal($settings->getItemAmount());
        // set currency code
        $amount->setCurrency($settings->getCurrencyCode());
        // set total of transaction
        $amount->setTotal($settings->getItemAmount());
        // attach details object
        $amount->setDetails($details);
        // attach amount-object to transaction
        $transaction->setAmount($amount);

        // add transaction to execution object
        $execution->addTransaction($transaction);

        // execute payment
        $result = $payment->execute($execution, $apiContext);
        // retrieve payment to check state
        $payment = Payment::get($paymentId, $apiContext);

        // if state failed, throw payment exception with reason
        if ($payment->getState() == self::PAYMENT_FAILED) {
            throw new PaymentException($payment->getFailureReason(), $payment);
        }

        // if state is not failed or approved, exit with exception due to not supported state received from paypal
        if($payment->getState() != self::PAYMENT_FAILED && $payment->getState() != self::PAYMENT_APPROVED){
            throw new \Exception("payment state not supported yet: ".$payment->getState());
        }

        return $payment;
    }

}