<?php
namespace App\Helpers;

use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\ProductCategory;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductMedias;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\VendorShopSettings;
use App\Models\CartItems;
use App\Models\CustomerAddresses;
use App\Models\Orders;
use App\Models\OrderAddresses;
use App\Models\OrderComments;
use App\Models\OrderItems;
use App\Models\OrderItemAttributes;
use App\Models\Pages;
use App\Models\InstallationCharges;
use App\Models\InstallationChargeItems;
use App\Mail\userVerifyMail;
use App\Mail\UserOrderStatusMail;
use App\Mail\ForgetPasswordMail;
use App\Mail\userResetPassMail;
use App\Mail\userOrderMail;
use App\Mail\vendorOrderMail;
use App\Mail\adminOrderMail;
use App\Mail\userInvoiceMail;
use App\Mail\userInvoicePaymentSuccessMail;
use App\Mail\AdminNotifyInvoicePaySuccMail;
use Mail;

class Helpers {
    // Welcome
    public static function getWelcomeMesg(){ 
        $str = "Hello World"; return $str; 
    }

    public static function getCategoriesForProduct($vendorId){
        $categories = ProductCategory::where('vendor_id', $vendorId)->where('status', 1)->get();
        return $categories;
    }
    
    public static function getCategories(){
        $record = ProductCategory::select('*')->where('status', '=', 1)->where('has_parent', '=', 0)->get();
        return $record;
    }

    public static function getSubCategories($parent_cat){
        $records = ProductCategory::whereHas('child_categories', function ($query) use($parent_cat) {
                        return $query->where('cat_id', '=', $parent_cat);
                   })->select('*')->where('status', '=', 1)->where('has_parent', '=', 1)->get();
        return $records;
    }

    public static function getCategorySlug($category){
        $catRecord = ProductCategory::select('slug')->where('id', $category)->first();
        return $catRecord->slug;
    }

    public static function getAccessoriesCatId(){
        $catRecord = ProductCategory::select('id')->where('slug', 'accessories')->where('has_parent', '=', 0)->first();
        if(!empty($catRecord)) {
            return $catRecord->id;   
        } else {
            return 0;
        } 
    }

    public static function checkCategorySelected($prodId, $parent_id, $cat_id = null){
        $record = DB::table('product_categories')->where('product_id', '=', $prodId)->where('parent_category_id','=', $parent_id)->where('category_id','=', $cat_id)->first();
        if(!empty($record)){
            return 1;
        } else {
            return 0;
        }
    }

    public static function getParentCategoriesByProductId($product_id){
        $categories = DB::table('product_categories')->join('categories', 'categories.id', '=', 'product_categories.parent_category_id')->where('product_id', '=', $product_id)->get();
        return $categories;
    }

    public static function getCategoryName($category){
        $catRecord = ProductCategory::select('name')->where('id', $category)->first();
        return $catRecord->name;
    }

    public static function getCategoryImage($category_slug){
        $catRecord = ProductCategory::select('image')->where('slug', $category_slug)->first();
        return $catRecord->image;
    }

    public static function getAttributes(){
        $record = Attribute::select('*')->get();
        return $record;
    }

    public static function getAttributeOptions($attrid){
        $record = AttributeOption::select('*')->where('attribute_id','=',$attrid)->get();
        return $record;
    }

    public static function getProdCatName($catid){
        $record = ProductCategory::select('name')->where('id','=',$catid)->first();
        return $record->name;
    }

    public static function getFeaturedImage($prodid){
        $imgurl = "";
        $record = ProductMedias::select('url')->where('product_id','=',$prodid)->where('is_featured','=','1')->take(1)->get();
        foreach($record as $reclist){ $imgurl = $reclist->url; }
        return $imgurl;
    }

    public static function getDateFormatted($datestr){
        $formatdate = date('d-M-Y',strtotime($datestr));
        return $formatdate;
    }

    public static function getPriceFormatted($price,$symbol){
        if($symbol!=''){ $sym = $symbol; } else { $sym = "$"; }
        $formatprice = $sym." ".number_format($price,2);
        return $formatprice;
    }

    public static function getShopslug($user_id){
        $vendorInfo=VendorShopSettings::select('shop_url_slug')->where("vendor_id", $user_id)->first();
        if(isset($vendorInfo->shop_url_slug)) {
            return $vendorInfo->shop_url_slug;
        }
        return false;
    }

    public static function getShopData($user_id){
        $vendorInfo=VendorShopSettings::where("vendor_id", $user_id)->first();
        return $vendorInfo;
    }

    public static function getCartData($user_id){
        $cart_count=CartItems::where("user_id", $user_id)->count();
        return $cart_count;
    }

    public static function getTextAttributeById($id){
        $option_name = Attribute::select('name')->where("id", $id)->where("type", "text")->first();
        if(isset($option_name->name)) {
            return $option_name->name;
        }
        return false;
    }

    public static function getAttributeById($id) {
        $data = Attribute::select('*')->where("id", $id)->first();
        return $data;
    }
    

    public static function getExistCartData($user_id,$product_id){
        $cart_info=CartItems::where("user_id", $user_id)
                    ->where("product_id", $product_id)
                    ->orderBy('id','DESC')
                    ->first();
        return $cart_info;
    }

    public static function getCountryData($user_id,$product_id){
        $countries = Country::get(["name", "id"]);
        return $countries;
    }

    public static function getCountryName($id){
        $vendorInfo=Country::select('name')->where("id", $id)->first();
        return $vendorInfo;
    }

    public static function getStateName($id){
        $vendorInfo=State::select('name')->where("id", $id)->first();
        return $vendorInfo;
    }

    public static function setMailWeb($subject,$to,$toname,$details,$mailtype,$filename=''){
        try {
            // Create SMTP Transport
            $transport = new \Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT'), env('MAIL_ENCRYPTION') );

            // Authentication
            $transport->setUsername(env('MAIL_USERNAME'));
            $transport->setPassword(env('MAIL_PASSWORD'));

            // Mailer
            $mailer = new \Swift_Mailer( $transport );

            // Message
            $message = new \Swift_Message();

            // Subject
            $message->setSubject($subject);

            // Sender
            $message->setFrom( [env('MAIL_FROM_ADDRESS') => env('MAIL_FROM_NAME') ] );

            // Recipients
            $message->addTo( $to, $toname );

            // CC - Optional
            //$message->addCc( 'cc@gmail.com', 'CC Name' );

            // BCC - Optional
            //$message->addBcc( 'bcc@gmail.com', 'BCC Name' );

            // Body
             if($mailtype=='verifyemail'){
                $view = new userVerifyMail($details);
            }elseif($mailtype=='order-status'){
                $view = new UserOrderStatusMail($details);
            }elseif($mailtype=='forgetemail'){
                $view = new ForgetPasswordMail($details);
            }elseif($mailtype=='reset-pass'){
                $view = new userResetPassMail($details);
            }elseif($mailtype=='orderemail'){
                $view = new userOrderMail($details);
            }elseif($mailtype=='vendor-order-email'){
                $view = new vendorOrderMail($details);
            }elseif($mailtype=='admin-order-email'){
                $view = new adminOrderMail($details);
            }elseif($mailtype=='installation-invoice'){
                $view = new userInvoiceMail($details,$filename);
            }elseif($mailtype=='installation-invoice-pay-success'){
                $view = new userInvoicePaymentSuccessMail($details);
            }elseif($mailtype=='admin-notify-invoice-pay-success'){
                $view = new AdminNotifyInvoicePaySuccMail($details);
            }
             
            $html = $view->render();

            $message->setBody( $html, 'text/html' );

            // Send the message
            $result = $mailer->send( $message );
        }
        catch( Exception $exc ) {

            echo $exc->getMessage();
        }
    }

    public static function getProdSelOptions($attrbid, $prodid){
        $record = DB::table('product_attributes')->join('attribute_options','product_attributes.option_id','=','attribute_options.id')->select('product_attributes.option_id as optionid','attribute_options.options as optionname','product_attributes.variable_price as variableprice','product_attributes.id as productattributes_id')->where('product_attributes.product_id','=', $prodid)->where('product_attributes.attribute_id','=', $attrbid)->where('product_attributes.deleted_at','=', null)->get();
        return $record;
    }

    public static function getAttributeName($attribid){
        $attributename = "";
        $record = Attribute::select('name')->where('id', $attribid)->first();
        foreach($record as $reclist){ $attributename = $reclist->name; }
        return $attributename;
    }
    public static function getAttributeType($attribid){
        $attributetype = "";
        $record = Attribute::select('type')->where('id', $attribid)->first();
        foreach($record as $reclist){ $attributetype = $reclist->type; }
        return $attributetype;
    }
    public static function getAttrOptionName($optionid){
        $optname = "";
        $record = AttributeOption::select('options')->where('id', $optionid)->first();
        foreach($record as $reclist){ $optname = $reclist->options; }
        return $optname;
    }
    public static function getProdAllCatName($catid){
        $catarr = json_decode($catid);
        $nmlist = "";
        foreach($catarr as $catlist){
            $nmlist .= Self::getProdCatName($catlist);
        }
        return $nmlist;
    }

    public static function getProAttribute($product_id,$attribid){
        $attributename = "";
        $record = ProductAttributes::where('product_id', $product_id)->where('attribute_id', $attribid)->first();        
        return $record;
    }

    public static function getProAttributeById($id){
        $attributename = [];
        $record = ProductAttributes::where('id', $id)->first();
        $attribute = Attribute::where('id', $record["attribute_id"])->first();
        $attribute_option = AttributeOption::where('id', $record["option_id"])->first();
        $attributename['attribute']= $attribute;
        $attributename['option']= $attribute_option;          
        return $attributename;
    }

    public static function getProductDataById($product_id){
        $record = Product::select('name')->where('id', $product_id)->first();
        return $record;
    }

    public static function deleteUserData($user_id){
       //print_r($user_id);exit;
        $user_data=User::where('id', $user_id)->delete();
        $cart_items=CartItems::where('user_id', $user_id)->delete();
        $customer_addresses=CustomerAddresses::where('user_id', $user_id)->delete();
        $order=Orders::where('user_id', $user_id)->get();
        if($order->isNotEmpty()){ 
            foreach($order as $value){                
                $orderitem = OrderItems::where('order_id',$value->id)->get();
                if($orderitem->isNotEmpty()){ 
                    foreach($orderitem as $item){  
                     $orderitem = OrderItemAttributes::where('order_item_id',$item->id)->delete();
                    }    
                }
                $orderitem = OrderItems::where('order_id',$value->id)->delete();
                $order_comments = OrderComments::where('order_id',$value->id)->delete();
                $order_addresses = OrderAddresses::where('order_id',$value->id)->delete();
            }
            $order=Orders::where('user_id', $user_id)->delete();
        }
        return 1;
    } 
    
    public static function getParentCat($PID) {
        $data = ProductCategory::select('name')->whereIn('id',explode(',', $PID))->orderBy('id','DESC')->get();
        $parentName = [];
        foreach ($data as $key => $value) {
            array_push($parentName, $value['name']);
        }
        $pName = implode(',', $parentName);
        return $pName;
    }
    
    public static function getVendorName($vendor_id) {
        $data = User::select('name')->where('id','=',$vendor_id)->first();
        return $data['name'];
    }

    public static function getVendorLogo($vendor_id) {
        $data = User::select('user_logo')->where('id','=',$vendor_id)->first();
        return $data['user_logo'];
    }
    
    public static function isParent($id) {
        $data = ProductCategory::select('parent_id')->where('parent_id','=',$id)->first();
        return $data?$data['parent_id']:'0';
    }
    public static function passwordGenerator(){
        $lower_case = "abcdefghijklmnopqrstuvwxyz";
        $upper_case = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "1234567890";
        $symbols = "@#$%&";

        $lower_case = str_shuffle($lower_case);
        $upper_case = str_shuffle($upper_case);
        $numbers = str_shuffle($numbers);
        $symbols = str_shuffle($symbols);

        $password = substr($lower_case, 0, 3);
        $password .= substr($upper_case, 0, 2);
        $password .= substr($numbers, 0, 3);
        $password .= substr($symbols, 0, 2);

        return $password;
    }

    public static function getPageData($vendor_id) {
        $page = Pages::where('vendor_id', $vendor_id)
                    ->where('is_home', 1)
                    ->where('status', 1)
                    ->first(); 
        return $page;
    }   

    public static function getMetaTags($type, $id, $slug) {
        $metaTitle = '';
        $metaDescription = '';
        $metaKeywords = '';
        $data = [];

        switch ($type) {
            case 'page':
                $page = Pages::where('vendor_id', $id)
                    ->where('is_home', 1)
                    ->where('status', 1)
                    ->first(); 
                    if($page) {
                        $data = Pages::find($page->id);
                    }
                break;
            case 'category':
                $data = ProductCategory::select('*')
                        ->where('slug', '=', $slug)
                        ->where('vendor_id', '=', $id)
                        ->where('status', '=', 1)
                        ->where('has_parent', '=', 0)
                        ->first();
                break;
            case 'sub-category':
                $data = ProductCategory::select('*')
                        ->where('slug', '=', $slug)
                        ->where('vendor_id', '=', $id)
                        ->where('status', '=', 1)
                        ->where('has_parent', '=', 1)
                        ->first();
                break;
            case 'product':
                $data = Product::where('slug', '=', $slug)
                                ->where('status', '=', 1)
                                ->where('vendor_id', $id)
                                ->first();
                break;
            default:
                return [
                    'metaTitle' => 'Colorcorp',
                    'metaDescription' => 'Colorcorp',
                    'metaKeywords' => 'Colorcorp',
                ];
        }
        //dd($data->meta_title);

        if ($data) {
            $metaTitle = ($data->meta_title) ? $data->meta_title : 'Colorcorp' ;
            $metaDescription = ($data->meta_description) ? $data->meta_description : 'Colorcorp' ;
            $metaKeywords = ($data->meta_keywords) ? $data->meta_keywords : 'Colorcorp' ;
        } else {
            $metaTitle = 'Colorcorp';
            $metaDescription = 'Colorcorp';
            $metaKeywords = 'Colorcorp';
        }

        return [
            'metaTitle' => html_entity_decode($metaTitle),
            'metaDescription' => html_entity_decode($metaDescription),
            'metaKeywords' => html_entity_decode($metaKeywords),
        ];
    }


    public static function getDataForReorder($id) {
        $user = Auth::user();
        $data = Orders::where("id", $id)->orderBy('id', 'DESC')->first();
        $ICdata = InstallationCharges::where("order_id", $id)->where("user_id", $user->id)->first(); 
        $ICIdata = [];
        foreach ($data['orderItems'] as $key => $value) {
            $ICIdata    = InstallationChargeItems::where("order_item_id", $value->id)->first();
            if(!empty($ICIdata)){
                if($value->installation=='yes' && $ICIdata->order_item_id==$value->id){
                    $data['orderItems'][$key]['charges'] = $ICIdata->charges;
                }else{
                    $data['orderItems'][$key]['charges'] = 0;
                }
            }else{
                $data['orderItems'][$key]['charges'] = 0;
            }
        }
        return array('data' => $data, 'ICIdata' => $ICIdata);
    }
}