<?php
session_start();

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

require 'bootstrap.php';

$uuid = $_SESSION['sale_uuid'];
$payer = new Payer();
$payer->setPaymentMethod('paypal');
// Set some example data for the payment.
$currency = 'MXN';
$amountPayable = $_SESSION['raffle_price'];
$invoiceNumber = uniqid();
$amount = new Amount();
$amount->setCurrency($currency)
	->setTotal($amountPayable);
$transaction = new Transaction();
$transaction->setAmount($amount)
	->setDescription('Ticket de rifa')
	->setInvoiceNumber($invoiceNumber);
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl($paypalConfig['return_url'])
	->setCancelUrl($paypalConfig['cancel_url']);
$payment = new Payment();
$payment->setIntent('sale')
	->setPayer($payer)
	->setTransactions([$transaction])
	->setRedirectUrls($redirectUrls);
try {
	$payment->create($apiContext);
} catch (Exception $e) {
	throw new Exception('Unable to create link for payment');
}
try {
    require("../dbengine/dbconnect.php");
	$updatedata = mysqli_query($conn, "UPDATE ticket_buy SET invoice='$invoiceNumber' WHERE uuid='$uuid'");
} catch (Exception $e) {
	throw new Exception('Unable to save invoice');
}
header('location:' . $payment->getApprovalLink());
exit(1);
