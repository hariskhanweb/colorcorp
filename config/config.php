<?php
return [
"paymentconstant" => [
    'ERROR_BEARER_TOKEN_NOT_SET' => 'bearer_token_not_set',
   	'ERROR_MERCHANT_CODE_NOT_SET' => 'merchant_code_not_set',
   	'ERROR_INVALID_RESPONSE' => 'invalid_server_response',
   	'ERROR_INVALID_RESPONSE_DETAILS' => 'Invalid response from gateway sever',
    'ERROR_TRANSACTION' => 'An error occurred during transaction process. Please try again',
   	'LIVE_URL' => 'https://payments.auspost.net.au/v2/',
    'SANDBOX_URL' => 'https://payments-stest.npe.auspost.zone/v2/',
    'LIVE_TOKEN' => 'https://welcome.api2.auspost.com.au/oauth/token',
    'PAYMENT_WRITE' => 'https://api.payments.auspost.com.au/payhive/payments/write',
    'SANDBOX_PAYMENT_INSTRUMENTS' => 'https://welcome.api2.sandbox.auspost.com.au/oauth/token'
  ]
];