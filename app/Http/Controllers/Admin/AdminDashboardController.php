<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\VendorShopSettings;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Product;
use Helper;
use DB;

class AdminDashboardController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function dashboard() 
    {       
        $user = Auth::user(); 
        if(!$user){
            return Voyager::view('voyager::login');
        }       

        $chartData = []; 
        for ($i = 1; $i <= 12; $i++) {
            $chartData['customers'][$i] = 0;
            $chartData['vendors'][$i] = 0;
            $chartData['orders'][$i] = 0;
            $chartData['products'][$i] = 0;
        }
        // Users
        // $vendors   = User::where('role_id', 2)->where('email_verified_at', '!=', null)->orderBy('id', 'DESC')->limit(5)->get();
        $vendors   = User::where('role_id', 2)->orderBy('id', 'DESC')->limit(5)->get();
        // $totalVend = User::where('role_id', 2)->where('email_verified_at', '!=', null)->count();
        $totalVend = User::where('role_id', 2)->count();
        $totalCust = User::where('role_id', 3)->where('email_verified_at', '!=', null)->count();

        $chartData['customers'] = implode(',', $this->customersByMonth($chartData));
        $chartData['vendors'] = implode(',', $this->vendorsByMonth($chartData));

        // Orders
        $totalOrders = Orders::where('status', '!=', 0)->count();
        $chartData['orders'] = implode(',', $this->ordersByMonth($chartData));

        // Products
        $totalProducts = Product::where('status', 1)->count();
        $chartData['products'] = implode(',', $this->productsByMonth($chartData));

        // Top sales Products
        $topVendorsOrder = $this->topVendorsOrder();
        $totalRevenue    = $this->totalRevenue();

        return Voyager::view('voyager::dashboard.index', compact('vendors', 'totalCust', 'totalVend', 'totalOrders', 'totalProducts', 'chartData', 'topVendorsOrder', 'totalRevenue'));
    }

    public function vendorRediect() {
        $user = Auth::user();
        $shop_url_slug = Helper::getShopslug($user->id);
        return redirect()->route('vendor.dashboard', ['vendor_name' => $shop_url_slug]);
    }

    public function customersByMonth($chartData) {
        $customers = User::where('role_id', 3)->where('email_verified_at', '!=', null)->get();
        foreach($customers as $customer){
            $month = date('n', strtotime($customer->created_at));
            $chartData['customers'][$month] = $chartData['customers'][$month] + 1;
        }
        return $chartData['customers'];
    }

    public function vendorsByMonth($chartData) {
        // $vendors = User::where('role_id', 2)->where('email_verified_at', '!=', null)->get();
        $vendors = User::where('role_id', 2)->get();
        foreach($vendors as $customer){
            $month = date('n', strtotime($customer->created_at));
            $chartData['vendors'][$month] = $chartData['vendors'][$month] + 1;
        }
        return $chartData['vendors'];
    }

    public function ordersByMonth($chartData) {
        $orders = Orders::where('status', '!=', 0)->get();
        foreach($orders as $order){
            $month = date('n', strtotime($order->created_at));
            $chartData['orders'][$month] = $chartData['orders'][$month] + 1;
        }
        return $chartData['orders'];
    }

    public function productsByMonth($chartData) {
        $products = Product::where('status', 1)->get();
        foreach($products as $product){
            $month = date('n', strtotime($product->created_at));
            $chartData['products'][$month] = $chartData['products'][$month] + 1;
        }
        return $chartData['products'];
    }

    public function topVendorsOrder(){
        $data = Orders::leftJoin('users', 'users.id', '=', 'orders.vendor_id')
               ->select('orders.vendor_id as vid', 'users.name', DB::raw('SUM(orders.total_amount) as total_price'), DB::raw('count(*) as total'))
               ->where(DB::raw('YEAR(CURDATE())'), '=', DB::raw('DATE_FORMAT(`orders`.`created_at`,"%Y")'))
               ->groupBy('vid')
               ->orderBy('total', 'DESC')
               ->limit(7)
               ->get();
        return $data;
    }

    public function totalRevenue(){
        $data = DB::table('orders')
                ->select('vendor_id', DB::raw('SUM(total_amount) as total_rev'), DB::raw('count(*) as total'))
                ->where(DB::raw('YEAR(CURDATE())'), '=', DB::raw('DATE_FORMAT(`orders`.`created_at`,"%Y")'))
                ->where('status', '!=', 0)
                ->first();
        return $data?$data->total_rev:0;
    }
}
