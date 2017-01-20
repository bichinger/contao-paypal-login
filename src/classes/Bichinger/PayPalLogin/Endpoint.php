<?php


namespace Bichinger\PayPalLogin;


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class Endpoint
{


    const PAYMENT_APPROVED = "approved";
    const PAYMENT_FAILED = "failed";


    public static function handlePayment($paymentId, $token, $PayerID)
    {
        $settings = PayPalSettings::getInstance();
        $apiContext = PayPalRequest::getApiContext($settings);

        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);


        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($settings->getItemAmount());

        $amount->setCurrency($settings->getCurrencyCode());
        $amount->setTotal($settings->getItemAmount());
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);


        $result = $payment->execute($execution, $apiContext);
        $payment = Payment::get($paymentId, $apiContext);

        if ($payment->getState() == self::PAYMENT_FAILED) {
            throw new PaymentException($payment->getFailureReason(), $payment);
        }

        return $payment;
    }

}