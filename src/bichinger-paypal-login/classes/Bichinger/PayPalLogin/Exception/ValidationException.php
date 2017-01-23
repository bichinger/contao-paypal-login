<?php


namespace Bichinger\PayPalLogin\Exception;


use Exception;

/**
 * Class ValidationException
 * @package Bichinger\PayPalLogin\Exception
 */
class ValidationException extends \Exception
{

    /** @var array */
    private $errors;

    /**
     * ValidationException constructor.
     * @param string $errors
     */
    public function __construct($errors)
    {
        $this->setErrors($errors);
        parent::__construct($GLOBALS['TL_LANG']['MSC']['validation_error']);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
}