<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\VendorShopSettings;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Product;
use Helper;
use DB;

class DashboardController extends Controller
{
    //
    public function index($vendor_name) 
    {
        $user = Auth::user();        
        $vendor = VendorShopSettings::where("vendor_id", $user->id)->first(); 
        $chartData = []; 
        for ($i = 1; $i <= 12; $i++) {
            $chartData['customers'][$i] = 0;
            $chartData['orders'][$i] = 0;
            $chartData['products'][$i] = 0;
        }
        // Customer
        $customers      = User::where('vendor_id', $user->id)->where('email_verified_at', '!=', null)->orderBy('id', 'DESC')->limit(5)->get();
        $totalCustomers = User::where('vendor_id', $user->id)->where('email_verified_at', '!=', null)->count();
        $chartData['customers'] = implode(',', $this->customersByMonth($user->id, $chartData));

        // Orders
        $totalOrders = Orders::where('vendor_id', $user->id)->where('status', '!=', 0)->count();
        $chartData['orders'] = implode(',', $this->ordersByMonth($user->id, $chartData));

        // Products
        $totalProducts = Product::where('vendor_id', $user->id)->where('status', 1)->count();
        $chartData['products'] = implode(',', $this->productsByMonth($user->id, $chartData));

        // Top sales Products
        $topSalesProducts = $this->topSalesProducts($user->id);

        if($vendor_name == $vendor->shop_url_slug){
            Session::put('vendordata', $vendor);
            return view('vendordashboard.dashboard', compact('customers','totalCustomers', 'totalOrders', 'totalProducts', 'chartData', 'topSalesProducts'));
        }else{
            return redirect()->route('vendor.dashboard', ['vendor_name' => $vendor->shop_url_slug]);
        }
    }

    public function vendorRediect() {
        $user = Auth::user();
        $shop_url_slug = Helper::getShopslug($user->id);
        return redirect()->route('vendor.dashboard', ['vendor_name' => $shop_url_slug]);
    }

    public function customersByMonth($vendor_id, $chartData) {
        $customers = User::where('vendor_id', $vendor_id)->where('status', 1)->get();
        foreach($customers as $customer){
            $month = date('n', strtotime($customer->created_at));
            $chartData['customers'][$month] = $chartData['customers'][$month] + 1;
        }
        return $chartData['customers'];
    }

    public function ordersByMonth($vendor_id, $chartData) {
        $orders = Orders::where('vendor_id', $vendor_id)->where('status', '!=', 0)->get();
        foreach($orders as $order){
            $month = date('n', strtotime($order->created_at));
            $chartData['orders'][$month] = $chartData['orders'][$month] + 1;
        }
        return $chartData['orders'];
    }

    public function productsByMonth($vendor_id, $chartData) {
        $products = Product::where('vendor_id', $vendor_id)->where('status', 1)->get();
        foreach($products as $product){
            $month = date('n', strtotime($product->created_at));
            $chartData['products'][$month] = $chartData['products'][$month] + 1;
        }
        return $chartData['products'];
    }

    public function topSalesProducts($vendor_id){
        $data = DB::table('order_items')
                ->select('product_id', DB::raw('SUM(pro_att_price) as total_price'), DB::raw('count(*) as total'))
                ->join('orders', 'order_id', '=', 'orders.id')
                ->where('orders.vendor_id', $vendor_id)
                ->groupBy('product_id')
                ->orderBy('total', 'DESC')
                ->limit(7)
                ->get();
        return $data;
    }

    public function myAccount($vendor_name)
    {  
        $user = Auth::user();
        return view('vendordashboard.my-account',compact('user'));
    }

    public function myaccountUpdate(Request $request){ 
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'mobile_number' => 'required',
        ]);

        $user = Auth::user();
        $userdata = user::find($user->id);
        $userdata->name =  $request->name;        
        $userdata->mobile_number = $request->mobile_number;
    
        $userdata->update();

        return redirect()->back()->with([
            'message'    => 'account data updated successfully',
            'alert-type' => 'success',
        ]);  
    }

    public function resetPassword($vendor_name)
    {  
        $user = Auth::user();
        return view('vendordashboard.vendor-reset-pass',compact('user'));
    }
}
