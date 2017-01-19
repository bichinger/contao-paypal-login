<?php
die();

/*
 * GET:

 array(3) {
  ["paymentId"]=>
  string(28) "PAY-2NJ43687WT108160YLCAO34Y"
  ["token"]=>
  string(20) "EC-40V07320DH8757723"
  ["PayerID"]=>
  string(13) "MK5L9V77DNAQA"
}


 */


$paymentId = $_GET['paymentId'];
$payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    $transaction = new Transaction();
    $amount = new Amount();
    $details = new Details();

    $details->setShipping(2.2)
        ->setTax(1.3)
        ->setSubtotal(17.50);

    $amount->setCurrency('USD');
    $amount->setTotal(21);
    $amount->setDetails($details);
    $transaction->setAmount($amount);

    $execution->addTransaction($transaction);


$result = $payment->execute($execution, $apiContext);