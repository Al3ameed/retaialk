<?php

use App\CategoryProducts;
use App\ProductVariation;
use App\ProductVariations;
use App\VariantOption;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Hash;

Route::get('products/categories', function (Request $request) {

    $products = \App\Products::all();

    foreach ($products as $product) {

        $exist = CategoryProducts::where(['product_id' => $product->id,
            'category_id' => $product->item_group])->first();

        if (!$exist) {

            CategoryProducts::create([
                'product_id' => $product->id,
                'category_id' => $product->item_group,
            ]);
        }

        if (!$exist) {
            if ($product->second_item_group) {

                \App\CategoryProducts::create([
                        'product_id' => $product->id,
                        'category_id' => $product->second_item_group,
                    ]);
            }
        }
    }
});

Route::get('warehouse_d', function (Request $request) {

    $districts = \App\District::all();
    $warehouses = \App\Warehouses::all();
    foreach ($warehouses as $warehouse) {
        foreach (json_decode($warehouse->district_id) as $district_id) {

            \App\District::where('id', $district_id)
                ->update([
                    'warehouse_id' => $warehouse->id,
                ]);
        }
    }


});

Route::get('product_variants', function (Request $request) {

    $products_variations = ProductVariations::all();

    foreach ($products_variations as $products_variation) {
        $variations_data = json_decode($products_variation->variations_data);

        if (is_array($variations_data) && count($variations_data) > 0) {
            foreach ($variations_data as $variation) {

                $exp = explode(':', $variation);

                $result = VariantOption::create([
                    'variant_data_id' => $exp[0],
                    'variant_meta_id' => $exp[1],
                ]);


                $result_1 = ProductVariation::where('product_id', $products_variation->products_id)
                    ->where('variant_option_id', $result->id)->first();

                if (!$result_1)
                    ProductVariation::create([
                        'product_id' => $products_variation->products_id,
                        'variant_option_id' => $result->id,
                    ]);


            }
        }
    }
});

Route::get('col_products', function (Request $request) {

    $col_items = \App\CollectionItem::all();
    foreach ($col_items as $item) {
        $product = \App\Products::where('item_code', $item->item_code)->first();

        if ($product) {
            $item->product_id = $product->id;
            $item->save();
        } else {
            $itemCodes[] = $item;
        }

    }


});

Route::group(['middleware' => 'logs'], function () {

    // Route::group(['middleware' => 'auth:api'], function() {
    //Favorites
    Route::post('/user/favorites', 'Favorites\FavoritesController@addFavorite');
    Route::get('/user/favorites', 'Favorites\FavoritesController@listFavourite');
    Route::delete('/user/favorites', 'Favorites\FavoritesController@deleteFavorite');
    // });
    //Products
    // get products related to product in query parameter
    //get one products

    // Categories
    Route::get('/categories ', 'Category\CategoryApiController@getCategories');
    Route::get('/categories/{parentParams}', 'Category\CategoryApiController@getsubcategories');
    Route::get('/productsattributes', 'Category\CategoryApiController@getCategoryProductsFilters');
    Route::get('/categorytree', 'Category\CategoryApiController@categorytree');
    Route::get('/products', 'Category\CategoryApiController@getCategoryProduct');
    Route::get('/products2', 'Category\CategoryApiController@getCategoryProduct2');
    Route::get('/categoryimg/{id}', 'Category\CategoryApiController@categoryimg');

    // District
    Route::get('/districts', 'UsersController@getdistricts');

    // StoreApi
    Route::get('shop-types', 'Shop\StoreApiController@getAllShopTypes');
    Route::put('store-details', 'Shop\StoreApiController@updateStoreDetails');

    //User
    Route::post('/address', 'UsersController@addnewaddress');
    Route::get('/address', 'UsersController@getaddress');

    Route::post('/register', 'UsersController@doapiregister');
    Route::get('/guest', 'UsersController@guestRegister');

    Route::post('/login', 'UsersController@apilogin');
    Route::post('user/checkphone', 'UsersController@checkphone');

    Route::post('/cart/', 'SalesOrderController@addtocart');
    Route::get('/cart', 'SalesOrderController@getcart');
    Route::get('/cart/delete/{product_id}/{token}', 'SalesOrderController@removefromcart');
    Route::post('/checkout', 'SalesOrderController@checkout');
    Route::get('/shipping', 'SalesOrderController@getshipping');

    //wishlist (for web)
    Route::post('/user/wishlist', 'wishlist\WishlistController@addWishlist');
    Route::get('/user/wishlist', 'wishlist\WishlistController@listWishlist');
    Route::delete('/user/wishlist', 'wishlist\WishlistController@deleteWishlist');

    //Payment
    Route::post('/payment_methods/assign', 'Payment\PaymentController@assingnPayment');

    //Facebook
    Route::post('/login/facebook', 'UsersController@loginFacebook');
    //check app version
    Route::get('/checkappversion', 'UsersController@checkAppVersion');
});

//User
Route::get('/user/data/', 'UsersController@getUser');
Route::put('/user/', 'UsersController@updateUserData');
Route::post('/user/validatepassword', 'UsersController@validateUserPassword');
Route::put('/user/changepassword', 'UsersController@updateUserPassword');
Route::get('userprofile', 'UsersController@getProfile');
Route::put('/user/address/{addresse_id}/', 'UsersController@updateAddress');
Route::delete('/user/address/{addresse_id}/', 'UsersController@deleteAddress');
Route::get('/categorytree', 'Category\CategoryApiController@categorytree');

//Payments
Route::get('/payment_methods2', 'Payment\PaymentController@paymentsMethod');
Route::get('/payment_methods', 'Payment\PaymentController@paymentsMethod2'); // Do Not Delete This One
Route::post('/payment_methods/assign', 'Payment\PaymentController@assingnPayment');

// checkout we accept payment
//Route::post('checkout/payment', 'WeAcceptPaymentsController@checkout');
//Route::any('checkout/payment/callback', 'WeAcceptPaymentsController@callback');
Route::post('checkout/payment', 'WeAcceptPaymentsController@checkout');
Route::get('checkout/payment/weaccept/response', 'WeAcceptPaymentsController@callback');
Route::any('payment/order/status', 'WeAcceptPaymentsController@status');

//DeliveryMan
Route::post('/delivery/login', 'DeliveryApi\DeliveryApiController@login');
Route::post('/delivery/changePassword', 'DeliveryApi\DeliveryApiController@changePassword');

//Delivery-Orders
Route::get('delivery/delivers', 'DeliveryOrders\DeliveryOrderApiController@getall');
Route::get('delivery/orders', 'DeliveryOrders\DeliveryOrderApiController@getDeliveryOrder');

Route::get('delivery/user/orders/', 'DeliveryOrders\DeliveryOrderApiController@getUserOrders');
Route::get('orders/{id}', 'DeliveryOrders\DeliveryOrderApiController@getUserOrdersDetails');

// complains
Route::post('user/postcomplains', 'Complains\CustomerComplainsApiController@storeComplainForm');
Route::get('user/complains', 'Complains\CustomerComplainsApiController@getComplain');
Route::get('all/complains', 'Complains\CustomerComplainsApiController@getAllComplains');

//Orders
Route::get('delivery/order/status', 'DeliveryOrders\DeliveryOrderApiController@changeStatusOrder');

//Search Products

Route::get('configrations/', 'SettingsController@configrations');
// Route::get('productsweb/{item_group}', 'Products\ProductsController@getProductsByItemGroup');

//TimeSections
Route::get('/time/sections', 'TimeApi\TimeApiController@TimeSections');

//PromocodeValidation

Route::post('/promocode/validate', 'Promocode\PromocodeController@validateCode');

//Reset-Password
Route::post('/reset/password', 'ResetPasswordApi\ResetPasswordApiController@resetPasswordApi');

// Products
Route::get('product', 'Products\ProductsApiController@getProduct');
Route::get('product/cms', 'Products\ProductsApiController@getProductCms');
Route::get('/getproducts', 'Products\ProductsApiController@getProducts');
Route::get('related-products', 'Products\ProductsApiController@relatedProducts');
Route::get('/categories/products', 'Products\ProductsApiController@getProductBrandByCategoryID');
Route::get('search/products', 'Products\ProductsApiController@searchProducts');
Route::get('/search/products/all', 'Products\ProductsApiController@allProductSearch');

Route::get('filter/products', 'Products\ProductsApiController@filterProducts');
// Route::post('filter/products', 'Products\ProductsApiController@filterProducts');
Route::post('filter/products', 'Products\ProductsApiController@filterProducts');

//Brands
Route::get('brands/products', 'BrandApi\BrandApiController@getProducts');
Route::get('getbrands', 'BrandApi\BrandApiController@brands');
Route::get('/Bundel/All', 'BundlesCms\APIBundelController@getAllBundels');
Route::get('/collections', 'CollectionController@getApiCollections');
Route::get('/collections-v2', 'CollectionController@getApiCollections2');
Route::get('slider', 'SliderController@getSlider');
Route::get('/main/collection', 'CollectionController@getMainCollection');
Route::get('reorder/validate/{id}', 'DeliveryOrders\DeliveryOrderApiController@reOrderValidate');
// Push Notification
Route::post('/notifications/assign', 'pushApi\pushApiController@addDeviceToken');

Route::get('/usernotifyproduct/{id}', 'UsersController@userNotifyProductBack');
Route::delete('/usernotifyproduct/{id}', 'UsersController@removeuserNotifyProductBack');
// Route::get('/update/erpname', 'UsersController@changeName');
Route::get('/user/notify ', 'CategoryController@userItemNotify');

http: //localhost/goomla/admin/refreshdata
Route::get('/refreshdata', function () {
    return view('admin.refreshdata');
});
// about api
Route::get('/about', 'AboutController@about_api');
// faq api
Route::get('/faq', 'FAQController@listAllFAQ');

// faq api
Route::post('/contact-us', 'ContactusApiController@sendEmail');
Route::get('/testtt', function () {
    return 'it worked !!';
});

/* Route to check address has orders or not*/
Route::get('/checkAddressOrder/{id}' , 'SalesOrderController@checkAddressOrder');
Route::post('v2/checkout', 'CheckoutController@index');

Route::get('/home','Home\HomeApiController@index');
## Send Address Id and Promocode and calculate vat, total, ...
Route::post('/calculations/cart','SalesOrderController@cartCalculations');
Route::post('shipment/webhook' , 'Shipment\ShippmentController@webHookDelivery')->name('webhookURL');

Route::post('shipment/receipt/download', 'Shipment\ShipmentController@getShipmentReceipts');

Route::post('shipment/receipt/store', 'Shipment\ShipmentController@storeShipmentReceipts');


// weaccept callback routes
Route::post('checkout/payment/processing', 'WeAcceptPaymentsController@processCallBack');


Route::get('/checkout/payment/callback', 'WeAcceptPaymentsController@finalCallBack');
