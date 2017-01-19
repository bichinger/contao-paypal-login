<?php


namespace Bichinger\PayPalLogin;


use Exception;

class ValidationException extends \Exception
{

    private $errors;

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function __construct($errors)
    {
        $this->setErrors($errors);
        parent::__construct($GLOBALS['TL_LANG']['MSC']['validation_error']);
    }
}