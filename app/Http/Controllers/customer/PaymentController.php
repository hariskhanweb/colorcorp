<?php

namespace App\Http\Controllers\customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\SecurePayTrait;
use App\Models\CartItems;
use App\Models\User;
use App\Models\Orders;
use App\Models\InstallationCharges;
use Helper;
use Auth;

class PaymentController extends Controller
{
    //
    use SecurePayTrait;

    public function __construct(){
        $this->client_id = setting('payment-setting.client_id');
        $this->client_secret = setting('payment-setting.client_secret');
        $this->merchantCode = setting('payment-setting.merchant_code');
        $this->isLive = setting('payment-setting.Environment')==1 ? false : true;
        $this->isCaptureImmediately = true;
        $this->general_error_message = config('config.paymentconstant.ERROR_TRANSACTION');
    }

    public function generatePayment(Request $request){
        $cartdata = CartItems::where("user_id", $request->user_id)->with('getCartProduct')->get();
        $subtotal = 0;
        $proAttriPrice = 0;
        $tax = 0;
        $gst = 0;
        $orderTotal = 0; 
        foreach( $cartdata as $item){
            $subtotal = $subtotal + ($item->pro_qty * $item->pro_att_price);
        }
        $gst        = ($subtotal * setting('tax-setting.gst')) / 100;
        $orderTotal = $subtotal + round($gst) + $tax;

        /*$latestRecord = Orders::select('id')->orderBy('id', 'DESC')->first();
        if(!empty($latestRecord)) {
            $latestOrderID = $latestRecord->id;
            $order_number = 1000 + $latestOrderID + 1;
        } else {
            $order_number = 1000 + 1;
        }
        $ornum = $order_number+$request->user_id;*/  
        $ornum = 1000 + time();

        $is_save_credit_card = $request->saveCard;
        $token               = $request->securePayApiToken;
        $orderId             = 'cc-'.$ornum;
        $orderIp             = $request->ip();
        $scope               = array(config('config.paymentconstant.PAYMENT_WRITE'));

        if ($is_save_credit_card) {
            $scope = array_merge($scope, array(config('config.paymentconstant.SANDBOX_PAYMENT_INSTRUMENTS')));
        }

        $init_result = $this->initWithAuthentication($this->client_id, $this->client_secret, $this->merchantCode, $scope, $this->isLive);

        if (isset($init_result['error']) && !$init_result['error']) {
            if ($this->isCaptureImmediately) {
                $create_payment_result = $this->createPayment($token,
                    $orderIp,
                    $orderTotal * 100,
                    $orderId
                );

            } else {
                $create_payment_result = $this->createPreAuthPayment($token,
                    $orderIp,
                    $orderTotal * 100,
                    $orderId
                );
            }
            // print_r($create_payment_result);
            if ($create_payment_result['error']) {


                if(isset($create_payment_result['gatewayResponseMessage'])){
                    $err='Transcation Declined '.$create_payment_result['gatewayResponseMessage'];
                }else{
                    $err='ERROR';
                }

                $logMsg = "Error : ".$err;
                Log::channel('paymentlog')->critical($logMsg);

                echo json_encode(
                    [
                        'success' => false,
                        'message' => $err
                    ]
                );
            } else {
                
                if ($is_save_credit_card) {
                    $this->createPaymentInstrument($order['customer_id'], $token, $order['ip']);
                }

                $logMsg = "Success : Order id ".$create_payment_result['orderId'];
                Log::channel('paymentlog')->critical($logMsg);

                echo json_encode([
                    'success' => true,
                    'orderId' => $create_payment_result['orderId'],
                    'bankTransactionId' => $create_payment_result['bankTransactionId']
                ]);
            }
        } else {

            if(isset($init_result['gatewayResponseMessage'])){
                $err='Transcation Declined '.$init_result['gatewayResponseMessage'];
            }else{
                $err=$this->general_error_message;
            }

            $logMsg = "Error : ".$err;
            Log::channel('paymentlog')->critical($logMsg);

            echo json_encode(
                [
                    'success' => false,
                    'message' => isset($init_result['gatewayResponseMessage']) ? 'Transcation Declined ' . $init_result['gatewayResponseMessage'] : $this->general_error_message
                ]
            );
        }
    }



    public function invoiceCheckoutPayment(Request $request){
        //$orderid = base64_decode(urldecode($id));
        $AdminData = User::select('name','email')->where('role_id',1)->first();
        $UserData = User::select('name','email')->where('id',$request->user_id)->first();
        $ICdata = InstallationCharges::where("id", $request->ICdata_id)->first();
        $orderTotal = number_format($ICdata->total_charges, 2); 
        $orderIp             = $request->ip();
        $scope               = array(config('config.paymentconstant.PAYMENT_WRITE'));
        $is_save_credit_card = $request->saveCard;
        $token               = $request->securePayApiToken;
        $orderId             = 'cc-'.$ICdata->inv_number;

        if ($is_save_credit_card) {
            $scope = array_merge($scope, array(config('config.paymentconstant.SANDBOX_PAYMENT_INSTRUMENTS')));
        }

        $init_result = $this->initWithAuthentication($this->client_id, $this->client_secret, $this->merchantCode, $scope, $this->isLive);
        if (isset($init_result['error']) && !$init_result['error']) {
            if ($this->isCaptureImmediately) {
                $create_payment_result = $this->createPayment($token, $orderIp,$orderTotal * 100,$orderId);
            } else {
                $create_payment_result = $this->createPreAuthPayment($token,$orderIp,$orderTotal * 100,$orderId);
            }

            if ($create_payment_result['error']) {
                  if(isset($create_payment_result['gatewayResponseMessage'])){
                    $err='Transcation Declined '.$create_payment_result['gatewayResponseMessage'];
                }else{
                    $err='ERROR';
                }

                $logMsg = "Error : ".$err;
                Log::channel('paymentlog')->critical($logMsg);
                echo json_encode( ['success' => false,'message' => $err]);

            }  else {
                
                if ($is_save_credit_card) {
                    $this->createPaymentInstrument($order['customer_id'], $token, $order['ip']);
                }
                $logMsg = "Success : Order id ".$create_payment_result['orderId'];
                Log::channel('paymentlog')->critical($logMsg);

                $Tdata = InstallationCharges::select('transaction_id','total_charges','inv_number')->where("id", $request->ICdata_id)->first();
                $OrderID = Orders::select('id','order_number')->where('id',$ICdata->order_id)->first();

                $details = [
                    'name'          => $AdminData->name??'NA',
                    'username'      => $UserData->name??'NA',
                    'order_number'  => $OrderID->order_number??'NA',
                    'inv_number'    => $Tdata->inv_number,
                    'transaction_id'=> $Tdata->transaction_id,
                    'status'        => 'Completed',
                    'amount'        => setting('payment-setting.currency').$Tdata->total_charges,
                ];

                $subject    = 'Installation Invoice Payment Success';
                $to         = $UserData->email;
                $toname     = $UserData->name??'NA';
                $mailtype   = 'installation-invoice-pay-success';
                
                Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);

                $subject    = 'Installation Invoice Payment Success';
                $to         = $AdminData->email;
                $toname     = $AdminData->name??'NA';
                $mailtype   = 'admin-notify-invoice-pay-success';
                
                Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);

                echo json_encode([
                    'success' => true, 'orderId' => $create_payment_result['orderId'], 'bankTransactionId' => $create_payment_result['bankTransactionId']
                ]);
            }

        } else {
            if(isset($init_result['gatewayResponseMessage'])){
                $err='Transcation Declined '.$init_result['gatewayResponseMessage'];
            }else{
                $err=$this->general_error_message;
            }

            $logMsg = "Error : ".$err;
            Log::channel('paymentlog')->critical($logMsg);

            echo json_encode(
                [
                    'success' => false,
                    'message' => isset($init_result['gatewayResponseMessage']) ? 'Transcation Declined ' . $init_result['gatewayResponseMessage'] : $this->general_error_message
                ]
            );
        }


    }
}
