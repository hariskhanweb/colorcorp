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
use App\Models\CustomerAddresses;
use App\Models\Country;
use App\Models\State;
use Redirect;
use Mail;
use Helper;
use File;
use DB;

class CartMangController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $data=User::where("id", $user->id)->first();
        $page = Helper::getPageData(Auth::user()->vendor_id);
        return view('customer.account',compact('data','page'));
    } 
}