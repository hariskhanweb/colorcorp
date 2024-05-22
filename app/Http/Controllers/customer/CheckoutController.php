<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use App\Models\State;
use App\Models\CartItems;
use App\Models\Product;
use App\Models\CustomerAddresses;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAddresses;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\ProductAttributes;
use App\Models\OrderItemAttributes;
use App\Models\InstallationCharges;
use App\Models\InstallationChargeItems;
use Redirect;
use Mail;
use Helper;
use File;
use DB;
use PDF;

class CheckoutController extends Controller
{
    public function cart($vendor_name) {
        $user = Auth::user();
        $cartdata=CartItems::where("user_id", $user->id)->with('getCartProduct')->get();
        $page = Helper::getPageData($user->vendor_id);
        return view('customer.checkout.cart',compact('cartdata', 'page'));
    }

    public function cartCheckout(Request $request) {
        $user = Auth::user();       

        $data=User::where("id", $user->id)->first();
        $cartdata=CartItems::where("user_id", $user->id)->with('getCartProduct')->get();
        
        $cusbilldata = CustomerAddresses::where("user_id", $user->id)
        ->where("address_type", "1")->orderBy('default_address','DESC')->get();
        $defBillingAddId = 0;
        foreach( $cusbilldata as $bill) {
            if($bill->default_address == 1) {
                $defBillingAddId = $bill->id;
                break;
            }
        }

        $cusshipdata=CustomerAddresses::where("user_id", $user->id)
        ->where("address_type", "0")->orderBy('default_address','DESC')->get();
        $defShippingAddId = 0;
        foreach( $cusshipdata as $ship) {
            if($ship->default_address == 1) {
                $defShippingAddId = $ship->id;
                break;
            }
        }

        $countries = Country::get(["name", "id"]);
        $states = State::where("country_id", 1)->get();

        $firstOrder = Orders::orderBy('id', 'desc')->first();
        $nextAutoIncrementId = $firstOrder->id+1;
        
        $page = Helper::getPageData($user->vendor_id);
        if(count($cartdata)>0){
          return view('customer.checkout.checkout',compact('cartdata','cusbilldata','defBillingAddId','cusshipdata','defShippingAddId','countries','states','nextAutoIncrementId','page'));
        }else{
          $shop_url_slug = Helper::getShopslug($user->vendor_id);
          return redirect()->route('cart',['vendor_name' => $shop_url_slug ]);
        }

    }

    public function thankYou($vendor_name, $id) {
        $user = Auth::user();
        $orderid = base64_decode(urldecode($id));
        $orderrecord = Orders::select('*')->where('id',$orderid)->where('user_id',$user->id)->first();
        $page = Helper::getPageData($user->vendor_id);
        if($orderrecord){
          $order_number = $orderrecord->order_number;
          return view('customer.checkout.thankyou',compact('order_number', 'orderrecord','page'));
        }else{
          abort(404);
        }
    }

    public function addReorder(Request $request) {
        $user = Auth::user();
        $parent_order_id = $request->parent_order_id;
        $data = Helper::getDataForReorder($parent_order_id);
        $getData = $data['data'];

        if(isset($getData)) {
          foreach($getData['orderItems'] as $key => $value) {
            $product_id = $value->product_id;
            $pro_price = $value->pro_price;

            $productdata = Product::where("id", $product_id)->first();
            if($productdata && !empty($productdata)) {
                $proatt = (isset($value->attribute)) ? unserialize($value->attribute):'';

                if(isset($proatt) && !empty($proatt)) {
                    $prodata  = array_filter($proatt);
                    $total=0;
                    foreach ($prodata as $key => $prodata_value) {
                        $attr_data = Helper::getAttributeById($key);
                        if(isset($attr_data->is_price) && $attr_data->is_price != 0) {
                            $proat=explode('-', $prodata_value);
                            if($proat[1]!='Yes' && $proat[1]!='No'){
                                $total=$total+$proat[1];
                            }
                        }    
                    }
                    $product_total=$total+$pro_price;
                    $proatt=serialize($prodata);
                } else {
                    $product_total=$pro_price;
                    $proatt=null;
                }

                $cart_info=CartItems::where("user_id", $user->id)
                            ->where("product_id", $product_id)
                            ->orderBy('id','DESC')
                            ->first();

                if(!empty($cart_info)){
                    $cardadd =CartItems::find($cart_info->id);
                }else{
                    $cardadd = new CartItems;  
                }  

                $cardadd->user_id           = $user->id;  
                $cardadd->vendor_id         = $productdata->vendor_id;
                $cardadd->product_id        = $productdata->id;
                $cardadd->vehicle_make      = $value->vehicle_make;
                $cardadd->vehicle_model     = $value->vehicle_model;       
                $cardadd->vehicle_colour    = $value->vehicle_colour;
                $cardadd->vehicle_year      = $value->vehicle_year;
                $cardadd->vehicle_rego      = $value->vehicle_rego;
                $cardadd->franchise_territory = $value->franchise_territory;
                $cardadd->franchise_name      = $value->franchise_name;
                $cardadd->installation      = $value->installation_address;   
                $cardadd->attribute         = $proatt;
                $cardadd->product_text_attr = $value->product_text_attr;
                $cardadd->decal_removel     = $value->decal_removel;
                $cardadd->pro_info          = $value->pro_info;
                $cardadd->re_scheduling_fee = $value->re_scheduling_fee;
                $cardadd->preparation_fee   = $value->preparation_fee;
                $cardadd->pro_price         = $value->pro_price;
                $cardadd->pro_att_price     = $product_total;
                $cardadd->additional_comment = $value->comment;
                $cardadd->cat_id            = $value->cat_id;
                $cardadd->parent_cat_id     = $value->parent_cat_id;
                $cardadd->pro_qty           = $value->quantity;
                $cardadd->save();
            } else {
                $shop_url_slug = Helper::getShopslug($user->vendor_id);
                return redirect('/account/my-order');
            }

          }
        } 
        Session::put('parent_order_id', $parent_order_id);
        $shop_url_slug = Helper::getShopslug($user->vendor_id);
        return redirect('/'.$shop_url_slug.'/checkout');
    }

    public function cartAdd(Request $request) {

        if($request->installation_address==1){
            $this->saveAddresses($request->all());
        }
        $user        = Auth::user();
        $data        = User::where("id", $user->id)->first();
        $productdata = Product::where("id", $request->sproductid)->first();

        if(isset($request->proatt) && $request->proatt != ""){
            $prodata  = array_filter($request->proatt);

            $total=0;
            foreach ($prodata as $key => $value) {
                $attr_data = Helper::getAttributeById($key);
                if(isset($attr_data->is_price) && $attr_data->is_price != 0) {
                    $proat=explode('-', $value);
                    if($proat[1]!='Yes' && $proat[1]!='No'){
                        $total=$total+$proat[1];
                    }
                }
            }

            $product_total=$total+$request->pro_price;
            $proatt=serialize($prodata);
        }else{
            $product_total=$request->pro_price;
            $proatt=null;
        }

        //dd($product_total);

        if($request->re_scheduling_fee){
            $re_scheduling_fee='yes';
        }else{
            $re_scheduling_fee='no';
        }  

        if($request->preparation_fee){
            $preparation_fee='yes';
        }else{
            $preparation_fee='no';
        }  

        $product_text_attr = null;
        if(isset($request->product_text_attr) && $request->product_text_attr != ""){
            $product_text_attr = serialize($request->product_text_attr);
        }

        $cart_info=CartItems::where("user_id", $user->id)
                    ->where("product_id", $productdata->id)
                    ->orderBy('id','DESC')
                    ->first();

        if(!empty($cart_info)){
            $cardadd =CartItems::find($cart_info->id);
        }else{
            $cardadd = new CartItems;  
        }  
            $cardadd->user_id           = $user->id;  
            $cardadd->vendor_id         = $productdata->vendor_id;
            $cardadd->product_id        = $productdata->id;
            $cardadd->vehicle_make      = $request->vehicle_make;
            $cardadd->vehicle_model     = $request->vehicle_model;       
            $cardadd->vehicle_colour    = $request->vehicle_colour;
            $cardadd->vehicle_year      = $request->vehicle_year;
            $cardadd->vehicle_rego      = $request->vehicle_rego;
            $cardadd->franchise_territory = $request->franchise_territory;
            $cardadd->franchise_name      = $request->franchise_name;
            $cardadd->installation      = $request->installation_address;   
            $cardadd->attribute         = $proatt;
            $cardadd->product_text_attr = $product_text_attr;
            $cardadd->decal_removel     = $request->decal_removel;
            $cardadd->pro_info          = $request->pro_info;
            $cardadd->re_scheduling_fee = $re_scheduling_fee;
            $cardadd->preparation_fee   = $preparation_fee;
            $cardadd->pro_price         = $request->pro_price;
            $cardadd->pro_att_price     = $product_total;
            $cardadd->additional_comment = $request->pro_info;
            $cardadd->cat_id            = $request->sproductcategry;
            $cardadd->parent_cat_id     = $request->division;
            $cardadd->pro_qty           = 1;
            $cardadd->save();    
            // dd($cardadd);
        Session::put('parent_order_id', '');
        $shop_url_slug = Helper::getShopslug($user->vendor_id);
        return redirect('/'.$shop_url_slug.'/cart');
    }

    public function cartDelete(Request $request) {
        $user_data=CartItems::where('id', $request->cartid)->delete();
         return \Response::json([
          'status' => true,
          'data' => "succeeded"
        ]);
    }

    public function saveAddresses($requestData){ 
        $user = Auth::user();

        CustomerAddresses::where('user_id', '=', $user->id)
            ->where('address_type', '=', 0)
            ->update([
                'default_address' => 0
            ]);

        $useradd = new CustomerAddresses;       
        $useradd->address = $requestData['address'];
        $useradd->city = $requestData['city'];
        $useradd->state_id = $requestData['state'];
        $useradd->country_id = $requestData['country'];
        $useradd->postcode = $requestData['postcode'];
        $useradd->default_address = 1;
        $useradd->mobile_number = $requestData['mobile_number'];
        $useradd->address_type = 0;
        $useradd->user_id = $user->id;
        $useradd->save();
        return $useradd;
    }

    public function placeOrder(Request $request) {
        $user     = Auth::user();
        $vendor_id = Auth::user()->vendor_id;
        $cartdata = CartItems::where("user_id", $user->id)->with('getCartProduct')->get();
        $subtotal = 0;
        $proAttriPrice = 0;
        $tax = 0;
        $gst = 0;
        $payment = 0; 
        foreach( $cartdata as $item){
            $subtotal = $subtotal + ($item->pro_qty * $item->pro_att_price);
        }
        $gst = ($subtotal * setting('tax-setting.gst')) / 100;
        $payment = $subtotal + round($gst);

        /*$latestRecord = Orders::select('id')->orderBy('id', 'DESC')->first();
        if(!empty($latestRecord)) {
            $latestOrderID = $latestRecord->id;
            $order_number = 'CC'.(1000 + $latestOrderID + 1);
        } else {
            $order_number = 'CC'.(1000 + 1);
        }*/
        $order_number = $request->order_number;

        $parent_order_id = Session::get('parent_order_id');
        //dd($parent_order_id);

        $order = new Orders;
        $order->vendor_id = $vendor_id;
        $order->user_id = $user->id;
        $order->order_number = $order_number;
        $order->subtotal = $subtotal;
        $order->tax = setting('tax-setting.gst');
        $order->total_amount = $payment;
        $order->transaction_id =  $request->transaction_id;
        $order->comment = $request->addition_infomation;
        $order->status = 2;
        $order->gst = round($gst);
        $order->parent_order_id = $parent_order_id;
        $order->save();

        Session::put('parent_order_id', '');

        $lastInsertedId = $order->id;
        
       
        foreach($cartdata as $item) {

            $productdata = Product::select('name')->where("id", $item->product_id)->first();
            $orderitems = new OrderItems;
            $orderitems->order_id     = $lastInsertedId;
            $orderitems->product_id   = $item->product_id;
            $orderitems->name         = $productdata->name;
            $orderitems->vehicle_make = $item->vehicle_make;
            $orderitems->vehicle_model= $item->vehicle_model;
            $orderitems->vehicle_colour= $item->vehicle_colour;
            $orderitems->vehicle_year  = $item->vehicle_year;
            $orderitems->vehicle_rego  = $item->vehicle_rego;
            $orderitems->decal_removel = $item->decal_removel;
            $orderitems->comment       = $item->pro_info;
            $orderitems->re_scheduling_fee = $item->re_scheduling_fee;
            $orderitems->preparation_fee   = $item->preparation_fee;
            $orderitems->installation   = $item->installation==1?'yes':'no';
            $orderitems->franchise_territory   = $item->franchise_territory;
            $orderitems->franchise_name   = $item->franchise_name;
            $orderitems->quantity          = $item->pro_qty;
            $orderitems->pro_price             = $item->pro_price;
            $orderitems->pro_att_price       = $item->pro_att_price;
            $orderitems->cat_id       = $item->cat_id;
            $orderitems->parent_cat_id       = $item->parent_cat_id;
            $orderitems->product_text_attr       = $item->product_text_attr;
            $orderitems->attribute       = $item->attribute;
            $orderitems->save();

            // Order Attributes

            
            if(isset($item->product_text_attr) && $item->product_text_attr != '') {
                $product_text_attr=@unserialize($item->product_text_attr);
                if(!empty($product_text_attr)) {
                    foreach($product_text_attr as $key => $attval){

                        $attr_option_name = Helper::getTextAttributeById($key);
                            
                        $orderitem_attri = new OrderItemAttributes;
                        $orderitem_attri->order_item_id = $orderitems->id;
                        $orderitem_attri->name = $attr_option_name;
                        $orderitem_attri->type = '';
                        $orderitem_attri->type_value = $attval;                
                        $orderitem_attri->save();                    
                    }  
                }    
            }

            if(isset($item->attribute)) {
                $pro_attribute=unserialize($item->attribute);
                foreach($pro_attribute as $attval){
                    $att_val=explode('-',$attval);
                    $pro_att_data=ProductAttributes::where('id', $att_val[0])->first();

                    $att_data = Attribute::where('id', $pro_att_data->attribute_id)->first();

                    $option_data = AttributeOption::where('id', $pro_att_data->option_id)->first();

                    $orderitem_attri = new OrderItemAttributes;
                    $orderitem_attri->order_item_id = $orderitems->id;
                    $orderitem_attri->name = $att_data->name;
                    $orderitem_attri->type = $option_data->options;
                    $orderitem_attri->type_value = $att_val[1];                
                    $orderitem_attri->save();                    
                }  
            }
        }
        
        // Order Addresses
        

        if($request->billing_adddress_id != 0){
           $billingAddress = CustomerAddresses::where('id', $request->billing_adddress_id)->first(); 
               $address = new OrderAddresses; 
               $address->order_id = $lastInsertedId; 
               $address->type = 1 ; 
               $address->name = $user->name;
               $address->address = $billingAddress->address; 
               $address->city = $billingAddress->city; 
               $address->state_id = $billingAddress->state_id; 
               $address->country_id = $billingAddress->country_id; 
               $address->postcode = $billingAddress->postcode; 
               $address->mobile_number = $billingAddress->mobile_number; 
               $address->save();
        }else{
               $address = new OrderAddresses; 
               $address->order_id = $lastInsertedId;
               $address->type = 1 ; 
               $address->name =  $request->bfirstname;
               $address->address = $request->baddress;
               $address->city = $request->bcity;
               $address->state_id = $request->bstate;
               $address->country_id = $request->bcountry;
               $address->postcode = $request->bpostcode;
               $address->mobile_number = $request->bmobile;
               $address->save();

               $this->saveOrderAddresses($request->all(),1);

        }
        if($request->shipping_adddress_id != 0){
           $shippingAddress = CustomerAddresses::where('id', $request->shipping_adddress_id)->first();
                $address = new OrderAddresses;
               $address->order_id = $lastInsertedId; 
               $address->type = 0; 
               $address->name = $user->name;
               $address->address = $shippingAddress->address; 
               $address->city = $shippingAddress->city; 
               $address->state_id = $shippingAddress->state_id; 
               $address->country_id = $shippingAddress->country_id; 
               $address->postcode = $shippingAddress->postcode;
               $address->mobile_number = $shippingAddress->mobile_number;
               $address->save();
        }else{
               $address = new OrderAddresses; 
               $address->order_id = $lastInsertedId; 
               $address->type = 0; 
               $address->name =  $request->sfirstname;
               $address->address = $request->saddress;
               $address->city = $request->scity;
               $address->state_id = $request->sstate;
               $address->country_id = $request->scountry;
               $address->postcode = $request->spostcode;
               $address->mobile_number = $request->smobile;
               $address->save();

               $this->saveOrderAddresses($request->all(),0);
        }

        $procount=count($cartdata);

        /*$billingadd = OrderAddresses::where("order_id", $lastInsertedId)->where("type", 1)->first();
        $shipadd = OrderAddresses::where("order_id", $lastInsertedId)->where("type", 0)->first();
        $orderitem = OrderItems::where("order_id", $lastInsertedId)->with('productImage')->get();;

        $details = [  
            'name'    => $request->name,              
            'email'    => $request->email,
            'orderdata'    => $orderdata,
            'orderitem'    => $orderitem,
            'billingadd'    => $billingadd,
            'shipadd'    => $shipadd,
             'url'    => 'abc.com',          
        ];
        //Mail::to($request->email)->send(new userVerifyMail($details));
        $subject='Your Colorcorp order #'.$order_number.' of '.$procount.' item';
        $to=$user->email;
        $toname=$user->name;
        $mailtype='orderemail';
        
        Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);*/

        // Generate PDF
        $data = Orders::where("id", $lastInsertedId)->orderBy('id', 'DESC')->with('orderItems')->with('shippingAddress')->with('billingAddress')->first()->toArray();

        $datashipstate = State::select('name')->where('id','=', $data['shipping_address']['state_id'])->first();
        $datashipcontry = Country::select('name')->where('id','=', $data['shipping_address']['country_id'])->first();
        $databillstate = State::select('name')->where('id','=', $data['billing_address']['state_id'])->first();
        $databillcontry = Country::select('name')->where('id','=', $data['billing_address']['country_id'])->first();
        $imgpath = asset('/storage/');

        //var_dump($data); die();

        if(empty($data)){
            return redirect()->route('my.order');        
        }

        $page = Helper::getPageData($user->vendor_id);
        $filename = "Invoice".trim($data['order_number']).".pdf";
        $pdf = PDF::loadView('customer.order.order-invoice-pdf', compact('data','datashipstate','datashipcontry','databillstate','databillcontry','imgpath','page'))->setOptions(['isRemoteEnabled' => true]);
        
        $pdf->save(public_path('invoices/'.$filename)); 

        $orderdata  = Orders::where("id", $lastInsertedId)->first();
        $admin_user = User::where("role_id", 1)->first();
        $subject='Your Colorcorp order #'.$order_number.' of '.$procount.' item';
        $to=$user->email;
        $toname=$user->name;
        $mailtype='orderemail';


        $vendor_name= $orderdata->vendorName->name;
        $vendor_email= $orderdata->vendorName->email ;

        $admin_name= 'Colorcorp';
        $admin_email= setting('admin.sales_mail');

        $details = [  
          'name'    => $user->name,              
          'email'    => $user->email,
          'orderdata'    => $orderdata,
          'url'    => 'abc.com'     
        ];

        $vendor_details = [  
          'name'    => $vendor_name,              
          'email'    => $vendor_email,
          'orderdata'    => $orderdata,
           'url'    => 'abc.com',          
        ];

        $admin_details = [  
          'name'    => 'Colorcorp',              
          'email'    => setting('admin.sales_mail'),
          'orderdata'    => $orderdata,
           'url'    => 'abc.com',          
        ];

        
        Helper::setMailWeb($subject,$to,$toname,$details,'orderemail');
        Helper::setMailWeb($subject,$vendor_email,$vendor_name,$vendor_details,'vendor-order-email');
        Helper::setMailWeb($subject,$admin_email,$admin_name,$admin_details,'admin-order-email');

        $carttrash = CartItems::where("user_id", $user->id)->delete();

        $shop_url_slug = Helper::getShopslug($user->vendor_id);
        $orid=urlencode(base64_encode($lastInsertedId));
        return redirect()->route('thankYou',['vendor_name' => $shop_url_slug, 'id' => $orid ]);
    } 

    public function saveOrderAddresses($requestData,$type){ 

        $user = Auth::user();

        if($type==1){
            $prefix='b';
        }else{
            $prefix='s';
        }

        CustomerAddresses::where('user_id', '=', $user->id)
            ->where('address_type', '=', $type)
            ->update([
                'default_address' => 0
            ]);

        
        $useradd = new CustomerAddresses;       
        $useradd->address = $requestData[$prefix.'address'];
        $useradd->city = $requestData[$prefix.'city'];
        $useradd->state_id = $requestData[$prefix.'state'];
        $useradd->country_id = $requestData[$prefix.'country'];
        $useradd->postcode = $requestData[$prefix.'postcode'];
        $useradd->default_address = 1;
        $useradd->mobile_number = $requestData[$prefix.'mobile'];
        $useradd->address_type = $type;
        $useradd->user_id = $user->id;
        $useradd->save();
        
    } 


    public function cartUpdate(Request $request) {
        $user = Auth::user();
        $cardadd =CartItems::find($request->cartid);
        $cardadd->pro_qty= $request->qty;
        $cardadd->save(); 
        $total_gst=0;
        $total_gst=round(((float)$request->price*(float)$request->qty)*(float)setting('tax-setting.gst')/100);
        $item_total=((float)$request->price*(float)$request->qty)+(float)$total_gst; 
        $cartdata=CartItems::where("user_id", $user->id)->get();
        
        $cart_total=$gst=$total=0;
        if(!empty($cartdata)){
            foreach ($cartdata as $key => $value) {
                $cart_total +=((float)$value['pro_qty']*(float)$value['pro_att_price']);
            }
        }
        $gst=round(((float)$cart_total*(float) setting('tax-setting.gst'))/100);
        $total=(float)$gst+ (float)$cart_total;


        return response()->json([
            'success' => 1,
            'item_total' => setting('payment-setting.currency')." ".number_format($item_total,2),  
            'cart_total' => setting('payment-setting.currency')." ".number_format($cart_total,2),
            'gst' => setting('payment-setting.currency')." ".number_format($gst,2),
            'total' => setting('payment-setting.currency')." ".number_format($total,2),
        ]);
    }

    public function getCartProduct(Request $request) {
        $html   = "";
        $cardata =CartItems::where("id", $request->cartid)->first();

        if($cardata['vehicle_make'] || $cardata['vehicle_model'] || $cardata['vehicle_colour'] || $cardata['vehicle_year'] || $cardata['vehicle_rego'] || $cardata['franchise_name']) {
            $html .= '<div><p class="w-full"><span class="data-label"><b>Vehicle Make:</b></span><span>'.$cardata['vehicle_make'].'</span></p>
                <p class="w-full"><span class="data-label"><b>Vehicle Model:</b></span><span>'.$cardata['vehicle_model'].'</span></p><p class="w-full"><span class="data-label"><b>Vehicle Colour:</b></span><span>'.$cardata['vehicle_colour'].'</span></p> <p class="w-full"><span class="data-label"><b>Vehicle Year:</b></span><span>'.$cardata['vehicle_year'].'</span></p><p class="w-full"><span class="data-label"><b>Vehicle Rego:</span></b><span>'.$cardata['vehicle_rego'].'</span></p><p class="w-full"><span class="data-label"><b>Franchise Territory:</span></b><span>'.$cardata['franchise_name'].'</span></p>
              <p class="w-full"><span class="data-label"><b>Franchise Name:</b></span><span>'.$cardata['franchise_name'].'</span></p></div>';
        }

            $html .= '<div><p class="w-full"><span class="data-label"><b>Division:</b></span><span>'.Helper::getCategoryName($cardata['parent_cat_id']).'</span></p></div>';

            $attribute=unserialize($cardata['attribute']);
            $product_text_attr=@unserialize($cardata['product_text_attr']);
               
            if(!empty($attribute) || !empty($product_text_attr) ){
               $html .= '<p class="product-title lg:text-base text-gray-900">
                <b>Product Attribute</b>
              </p>';

              if($product_text_attr) {
                foreach($product_text_attr as $id => $attr_val) {
                    $attr_option_name = Helper::getTextAttributeById($id);
                    if($attr_option_name){
                        $html .= '<p class="w-full text-gray-500 text-sm"><b>
                          '.$attr_option_name.'-</b>'.$attr_val.'
                        </p>';
                    }
                }
              }
                
            if(!empty($attribute)) {  
                foreach($attribute as $value){
                  if(!empty($value)){
                    $value1 = explode('-', $value);
                    $arrtibute_option=Helper::getProAttributeById($value1[0]);
                    if($arrtibute_option['option']->options=="Yes/No"){
                    $option_name=$value1[1];
                    }else{
                    $option_name=$arrtibute_option['option']->options;
                    }
                   
                    if(!empty($value1)){
                     $html .= '<p class="w-full text-gray-500 text-sm"><b>
                      '.$arrtibute_option['attribute']->name.'-</b>'.$option_name.'
                    </p>';
                    }
                  }
                }
            }    
        }

        return response()->json([
            'success' => 1,
            'result' => $html,  
        ]);
    }

    public function invoiceCheckout($vendor_id, $id) {

        $user   = Auth::user();     
        $id     = base64_decode($id);
        $ICdata = InstallationCharges::where("id", $id)->where('status',1)->first();
        $shop_url_slug = Helper::getShopslug($user->vendor_id);

        if(empty($ICdata)){
            return redirect()->route('shop',['vendor_name' => $shop_url_slug]);
        }else{            
            $ICIdata= InstallationChargeItems::where("installation_charge_id", $id)->orderBy('id', 'DESC')->get();
            $Orders = Orders::find($ICdata->order_id);
            $data   = User::where("id", $user->id)->first();

            foreach ($ICIdata as $key => $value) {
                $getProductID = OrderItems::select('product_id')->where('order_id',$ICdata->order_id)->where('name',$value->order_item)->first();
                if(!empty($getProductID)){
                    $ICIdata[$key]['product_id'] = $getProductID->product_id;
                }else{
                    $ICIdata[$key]['product_id'] = 0;
                }
            }
            $page = Helper::getPageData($user->vendor_id);
            if(!empty($ICIdata)){
                return view('customer.checkout.invoice-checkout',compact('ICIdata','ICdata','Orders','page'));
            }else{
                return redirect()->route('shop',['vendor_name' => $shop_url_slug]);
            }
        }

    }

    public function getInstallationProduct(Request $request) {
        $html   = "";
        $data = OrderItems::where("order_id", $request->order_id)->where('name',$request->itemname)->first();

        if($data['vehicle_make'] || $data['vehicle_model'] || $data['vehicle_colour'] || $data['vehicle_year'] || $data['vehicle_rego'] || $data['franchise_name']) {

            $html .= '<div><p class="w-full"><span class="data-label"><b>Vehicle Make:</b></span><span>'.$data['vehicle_make'].'</span></p>
                <p class="w-full"><span class="data-label"><b>Vehicle Model:</b></span><span>'.$data['vehicle_model'].'</span></p><p class="w-full"><span class="data-label"><b>Vehicle Colour:</b></span><span>'.$data['vehicle_colour'].'</span></p> <p class="w-full"><span class="data-label"><b>Vehicle Year:</b></span><span>'.$data['vehicle_year'].'</span></p><p class="w-full"><span class="data-label"><b>Vehicle Rego:</span></b><span>'.$data['vehicle_rego'].'</span></p><p class="w-full"><span class="data-label"><b>Franchise Territory:</span></b><span>'.$data['franchise_name'].'</span></p>
              <p class="w-full"><span class="data-label"><b>Franchise Name:</b></span><span>'.$data['franchise_name'].'</span></p></div>';
        }

            $html .= '<div><p class="w-full"><span class="data-label"><b>Division:</b></span><span>'.Helper::getCategoryName($data['parent_cat_id']).'</span></p></div>';


               $attribute=unserialize($data['attribute']);
               $product_text_attr=@unserialize($data['product_text_attr']);
               
            if(!empty($attribute) || !empty($product_text_attr) ){
               $html .= '<p class="product-title lg:text-base text-gray-900">
                <b>Product Attribute</b>
              </p>';

              if($product_text_attr) {
                foreach($product_text_attr as $id => $attr_val) {
                    $attr_option_name = Helper::getTextAttributeById($id);
                    if($attr_option_name){
                        $html .= '<p class="w-full text-gray-500 text-sm"><b>
                          '.$attr_option_name.'-</b>'.$attr_val.'
                        </p>';
                    }
                }
              }
                
            if(!empty($attribute)) {  
            foreach($attribute as $value){
              if(!empty($value)){
                $value1 = explode('-', $value);
                $arrtibute_option=Helper::getProAttributeById($value1[0]);
                if($arrtibute_option['option']->options=="Yes/No"){
                $option_name=$value1[1];
                }else{
                $option_name=$arrtibute_option['option']->options;
                }
               
                if(!empty($value1)){
                 $html .= '<p class="w-full text-gray-500 text-sm"><b>
                  '.$arrtibute_option['attribute']->name.'-</b>'.$option_name.'
                </p>';
                }
              }
            }
        }
        }

        return response()->json([
            'success' => 1,
            'result' => $html,  
        ]);
    }

    public function placeInvoiceOrder(Request $request){
        $user     = Auth::user();
        $vendor_id = Auth::user()->vendor_id;

        $orid=$request->order_id;
        $installation_charge_id=$request->ICdata_id;
        $installation = InstallationCharges::find($installation_charge_id);
        $installation->status = 2;
        $installation->transaction_id = $request->transaction_id;
        $installation->save();

        $shop_url_slug = Helper::getShopslug($user->vendor_id);

        $orid=urlencode(base64_encode($installation_charge_id));

        return redirect()->route('invoicethankYou',['vendor_name' => $shop_url_slug, 'id' => $orid ]);
    }
     public function invoicethankYou($vendor_name, $id) {
        $user = Auth::user();
        $ICdata_id = base64_decode(urldecode($id));
        $ICdata = InstallationCharges::select('*')->where('id',$ICdata_id)->first();
        $orderrecord = Orders::select('*')->where('id',$ICdata->order_id)->first();
        $page = Helper::getPageData($user->vendor_id);
        if($orderrecord){
          $order_number = $orderrecord->order_number;
          return view('customer.checkout.invoice-thank-you',compact('order_number', 'orderrecord','ICdata','page'));
        }else{
          abort(404);
        }
    }
}
