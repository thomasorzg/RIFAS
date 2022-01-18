<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
require '../vendor/autoload.php';

$enableSandbox = false;

// $paypalConfig = [
//     'client_id' => 'AQ_QS6_GpQhsQHgsu1g_sUfvKApEH-jRo8oB5EebnzoFmu6Ze2drGrpqm8Wu13xCjSTNBo_6OURFLTOd',
//     'client_secret' => 'EJxV6IZUEspgODccptBycsdYvZLz9uPzsZ_qZr7AGw9gcxPSuo8-Q0EK6wmwyick99qWxulAUmLugfZ1',
//     'return_url' => 'http://boletos.dagacoding.com/raffle/response.php',
//     'cancel_url' => 'http://boletos.dagacoding.com/payment-cancelled.html'
// ];
$paypalConfig = [
    'client_id' => 'AYqGTTvFyYxkKp_htEWxRvw8N2abtAiL3P2zIaGPmxwmsDn2v2Bl9M_sgE2a3KnsUZ6_tQ8LdxYppTkC',
    'client_secret' => 'EAhVvvR53XvOxsN3LsL4x8fXdPYVBZ1gURLn_T0wSKxIiK-NvVYBN5uRH0JAqYLe_X0Sw9M-_J-mFsFT',
    'return_url' => 'http://boletos.rifasmalverdealvaroarguelles.com/raffle/response.php',
    'cancel_url' => 'http://boletos.rifasmalverdealvaroarguelles.com/payment-cancelled.html'
];

$apiContext = getApiContext($paypalConfig['client_id'], $paypalConfig['client_secret'], $enableSandbox);

/**
 * Set up a connection to the API
 *
 * @param string $clientId
 * @param string $clientSecret
 * @param bool   $enableSandbox Sandbox mode toggle, true for test payments
 * @return \PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret, $enableSandbox = false)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential($clientId, $clientSecret)
    );
    $apiContext->setConfig([
        'mode' => $enableSandbox ? 'sandbox' : 'live'
    ]);
    return $apiContext;
}