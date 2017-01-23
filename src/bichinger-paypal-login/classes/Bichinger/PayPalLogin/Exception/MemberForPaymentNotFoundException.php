<?php


namespace Bichinger\PayPalLogin\Exception;


use Exception;
use PayPal\Api\Payment;

/**
 * Class MemberForPaymentNotFoundException
 * @package Bichinger\PayPalLogin\Exception
 */
class MemberForPaymentNotFoundException extends \Exception
{

    /** @var Payment */
    private $payment;

    /**
     * MemberForPaymentNotFoundException constructor.
     * @param string $errors
     */
    public function __construct($errors)
    {
        $this->setPayment($errors);
        parent::__construct($GLOBALS['TL_LANG']['MSC']['member_for_payment_not_found_error']);
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }
}