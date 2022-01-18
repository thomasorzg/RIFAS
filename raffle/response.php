<?php
session_start();

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
require 'bootstrap.php';
if (empty($_GET['paymentId']) || empty($_GET['PayerID'])) {
    throw new Exception('The response is missing the paymentId and PayerID');
}
$paymentId = $_GET['paymentId'];
$payment = Payment::get($paymentId, $apiContext);
$execution = new PaymentExecution();
$execution->setPayerId($_GET['PayerID']);
try {
    // Take the payment
    $payment->execute($execution, $apiContext);
    try {
        $payment = Payment::get($paymentId, $apiContext);
        $data = [
            'transaction_id' => $payment->getId(),
            'payment_amount' => $payment->transactions[0]->amount->total,
            'payment_status' => $payment->getState(),
            'invoice_id' => $payment->transactions[0]->invoice_number
        ];
        $invoice_id = $payment->transactions[0]->invoice_number;
        require("../dbengine/dbconnect.php");
        $updatedata = mysqli_query($conn, "UPDATE ticket_buy SET paid=1 WHERE invoice='$invoice_id'");
        
        $saledata = mysqli_query($conn, "SELECT uuid FROM ticket_buy WHERE invoice='$invoice_id' LIMIT 1");
        $sale = mysqli_fetch_array($saledata);
        $uuid = $sale['uuid'];
        
        header('location:' . "raffle.php?ticket_sale=" . $uuid);
        exit(1);
    } catch (Exception $e) {
        // Failed to retrieve payment from PayPal
    }
} catch (\PayPal\Exception\PayPalConnectionException $ex) {
    var_dump(json_decode($ex->getData()));
    exit(1);
}