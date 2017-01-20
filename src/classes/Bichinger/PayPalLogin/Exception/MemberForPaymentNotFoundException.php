<?php


namespace Bichinger\PayPalLogin\Exception;


use Exception;

/**
 * Class MemberForPaymentNotFoundException
 * @package Bichinger\PayPalLogin\Exception
 */
class MemberForPaymentNotFoundException extends \Exception
{

    /** @var */
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
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }
}