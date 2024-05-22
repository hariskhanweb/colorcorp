<?php
namespace App\Http\Traits;
use App\Models\User;

trait SecurePayTrait {

	/*public const URL_SANDBOX_SCRIPT = 'https://payments-stest.npe.auspost.zone/v3/ui/client/securepay-ui.min.js';
    public const URL_LIVE_SCRIPT = 'https://payments.auspost.net.au/v3/ui/client/securepay-ui.min.js';*/

    // public const URL_SANDBOX_API = 'https://payments-stest.npe.auspost.zone/v2/';
    // public const URL_LIVE_API = 'https://payments.auspost.net.au/v2/';

    /*public const SCOPE_PAYMENT_READ = 'https://api.payments.auspost.com.au/payhive/payments/read';
    public const SCOPE_PAYMENT_WRITE = 'https://api.payments.auspost.com.au/payhive/payments/write';
    public const SCOPE_PAYMENT_INSTRUMENTS_READ = 'https://api.payments.auspost.com.au/payhive/payment-instruments/read';
    public const SCOPE_PAYMENT_INSTRUMENTS_WRITE = 'https://api.payments.auspost.com.au/payhive/payment-instruments/write';

    public const 'curl_error' = 'curl_error';
    public const ERROR_BEARER_TOKEN_NOT_SET = 'bearer_token_not_set';
    public const ERROR_MERCHANT_CODE_NOT_SET = 'merchant_code_not_set';
    public const ERROR_INVALID_RESPONSE = 'invalid_server_response';
    public const ERROR_INVALID_RESPONSE_DETAILS = 'Invalid response from gateway sever.';
*/
    private $bearerToken;
    private $apiUrl;
    private $merchantCode;
    private $storeId;

    public function __construct($storeId = 1)
    {
        $this->storeId = substr($storeId, 0, 24);
    }

    /**
     * Init SecurePayApi object with pre-authorized information
     * @param string $bearerToken Authenticated bearer token
     * @param string $merchantCode Account's merchant code
     * @param bool $isLive Is used for live transactions?
     * @return array
     */
    public function initWithToken(string $bearerToken, string $merchantCode, bool $isLive)
    {
        $this->bearerToken = $bearerToken;
        $this->merchantCode = $merchantCode;
        $this->apiUrl = $isLive ? 'https://payments.auspost.net.au/v2/' : 'https://payments-stest.npe.auspost.zone/v2/';
        return ['error' => false];
    }

    /**
     * Init SecurePayApi object using api provided by payment gateway, request is sent to gateway to retrieve authentication token
     * https://auspost.com.au/payments/docs/securepay/#authentication
     * @param string $clientId
     * @param string $clientSecret
     * @param string $merchantCode
     * @param array $scope
     * @param bool $isLive
     * @return array
     */
    public function initWithAuthentication(string $clientId, string $clientSecret, string $merchantCode, array $scope, bool $isLive)
    {
        $this->merchantCode = $merchantCode;
        $authUrl =
            $isLive ? 'https://welcome.api2.auspost.com.au/oauth/token'	: 'https://welcome.api2.sandbox.auspost.com.au/oauth/token';
        $this->apiUrl = $isLive ? 'https://payments.auspost.net.au/v2/' : 'https://payments-stest.npe.auspost.zone/v2/';



        $ch = curl_init();

		$audience = 'https://api.payments.auspost.com.au';

        curl_setopt($ch, CURLOPT_URL, $authUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&audience=' . rawurlencode( $audience ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);

        $resultArray = json_decode($result, true);
        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['error'])) {
            return ['error' => true,
                'errorCode' => $resultArray['error'],
                'errorDetail' => $resultArray['error_description']];
        }
        $this->bearerToken = $resultArray['access_token'];
        // return $this->bearerToken;
        return ['error' => false];
    }

    /**
     * Create payment
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-create-payment
     * @param string $paymentToken
     * @param string $ipAddress
     * @param int $amount
     * @param string|null $orderId
     * @param string|null $idempotencyKey
     * @return array
     */
    public function createPayment(string $paymentToken, string $ipAddress, int $amount, string $orderId = null, string $idempotencyKey = null)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postFields = [
            'amount' => $amount,
            'merchantCode' => $this->merchantCode,
            'token' => $paymentToken,
            'ip' => $ipAddress
        ];
        if (isset($orderId)) {
            $postFields['orderId'] = $this->storeUniquify($orderId);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        if (isset($idempotencyKey)) {
            $headers[] = 'Idempotency-Key: ' . $this->storeUniquify($idempotencyKey);
        }
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);
        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }
        if (!isset($resultArray['status'])) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => $resultArray
            ];
        }
        if ($resultArray['status'] !== 'paid') {
            return ['error' => true,
                'errorCode' => 'payment_status_' . $resultArray['status'],
                'errorDetail' => $resultArray['gatewayResponseCode'] . ' - ' . $resultArray['gatewayResponseMessage'],
                'gatewayResponseCode' => $resultArray['gatewayResponseCode'],
                'gatewayResponseMessage' => $resultArray['gatewayResponseMessage']
            ];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    private function storeUniquify($string)
    {
        if ($this->isRawString($string)) {
            return $this->storeId . '_' . $string;
        }
        return $string;
    }

    private function isRawString($string)
    {
        return substr($string, 0, strlen($this->storeId)) !== $this->storeId;
    }

    /**
     * Authorize payment
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-create-payment
     * @param string $paymentToken
     * @param string $ipAddress
     * @param int $amount
     * @param string|null $orderId
     * @return array
     */
    public function createPreAuthPayment(string $paymentToken, string $ipAddress, int $amount, string $orderId = null)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'payments/preauths');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postFields = [
            'amount' => $amount,
            'merchantCode' => $this->merchantCode,
            'token' => $paymentToken,
            'ip' => $ipAddress,
            'metadata' => $this->getMetadata(),
        ];
        if (isset($orderId)) {
            $postFields['orderId'] = $this->storeUniquify($orderId);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);
        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }
        if (!isset($resultArray['status'])) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => $resultArray
            ];
        }
        if ($resultArray['status'] !== 'paid') {
            return ['error' => true,
                'errorCode' => 'payment_status_' . $resultArray['status'],
                'errorDetail' => $resultArray['gatewayResponseCode'] . ' - ' . $resultArray['gatewayResponseMessage'],
                'gatewayResponseCode' => $resultArray['gatewayResponseCode'],
                'gatewayResponseMessage' => $resultArray['gatewayResponseMessage']
            ];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Cancel payment authorized previously
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-create-payment
     * @param string $ipAddress
     * @param string $orderId
     * @return array
     */
    public function cancelPreAuthPayment(string $ipAddress, string $orderId)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'payments/preauths/' . $this->storeUniquify($orderId) . '/cancel');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postFields = [
            'merchantCode' => $this->merchantCode,
            'ip' => $ipAddress,
            'metadata' => $this->getMetadata(),
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);
        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Capture payment authorized previously
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-create-capture-payment
     * @param string $ipAddress
     * @param string $orderId
     * @param int $amount
     * @return array
     */
    public function capturePreAuthPayment(string $ipAddress, string $orderId, int $amount)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'payments/preauths/' . $this->storeUniquify($orderId) . '/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postFields = [
            'merchantCode' => $this->merchantCode,
            'amount' => $amount,
            'ip' => $ipAddress,
            'metadata' => $this->getMetadata(),
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);
        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }
        if (!isset($resultArray['status'])) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => $resultArray
            ];
        }
        if ($resultArray['status'] !== 'paid') {
            return ['error' => true,
                'errorCode' => 'payment_status_' . $resultArray['status'],
                'errorDetail' => $resultArray['gatewayResponseCode'] . ' - ' . $resultArray['gatewayResponseMessage'],
                'gatewayResponseCode' => $resultArray['gatewayResponseCode'],
                'gatewayResponseMessage' => $resultArray['gatewayResponseMessage']
            ];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Refund captured payment
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-refund-payment
     * @param string $ipAddress
     * @param int $amount
     * @param string $orderId
     * @param string|null $idempotencyKey
     * @return array
     */
    public function refundPayment(string $ipAddress, int $amount, string $orderId, string $idempotencyKey = null)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'orders/' . $this->storeUniquify($orderId) . '/refunds');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postFields = [
            'amount' => $amount,
            'merchantCode' => $this->merchantCode,
            'ip' => $ipAddress,
            'metadata' => $this->getMetadata(),
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        if (isset($idempotencyKey)) {
            $headers[] = 'Idempotency-Key: ' . $this->storeUniquify($idempotencyKey);
        }
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);
        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }
        if (!isset($resultArray['status'])) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => $resultArray
            ];
        }
        if ($resultArray['status'] !== 'paid') {
            return ['error' => true,
                'errorCode' => 'payment_status_' . $resultArray['status'],
                'errorDetail' => $resultArray['gatewayResponseCode'] . ' - ' . $resultArray['gatewayResponseMessage'],
                'gatewayResponseCode' => $resultArray['gatewayResponseCode'],
                'gatewayResponseMessage' => $resultArray['gatewayResponseMessage']
            ];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Create payment instrument for specified user account
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-create-payment-instrument
     * @param string $customerCode
     * @param string $paymentToken
     * @param string $ipAddress
     * @return array
     */
    public function createPaymentInstrument(string $customerCode, string $paymentToken, string $ipAddress)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'customers/' . $this->storeUniquify($customerCode) . '/payment-instruments/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        $headers[] = 'token: ' . $paymentToken;
        $headers[] = 'ip: ' . $ipAddress;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);

        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Create payment using customer's saved payment instrument
     * @param string $customerCode
     * @param string $paymentToken
     * @param string $ipAddress
     * @param int $amount
     * @param string|null $orderId
     * @param string|null $idempotencyKey
     * @return array|mixed
     */
    public function createPaymentFromInstrument(string $customerCode, string $paymentToken, string $ipAddress, int $amount, string $orderId = null, string $idempotencyKey = null)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'customers/' . $this->storeUniquify($customerCode) . '/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postFields = [
            'amount' => $amount,
            'merchantCode' => $this->merchantCode,
            'token' => $paymentToken,
            'ip' => $ipAddress,
            'metadata' => $this->getMetadata(),
        ];
        if (isset($orderId)) {
            $postFields['orderId'] = $this->storeUniquify($orderId);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        if (isset($idempotencyKey)) {
            $headers[] = 'Idempotency-Key: ' . $this->storeUniquify($idempotencyKey);
        }
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);
        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }
        if (!isset($resultArray['status'])) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => $resultArray
            ];
        }
        if ($resultArray['status'] !== 'paid') {
            return ['error' => true,
                'errorCode' => 'payment_status_' . $resultArray['status'],
                'errorDetail' => $resultArray['gatewayResponseCode'] . ' - ' . $resultArray['gatewayResponseMessage'],
                'gatewayResponseCode' => $resultArray['gatewayResponseCode'],
                'gatewayResponseMessage' => $resultArray['gatewayResponseMessage']
            ];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Retrieve list of payment instruments of an user account
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-payment-instruments
     * @param string $customerCode
     * @param string $ipAddress
     * @return array
     */
    public function listPaymentInstrument(string $customerCode, string $ipAddress)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'customers/' . $this->storeUniquify($customerCode) . '/payment-instruments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        $headers[] = 'ip: ' . $ipAddress;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);

        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => 'invalid_server_response',
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Delete payment instrument of an user account
     * https://auspost.com.au/payments/docs/securepay/#card-payments-rest-api-delete-payment-instrument
     * @param string $customerCode
     * @param string $paymentToken
     * @param string $ipAddress
     * @return array
     */
    public function deletePaymentInstrument(string $customerCode, string $paymentToken, string $ipAddress)
    {
        if (!isset($this->merchantCode)) {
            return ['error' => true,
                'errorCode' => 'merchant_code_not_set',
                'errorDetail' => 'Merchant code is not set. Have you called init function?'];
        } elseif (!isset($this->bearerToken)) {
            return ['error' => true,
                'errorCode' => 'bearer_token_not_set',
                'errorDetail' => 'Bearer token is not set. Have you called init function?'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . 'customers/' . $this->storeUniquify($customerCode) . '/payment-instruments/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->bearerToken;
        $headers[] = 'token: ' . $paymentToken;
        $headers[] = 'ip: ' . $ipAddress;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => true,
                'errorCode' => 'curl_error',
                'errorDetail' => curl_error($ch)];
        }
        curl_close($ch);
        $resultArray = json_decode($result, true);

        if ($resultArray === null) {
            return ['error' => true,
                'errorCode' => self::ERROR_INVALID_RESPONSE,
                'errorDetail' => 'Invalid response from gateway sever.'];
        }
        if (isset($resultArray['errors'])) {
            return ['error' => true,
                'errorCode' => $resultArray['errors'][0]['code'],
                'errorDetail' => $resultArray['errors'][0]['detail']];
        }

        $resultArray['error'] = false;
        return $resultArray;
    }

    /**
     * Get bearer token
     * @return string
     */
    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    /**
     * Get merchant code
     * @return string
     */
    public function getMerchantCode()
    {
        return $this->merchantCode;
    }
}