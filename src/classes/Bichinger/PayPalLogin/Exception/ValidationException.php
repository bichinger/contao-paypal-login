<?php


namespace Bichinger\PayPalLogin\Exception;


use Exception;

class ValidationException extends \Exception
{

    private $errors;

    public function __construct($errors)
    {
        $this->setErrors($errors);
        parent::__construct($GLOBALS['TL_LANG']['MSC']['validation_error']);
    }

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
}