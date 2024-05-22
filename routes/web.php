<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Vendor\VendorShopSettingsController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Admin\VoyagerAttributesController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\InstallationInvoiceController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\CategoryController as CategoryManagementController;
use App\Http\Controllers\Vendor\ProductController as ProductManagementController;
use App\Http\Controllers\Vendor\CustomerController as CustomerManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\customer\CustomerMangController;
use App\Http\Controllers\mailChkController;
use App\Http\Controllers\customer\ProductManageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\customer\CategoryManageController;
use App\Http\Controllers\customer\CheckoutController;
use App\Http\Controllers\customer\CartMangController;
use App\Http\Controllers\customer\CustomerOrderController;
use App\Http\Controllers\customer\PDFController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('phpmyinfo', function () {
    phpinfo(); 
})->name('phpmyinfo');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/clear-cache', function () {
    if (Auth::check()) {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return response()->json(['message' => 'Caches cleared successfully']);
    } else {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
})->name('clear.cache');


Route::get('voyager/get-vendors-for-role', [App\Http\Controllers\HomeController::class, 'getVendorsForRole']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('admin-user', [UserController::class, 'adminUser'])->name('admin-user');
    Route::get('admin-user/create', [UserController::class, 'createadminUser'])->name('admin-admin-create');
    Route::post('admin-user/save', [UserController::class, 'saveadminUser'])->name('admin-user.save');
    Route::get('admin-user/edit/{id?}', [UserController::class, 'editadminUser'])->name('admin-user.edit');
    Route::post('admin-user/update', [UserController::class, 'updateadminUser'])->name('admin-user.update');
    Route::DELETE('admin-user/delete', [UserController::class, 'deleteAdminUser'])->name('admin-user.delete');

    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('voyager.dashboard');

    // Product Categories
    Route::get('get-parent-categories', [CategoryController::class, 'getParentCategories'])->name('getParentCategories');
    Route::get('/categories/create', [CategoryController::class, 'createCategory'])->name('categories.add');
    Route::post('/categories/store', [CategoryController::class, 'storeCategory']);
    Route::get('/categories/{id}/edit', [CategoryController::class, 'editCategory']);
    Route::post('/categories/update', [CategoryController::class, 'updateCategory']);
    Route::post('/categories/{id}/delete', [CategoryController::class, 'deleteCategory']);

    // Route for pages
    Route::get('/pages/create', [PageController::class, 'createPages'])->name('pages.add');
    Route::post('/pages/store', [PageController::class, 'storePages']);
    Route::get('/pages/{id}/edit', [PageController::class, 'editPages']);
    Route::post('/pages/update', [PageController::class, 'updatePages']);
    Route::post('/pages/{id}/delete', [PageController::class, 'deletePages']);


    // Route for Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customer/create', [CustomerController::class, 'customerCreate'])->name('customer.create');
    Route::post('/customer/save', [CustomerController::class, 'customerSave'])->name('customer.save');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'customerEdit'])->name('customer.edit');
    Route::post('/customer/update', [CustomerController::class, 'customerUpdate']);
    Route::post('/customer/delete/', [CustomerController::class, 'customerDelete'])->name('customer.delete');


    // Route for Admin pages
    Route::get('/products/create', [ProductController::class, 'createProducts'])->name('voyager.products.create');
    Route::get('/get-categories', [ProductController::class, 'getCategories'])->name('getCategories');
    Route::get('/remove-image', [ProductController::class, 'removeProdGalaryImg'])->name('removeProdGalaryImg');
    Route::post('/products/store', [ProductController::class, 'storeProducts']);
    Route::get('/products/{id}/edit', [ProductController::class, 'editProducts'])->name('voyager.products.edit');
    Route::post('/products/update', [ProductController::class, 'updateProducts']);
    Route::get('/products/{id}/delete', [ProductController::class, 'deleteProducts']);
    Route::get('/products/{id}', [ProductController::class, 'viewProducts'])->name('voyager.products.show');
    
    // Attribute
    Route::get('attributes/create', [VoyagerAttributesController::class, 'addnewattribute'])->name('attributes.create');
    Route::post('attributes/save', [VoyagerAttributesController::class, 'processattribute'])->name('attributes.save');
    Route::get('attributes/edit/{id?}', [VoyagerAttributesController::class, 'editattribute'])->name('attributes.edit');
    Route::post('attributes/update', [VoyagerAttributesController::class, 'processeditattribute'])->name('attributes.update');
    Route::post('attributes/delete', [VoyagerAttributesController::class, 'deleteattribute'])->name('attributes.delete');

    // Preparation Invoice
    Route::get('/installation-invoice', [InstallationInvoiceController::class, 'index'])->name('installation-invoice');
    Route::get('/installation-invoice/create/{id?}', [InstallationInvoiceController::class, 'createInvoice'])->name('installation-invoice.create');
    Route::post('/installation-invoice/save', [InstallationInvoiceController::class, 'saveInvoice'])->name('installation-invoice.save');
    Route::get('/installation-invoice-pay/{id?}', [InstallationInvoiceController::class, 'payInvoice']);
});

Route::post('api/fetch-states', [VendorShopSettingsController::class, 'fetchState']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/verify/{id?}', [LoginController::class, 'verifyUser']);
Route::post('/resetlink', [LoginController::class, 'resetLink']);
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

//Route::get('/mailchk', [mailChkController::class, 'index']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('resetpassword', [ResetController::class, 'resetPassword'])->name('resetpassword');
    Route::post('resetpassword-action', [ResetController::class, 'resetPasswordAction'])->name('reset.password');
    Route::get('resetpassword-skip', [ResetController::class, 'resetPasswordShik'])->name('reset.skip');
    Route::get('/logout', [LoginController::class, 'logout']);
});

Auth::routes();

Route::middleware(['auth', 'roles:vendor'])->prefix('vendor')->group(function(){ 
    // Vendor Dashboard
    Route::get('dashboard-setting', [VendorShopSettingsController::class, 'shopSetting'])->name('vendor.shopSetting');
});

Route::middleware(['auth', 'roles:customer'])->group(function(){
    Route::get('/account', [CustomerMangController::class, 'index'])->name('account');
    Route::post('/accountUpdate', [CustomerMangController::class, 'accountUpdate'])->name('account.update');
    Route::get('/account/addresses', [CustomerMangController::class, 'accountAddresses'])->name('account.addresses');
    Route::get('/account/addresses/add', [CustomerMangController::class, 'accountAddressesAdd'])->name('addresses.add');
    Route::post('/account/addresses/create', [CustomerMangController::class, 'accountAddressesCreate'])->name('addresses.create');
    Route::get('/account/addresses/edit/{id?}', [CustomerMangController::class, 'accountAddressesEdit'])->name('addresses.edit');
    Route::post('/account/addresses/update', [CustomerMangController::class, 'accountAddressesUpdate'])->name('addresses.update');
    Route::post('/account/addresses/delete', [CustomerMangController::class, 'accountAddressesDetete'])->name('addresses.delete');
    Route::get('/account/resetpassword', [CustomerMangController::class, 'accountResetPassword'])->name('password.reset');
    Route::get('/account/my-order', [CustomerOrderController::class, 'myOrder'])->name('my.order');
    Route::get('/account/view-order/{id?}', [CustomerOrderController::class, 'viewOrder'])->name('view.order');
    Route::get('/{vendor_name}/cart', [CheckoutController::class, 'cart'])->name('cart');
    Route::get('/{vendor_name}/checkout', [CheckoutController::class, 'cartCheckout'])->name('cartCheckout');
    Route::get('{vendor_name}/invoice-checkout/{id?}', [CheckoutController::class, 'invoiceCheckout'])->name('invoiceCheckout');
    Route::post('{vendor_name}/place-order', [CheckoutController::class, 'placeOrder'])->name('place.order');
    Route::get('{vendor_name}/thank-you/{id?}', [CheckoutController::class, 'thankYou'])->name('thankYou');

    // addded by sahina 
     Route::post('{vendor_name}/place--invoice-order', [CheckoutController::class, 'placeInvoiceOrder'])->name('place.invoiceOrder');
      Route::get('{vendor_name}/invoice-thank-you/{id?}', [CheckoutController::class, 'invoicethankYou'])->name('invoicethankYou');
     

    // Products Pages
    Route::get('/{vendor_name}/search-results', [CategoryManageController::class, 'searchResults'])->name('search.results');
    Route::get('/{vendor_name}', [CategoryManageController::class, 'vendorRediect'])->name('vendor.index');
    Route::get('/vendor/{vendor_name}', [CategoryManageController::class, 'index'])->name('shop');
    Route::get('/{vendor_name}/{category_slug?}', [CategoryManageController::class, 'showSubcategories'])->name('shop.subcategories');
    Route::post('/search-category', [CategoryManageController::class, 'searchCategories'])->name('search.category');
    Route::post('/load-more-category', [CategoryManageController::class, 'loadMoreCategory'])->name('loadmore.category');
   
    Route::get('/{vendor_name}/{parent_category}/{category}/product-list', [ProductManageController::class, 'productslist'])->name('product.list');
    Route::get('/{vendor_name}/{category}/single-product/{prodslug?}', [ProductManageController::class, 'singleproductdetail'])->name('product.single');

    Route::post('/addtocart', [CheckoutController::class, 'cartAdd'])->name('add.cart');
    Route::post('/addtocartforreorder', [CheckoutController::class, 'addReorder'])->name('add.cart.to.reorder');
    Route::post('/deletecart', [CheckoutController::class, 'cartDelete'])->name('delete.cart');
    Route::post('/updatecart', [CheckoutController::class, 'cartUpdate'])->name('update.cart');
    Route::post('/get-cart-product', [CheckoutController::class, 'getCartProduct'])->name('product.cart');
    Route::post('/get-installation-product', [CheckoutController::class, 'getInstallationProduct'])->name('product.installation');
    Route::get('/account/order-invoice-pdf/{id?}', [PDFController::class, 'getOrderInvoicePdf'])->name('invoice.order');
    Route::get('/account/installation-invoice-pdf/{id?}', [PDFController::class, 'genrateInstallationInvoicePdf'])->name('invoice.installation.pdf');
});

Route::middleware(['auth', 'roles:vendor'])->prefix('vendor')->group(function(){ 
    // Vendor Dashboard
    //Route::get('dashboard-setting', [VendorShopSettingsController::class, 'shopSetting'])->name('vendor.shopSetting');
    Route::post('vendor-shop-setting-action', [VendorShopSettingsController::class, 'createShopSetting'])->name('shopSetting.create');
    
    Route::get('/{vendor_name}/dashboard', [DashboardController::class, 'index'])->name('vendor.dashboard');
    Route::get('/{vendor_name}/vendor-shop-setting-edit', [VendorShopSettingsController::class, 'editShopSetting'])->name('vendor.shopSetting.edit');
    Route::post('vendor-shop-setting-update/{id}', [VendorShopSettingsController::class, 'updateShopSetting'])->name('shopSetting.update');
    Route::post('vendor-color-setting-update/{id}', [VendorShopSettingsController::class, 'updateColorSetting'])->name('colorSetting.update');

    //vendor customer management
    Route::get('/{vendor_name}/customer-management', [CustomerManagementController::class, 'index'])->name('vendor.customer');

    // Product Management Routes
    Route::get('/{vendor_name}/product-management', [ProductManagementController::class, 'index'])->name('vendor.product');
    Route::get('/{vendor_name}/product-management/view/{id?}', [ProductManagementController::class, 'viewProduct'])->name('vendor.product.view');
    

    Route::get('/{vendor_name}/order-management', [VendorOrderController::class, 'index'])->name('vendor.order');
    Route::get('/{vendor_name}/order-management/edit/{id?}', [VendorOrderController::class, 'edit'])->name('vendor.order.edit');
    Route::post('/{vendor_name}/order-management/update', [VendorOrderController::class, 'update'])->name('vendor.order.update');
    Route::get('/{vendor_name}/myaccount', [DashboardController::class, 'myAccount'])->name('vendor.myaccount');
    Route::post('/myaccountUpdate', [DashboardController::class, 'myaccountUpdate'])->name('myaccount.update');
    Route::get('/{vendor_name}/resetpassword', [DashboardController::class, 'resetPassword'])->name('vendor.resetpassword');






     // Vendor Category Management
    /*Route::get('/{vendor_name}/category-management', [CategoryManagementController::class, 'index']);
    Route::get('/{vendor_name}/category-management/add', [CategoryManagementController::class, 'create'])->name('vendordashboard.category-add');
    Route::post('/{vendor_name}/category-management/store', [CategoryManagementController::class, 'store']);
    Route::get('/{vendor_name}/category-management/edit/{id?}', [CategoryManagementController::class, 'edit'])->name('vendordashboard.category-edit');
    Route::post('/{vendor_name}/category-management/update', [CategoryManagementController::class, 'update']);
    Route::get('/{vendor_name}/category-management/delete/{id?}', [CategoryManagementController::class, 'delete'])->name('vendordashboard.category-edit');
    Route::get('/{vendor_name}/category-management/admin-categories', [CategoryManagementController::class, 'showAdminCategories']);
    Route::post('/{vendor_name}/category-management/store-vendor-admin-category', [CategoryManagementController::class, 'storeVendorAdminCategory']); */

});