<?php


namespace Bichinger\PayPalLogin\Exception;


use Exception;
use PayPal\Api\Payment;

/**
 * Class PaymentException
 * @package Bichinger\PayPalLogin\Exception
 */
class PaymentException extends \Exception
{

    /** @var Payment */
    private $payment;

    /** @var string */
    private $reason;

    /**
     * PaymentException constructor.
     * @param string $reason
     * @param Payment $payment
     */
    public function __construct($reason, $payment)
    {
        $this->setReason($reason);
        parent::__construct($GLOBALS['TL_LANG']['MSC']['payment_error']);
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

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }
}