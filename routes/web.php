<?php

use App\Events\ErrorEmail;

Route::get('test', function () {
    \Session::put('test', 'Yayeee works');
    return \Session::get('test');
});

//Route::get('/test', function () {
//	return view('Test');
//});

Route::get('/mail', function () {
    event(new ErrorEmail(10));
});
//Route::get('/', function () {
//	return view('welcome');
//});
Route::get('/', function () {
    return redirect('login');
});
Route::get('/storage', function () {
    abort(404);
});

Route::get('/fblogin', 'UsersController@osama');

// empty()
//Auth::routes();

Route::get('/admin/home', 'AdminHomeController@index');

//Reset-Paswwrod Api
Route::get('/reset/password/{token}', 'ResetPasswordApi\ResetPasswordController@ShowResetPasswordForm');
Route::post('/reset/password', 'ResetPasswordApi\ResetPasswordController@ResetPassword');
Route::get('/reset/password/beshir/{token}', 'ResetPasswordApi\ResetPasswordController@ShowResetPasswordFormBeshir');
Route::post('/reset/password/beshir', 'ResetPasswordApi\ResetPasswordController@ResetPasswordBeshir');

//Admin Auth
Route::get('admin/register', 'AdminAuth\RegisterController@showRegisterForm');
Route::post('admin/register', 'AdminAuth\RegisterController@register');

Route::get('admin/login', 'AdminAuth\LoginController@showLoginForm');
Route::post('admin/login', 'AdminAuth\LoginController@login');
Route::post('admin/logout', 'AdminAuth\LoginController@logout');
Route::post('admin/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
Route::get('admin/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
Route::post('admin/password/reset', 'AdminAuth\ResetPasswordController@reset');
Route::get('admin/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
// Route::get('updatePassword',function()
// {
//
//   //$password = bcrypt('Anw@rGl@Cm$118');
//   $password = bcrypt('BorujP@$$Trdng26218');
//   DB::table('admin_users')->where('id', 3)
//             ->update(['password' => $password]);
//             return "done";
// });

//Cms Routes
Route::get('/refreshimages', function () {

    $Products = DB::table('oldproducts3')->get();
    foreach ($Products as $Product) {
        $myimg = '';
        $myimg = $Product->image;

        if (strpos($myimg, 'oldErpnext') == false) {
            // $newImg = substr($myimg, 8);
            $newImg = 'oldErpnext/' . $myimg;
            DB::table('oldproducts3')->where('id', $Product->id)->update(['image' => $newImg]);
        }
        //$Product->image = $newImg;

        //$Product->save();
    }
});
Route::group(array('prefix' => '/admin/', 'middleware' => ['admin']), function () {



    Route::get('/search/user/{name}', 'GomlaUsers\UsersController@findUser');


    Route::get('aramex/', 'AramexController@openAramexTrackView');
    Route::get('aramex/shipment/create', 'AramexController@createShipment');
    Route::get('aramex/shipment/track', 'AramexController@shipmentTracking');


    // Products

    Route::resource('products', 'Products\ProductsController');
    Route::get('productsList', 'Products\ProductsController@productsList')->name('productsList');

    // Foods
    Route::get('extraPrice', 'Products\ProductsController@extraPrice')->name('extraPrice');

    Route::get('barcodes', 'Products\ProductsController@printBarcodeIndex');
    // Route::get('productsBarcodeList','Products\ProductsController@productsBarcodeList')->name('productsBarcodeList');
    Route::get('getBarCodes', 'Products\ProductsController@getBarCodes')->name('getBarCodes');
    Route::get('item_group', 'Products\ProductsController@getSecondItemgroup')->name('item_group');
    Route::get('product-details/{id}', 'Products\ProductsController@details');
    Route::get('product-variations-details/{parent_id}', 'Products\ProductsController@variationsStock');
    Route::get('products/image/delete/{image}', 'Products\ProductsController@deleteImage');
    Route::get('products/variation/delete/{variation}/{is_attribute}', 'Products\ProductsController@deleteVariation');
    Route::get('product/status/{id}', 'Products\ProductsController@Status');
    Route::get('products/{category_id}/order', 'Products\ProductsController@getProductsOrderPage');
    Route::get('product/category/data', 'Products\ProductsController@getCategoryProducts');
    Route::get('product/reorder', 'Products\ProductsController@reOrder');
    Route::get('attributes', 'Products\ProductsController@attributes')->name('attributes');
    Route::get('product/reordering', 'Products\ProductsController@getProductReodering');
    Route::get('productReorderingList', 'Products\ProductsController@productReorderingList')->name('productReorderingList');
    Route::get('product-variations', 'Products\ProductsController@addVariationView')->name('addVariationView');
    Route::get('product-attributes', 'Products\ProductsController@addAttributeView')->name('addAttributeView');
    Route::get('variationTable', 'Products\ProductsController@variationTable')->name('variationTable');
    Route::get('/getProductVariants/{itemcode}', 'Products\ProductsController@getProductVariants');
    Route::get('import/products/step1', 'Products\ProductsController@importProductsImagesView');

    Route::post('import/products/step1', 'Products\ProductsController@importProductsImagesForExcel');

    Route::get('import/products/step2/{pathno}', 'Products\ProductsController@importProductsExcelView');
    Route::post('import/products/step2/{pathNo}', 'Products\ProductsController@importProductsExcel');

    Route::get('home', 'AdminHomeController@index');
    Route::get('settings', 'SettingsController@index');
    Route::post('settings', 'SettingsController@update');
    Route::get('activities', 'AdjustmentController@activities');
    Route::get('activitiesList', 'AdjustmentController@activitiesList')->name('activitiesList');

    Route::get('/refreshdata', function () {
        return view('admin.refreshdata');
    });

    //
    // Route::get('testing','Brands\BrandsController@view');
    // Route::post('testing','Brands\BrandsController@post');

    Route::post('importStocks', 'warehouses\WarehousesController@import');
    Route::get('importStocks', 'warehouses\WarehousesController@importView');
    Route::Post('importStocksList', 'warehouses\WarehousesController@importStocksList')->name('importStocksList');
    Route::get('warehouses/default-warehouse/{id}', 'warehouses\WarehousesController@changeToDefault');

    Route::post('import-stocks', 'warehouses\WarehousesController@importStocks');
    //Report
    Route::get('reports', 'GomlaUsers\UsersController@reports');
    Route::get('export', 'GomlaUsers\UsersController@export');
    Route::post('user/importExcel', 'GomlaUsers\UsersController@importExcel');
    Route::get('user/import/details', 'GomlaUsers\UsersController@importExcelDetails');
    // Route::post('importExcel', 'MaatwebsiteController@importExcel');
    Route::get('usersnotificationlist', 'GomlaUsers\UsersController@listUserNotification')->name('userproductnotifylist');
    Route::get('viewusersrequireditems', 'GomlaUsers\UsersController@viewUserRequiredItems');

    //Users
    Route::resource('users', 'GomlaUsers\UsersController');
    Route::get('users/status/{id}', 'GomlaUsers\UsersController@status');
    Route::get('user/details/{id}', 'GomlaUsers\UsersController@userDetails');
    Route::get('userslist', 'GomlaUsers\UsersController@userslist')->name('userslist');
    Route::get('getuseremail', 'GomlaUsers\UsersController@getuseremail')->name('getuser');

    Route::resource('store-details', 'Shop\StoreCmsController');
    Route::get('shopList', 'Shop\StoreCmsController@shopList')->name('shopList');

    //Delivery-Man
    Route::resource('delivery/man', 'DeliveryManController');
    Route::get('delivery/man/status/{id}/{status}', 'DeliveryManController@Status');
    Route::get('deliverymanlist', 'DeliveryManController@deliverymanlist')->name('deliverymanlist');

    Route::resource('delivery/cars', 'DeliveryOrdersCms\DeliveryCarController');
    Route::get('carsList', 'DeliveryOrdersCms\DeliveryCarController@carsList')->name('carsList');
    Route::get('delivery/cars/delete/{id}', 'DeliveryOrdersCms\DeliveryCarController@delete');

    Route::resource('line-haul-batch', 'LineHaulBatchesController');
    Route::get('lineHaulBatchList', 'LineHaulBatchesController@lineHaulBatchList')->name('lineHaulBatchList');
    Route::get('line-haul-batch/image/delete/{image}', 'LineHaulBatchesController@deleteImage');
    Route::get('line-haul-batch/delete/{id}', 'LineHaulBatchesController@delete');

    //Time-Section
    Route::resource('time/section', 'TimeSection\TimeSectionController');
    Route::get('timelist', 'TimeSection\TimeSectionController@timelist')->name('timelist');
    Route::get('time/section/status/{id}', 'TimeSection\TimeSectionController@Status');
    // order tracking
    Route::get('/order/track', 'trackOrders@index')->name('track-orders');
    Route::post('/order_status', 'trackOrders@getStatus')->name('order_status');

    // Route::get('runsheet/add', 'DeliveryOrdersCms\DeliveryOrderController@add');

    //Delivery-Orders
    Route::resource('runsheet', 'DeliveryOrdersCms\DeliveryOrderController');
    Route::get('DelOrdersList', 'DeliveryOrdersCms\DeliveryOrderController@DelOrdersList')->name('DelOrdersList');
    Route::get('editOrderList', 'DeliveryOrdersCms\DeliveryOrderController@editOrderList')->name('editOrderList');

    Route::get('delivery_orders_list', 'DeliveryOrdersCms\DeliveryOrderController@DeliveryOrdersList')->name('delivery_orders_list');
    Route::get('runsheet/details/{id}', 'DeliveryOrdersCms\DeliveryOrderController@details');
    Route::get('runsheet/dnd/{id}', 'DeliveryOrdersCms\DeliveryOrderController@dnd');
    Route::post('runsheet/orders/delivery-order', 'DeliveryOrdersCms\DeliveryOrderController@updateStatus');
    Route::get('runsheet/debreifing/actions', 'DeliveryOrdersCms\DeliveryOrderController@runSheetActions');
    Route::get('runsheet/orders/detailsList', 'DeliveryOrdersCms\DeliveryOrderController@orderDetailsList')->name('orderDetailsList');

    Route::get('runsheet-filter/orders', 'DeliveryOrdersCms\DeliveryOrderController@orders')->name('orders');

    Route::get('bundles', 'BundlesCms\BundlesController@index');
    Route::get('bundles_list', 'BundlesCms\BundlesController@BundlesList')->name('bundles_list');
    Route::get('bundle/{id}/edit', 'BundlesCms\BundlesController@edit');
    Route::post('bundle/{id}/update', 'BundlesCms\BundlesController@update');

    // Route::get('categories','CategoryController@index');
    Route::get('categoriesList', 'Category\CategoryController@categoriesList')->name('categoriesList');
    Route::resource('categories', 'Category\CategoryController');
    Route::get('category/order', 'Category\CategoryController@getOrderPage');
    Route::get('category/reorder', 'Category\CategoryController@reOrder');
    Route::get('categories/{id}/manage', 'Category\CategoryController@manageSubCategories');
    Route::get('subcategory/reorder', 'Category\CategoryController@reOrderSubCategories');
    Route::get('categories/status/{id}', 'Category\CategoryController@Status');

    Route::get('slides', 'SliderController@index');
    Route::get('slide/add', 'SliderController@addSlide');
    Route::post('slide/add', 'SliderController@storeSlide');
    Route::get('slide/edit/{slideId}', 'SliderController@editSlide');
    Route::post('slide/edit/{slideId}', 'SliderController@updateSlide');
    Route::get('slide/delete/{id}', 'SliderController@deleteSlide');

    Route::get('collections', 'CollectionController@index');
    Route::get('collection/add', 'CollectionController@create');
    Route::get('collection/delete/{id}', 'CollectionController@delete');
    Route::get('collection/manage/{id}', 'CollectionController@manage');
    Route::get('collection/reorder', 'CollectionController@collectionReorder');
    Route::get('collection/item/reorder/{colid}', 'CollectionController@collectionItemReorder');
    Route::post('collection/store', 'CollectionController@store');
    Route::post('collection/item/add/{colId}', 'CollectionController@storeCollectionItem');
    Route::post('collection/update/{colId}', 'CollectionController@updateCollection');
    Route::get('collection/item/delete/{colId}/{itemId}', 'CollectionController@deleteCollectionItem');
    Route::get('itemsList', 'CollectionController@itemsList')->name('itList');

// Reports

    Route::get('reports/end-of-day', 'ReportsController@endOfDay');


    Route::get('stockReports', 'ReportsController@stockReports');
    Route::get('stockReportsList', 'ReportsController@stockReportsList')->name('stockReportsList');
    Route::get('salesReports', 'ReportsController@salesReports');

    Route::get('get-admin-Stock-imports', 'ReportsController@getadminStockImports')->name('get-admin-Stock-imports');
    Route::get('admin-Stock-imports', 'ReportsController@adminStockImports');

    Route::get('salesReportsList', 'ReportsController@salesReportsList')->name('salesReportsList');
    Route::get('storeOldOrdersReports', 'Sales\SalesController@storeOldOrdersReports');
    Route::get('salesReportsList/{day}', 'ReportsController@dayReportsList');
    Route::get('products-report', 'ReportsController@index');
    Route::get('products-profit', 'ReportsController@getProductsProfitView');
    Route::get('daily-report/{day}', 'ReportsController@dailyReportView');
    Route::get('productsProfitList', 'ReportsController@productsProfitList')->name('productsProfitList');
    Route::get('itemList', 'ReportsController@itemList')->name('itemList');
    Route::get('getBuyingPriceHistory', 'ReportsController@getBuyingPriceHistory')->name('getBuyingPriceHistory');
    Route::get('getSellingPriceHistory', 'ReportsController@getSellingPriceHistory')->name('getSellingPriceHistory');
    Route::get('districts-report', 'ReportsController@districtIndex');
    Route::get('districtList', 'ReportsController@districtList')->name('districtList');

    Route::get('reports/dashboard', 'ReportsController@reportsDashboard');

    Route::get('reports/invoice', function () {
        return view('admin.reports.invoice');
    });
    Route::get('reports/invoice/index', 'ReportsController@invoice');
    Route::get('reports/invoice/{id}', 'ReportsController@getInvoiceDetails');


    Route::get('couriers-report', 'ReportsController@courierIndex');
    Route::get('couriersList', 'ReportsController@couriersList')->name('couriersList');

    // Route::get('images/delete/{image}/{type}', 'ImageController@delete');

    Route::get('products/{id}/manage/price_list', 'PriceLists\PriceListsController@priceListView');
    Route::post('products/{id}/manage/price_list', 'PriceLists\PriceListsController@createOrUpdatePriceList');
    Route::resource('price-list', 'PriceLists\PriceListsController');
    Route::get('priceList', 'PriceLists\PriceListsController@priceList')->name('priceList');
    Route::get('price-list/delete/{id}', 'PriceLists\PriceListsController@delete');

    Route::get('products/{id}/manage/price_rule', 'PriceRules\PriceRulesController@priceRuleView');
    Route::post('products/{id}/manage/price_rule', 'PriceRules\PriceRulesController@createOrUpdatePriceRule');
    Route::delete('products/price_rule/{id}' , 'PriceRules\PriceRulesController@deletePriceRule');
    Route::resource('stocks', 'StockController');
    Route::get('stock-itemsList/{product}', 'StockController@itemsList')->name('itemList');
    Route::get('products/{id}/manage', 'StockController@manageProductItems');
    Route::get('stocks/delete/{item}', 'StockController@delete');
    Route::get('warehouse_qty', 'StockController@warehouses_qty')->name('warehouses_qty');
    Route::get('dest_warehouse', 'StockController@dest_warehouse')->name('dest_warehouse');
    Route::get('move_stock', 'StockController@moveStocks');
    Route::post('move_stock', 'StockController@storeMovedStocks');

    Route::get('products/{id}/standard-rate', 'ItemPrice\ItemPriceController@itemPriceView');
    Route::get('standard_rate_list/{product}', 'ItemPrice\ItemPriceController@standard_rate_list')->name('standard_rate_list');
    Route::resource('item-price', 'ItemPrice\ItemPriceController');

    Route::resource('shipping-rules', 'Shipping\ShippingRuleController');
    Route::get('shippingrulesList', 'Shipping\ShippingRuleController@shippingrulesList')->name('shippingrulesList');
    Route::get('shipping-rules/status/{id}', 'Shipping\ShippingRuleController@Status');

    Route::resource('taxs', 'TaxsController');
    Route::get('taxList', 'TaxsController@taxList')->name('taxList');
    Route::get('taxs/status/{id}', 'TaxsController@Status');

    // Roles
    Route::resource('roles', 'RoleController');
    Route::get('rolesList', 'RoleController@rolesList')->name('rolesList');
    Route::get('roles/delete/{role}', 'RoleController@delete');

    // Permissions
    Route::resource('permissions', 'PermissionController');
    Route::get('permissionsList', 'PermissionController@permissionsList')->name('permissionsList');
    Route::get('permissions/delete/{permission}', 'PermissionController@delete');

    Route::get('ordersList', 'Sales\SalesController@ordersList')->name('ordersList');
    Route::resource('sales-orders', 'Sales\SalesController');

    Route::get('bosta/shipment/create', 'Shipment\ShipmentController@createShipment');
    Route::get('bosta/shipment/track', 'Shipment\ShipmentController@shipmentTracking');

    Route::get('mylerz/shipment/create', 'Shipment\MylerzController@createShipment');
    Route::get('mylerz/shipment/track', 'Shipment\MylerzController@shipmentTracking');

    Route::get('sales-orders/{order_id}/shipment/create', 'AramexController@createShipment');
    Route::get('sales-orders/{shipment_id}/shipment/track', 'AramexController@shipmentTracking');


    Route::post('order/{id}/note/add', 'Sales\SalesController@addNote');

    Route::get('RunsheetList', 'Sales\SalesController@RunsheetList')->name('RunsheetList');
    Route::get('assignOrder', 'Sales\SalesController@assignOrder')->name('assignOrder');
    Route::post('sales_order/cancel', 'Sales\SalesController@cancelOrder');
    Route::get('sales-details/{id}', 'Sales\SalesController@details');
    Route::get('sales-order/manage', 'Sales\SalesController@returnsView');
    Route::get('SalesOrderManageList', 'Sales\SalesController@SalesOrderManageList')->name('SalesOrderManageList');
    Route::get('returnOrderProductsModal', 'Sales\SalesController@returnOrderProductsModal')->name('returnOrderProductsModal');
    Route::post('returnOrderProducts', 'Sales\SalesController@returnOrderProducts')->name('returnOrderProducts');
    Route::get('itemList', 'ReportsController@itemList')->name('itemList2');
    Route::post('changeorderstatus', 'Sales\SalesController@changeOrderStatus');
    Route::post('updateExternalReciept', 'Sales\SalesController@updateExternalReciept');
    Route::post('updateOrderStatus', 'Sales\SalesController@updateOrderStatus');

    Route::post('cart', 'Sales\SalesController@cart');
    Route::get('getUserShippingRate', 'Sales\SalesController@getUserShippingRate')->name('getUserShippingRate');
    Route::get('getUserAddress', 'Sales\SalesController@getUserAddress')->name('getUserAddress');
    Route::get('getCurrentUserAddresses', 'Sales\SalesController@getCurrentUserAddresses')->name('getCurrentUserAddresses');
    Route::get('getUserData', 'Sales\SalesController@getUserData')->name('getUserData');
    Route::get('getPromoCodeDetails', 'Sales\SalesController@getPromoCodeDetails')->name('getPromoCodeDetails');
    Route::post('create-user', 'GomlaUsers\UsersController@createUser')->name('createUser');
    Route::get('address/create', 'GomlaUsers\UsersController@createaddressview');
    Route::get('address/{id}/edit', 'GomlaUsers\UsersController@editaddressview');
    Route::patch('address/{id}', 'GomlaUsers\UsersController@updateaddress');
    Route::get('checkout', 'Sales\SalesController@checkoutviewData');
    Route::post('checkout', 'Sales\SalesController@postcheckout');
    Route::post('cart-remove', 'Sales\SalesController@removeFromCart');
    Route::get('getItemRate', 'Sales\SalesController@getItemRate')->name('getItemRate');

    Route::Resource('cmsusers', 'AdminsController');
    Route::get('cmsusers/delete/{id}', 'AdminsController@delete');
    Route::get('/cmsusers/changepassword/{id}', 'AdminsController@changePassView');
    Route::patch('/cmsusers/changepassword/{id}', 'AdminsController@changePassword');

    Route::get('adminsList', 'AdminsController@adminsList')->name('adminsList');

    Route::resource('sales-invoices', 'Sales\SalesInvoiceController');
    Route::get('sales-invoices/delete/{id}', 'Sales\SalesInvoiceController@delete');
    Route::get('sales-invoices/{id}/details', 'Sales\SalesInvoiceController@salesInvoiceDetails');

    Route::get('salesInvoicesList', 'Sales\SalesInvoiceController@salesInvoicesList')->name('salesInvoicesList');

    Route::get('salesInvoicesAccountingList', 'Sales\SalesInvoiceController@salesInvoicesAccountingList')->name('salesInvoicesAccountingList');

    Route::get('accounting/sales-invoices', 'Sales\SalesInvoiceController@accountingIndex');
    Route::resource('purchase-orders', 'Purchase\PurchaseOrdersController');
    Route::get('purchase-orders/delete/{id}', 'Purchase\PurchaseOrdersController@delete');
    Route::get('purchase-orders/{id}/details', 'Purchase\PurchaseOrdersController@purchaseOrderDetails');

    Route::get('purchaseOrdersList', 'Purchase\PurchaseOrdersController@purchaseOrdersList')->name('purchaseOrdersList');
    Route::post('sendEmail', 'Purchase\PurchaseOrdersController@sendEmail');
    Route::post('postPurchaseOrder', 'Purchase\PurchaseOrdersController@postPurchaseOrder');

    Route::post('purchase-invoices/sendEmail', 'Purchase\PurchaseInvoiceController@sendEmail');
    Route::post('sendEmailModal', 'Purchase\PurchaseInvoiceController@sendEmailModal');
    Route::post('postPurchaseInvoice', 'Purchase\PurchaseInvoiceController@postPurchaseInvoice');
    Route::get('purchase-invoices/{id}/details', 'Purchase\PurchaseInvoiceController@purchaseInvoiceDetails');

    Route::resource('purchase-receipts', 'Purchase\PurchaseReceiptController');
    Route::get('purchase-receipts/download-file/{id}', 'Purchase\PurchaseReceiptController@downloadFile');
    Route::get('purchase-receipts/{id}/details', 'Purchase\PurchaseReceiptController@purchaseReceiptDetails');

    Route::get('purchase-orders/download-file/{id}', 'Purchase\PurchaseOrdersController@downloadFile');

    Route::get('purchase-orders/{id}/purchase-receipts', 'Purchase\PurchaseReceiptController@manageReceipts');
    Route::get('purchaseReceiptsList/{purchase_order_id}', 'Purchase\PurchaseReceiptController@purchaseReceiptsList')->name('purchaseReceiptsList');
    Route::get('pendingPurchaseReceiptsList', 'Purchase\PurchaseReceiptController@pendingPurchaseReceiptsList')->name('pendingPurchaseReceiptsList');

    Route::get('purchase-receipts/delete/{id}', 'Purchase\PurchaseReceiptController@delete');
    Route::get('purchase-receipts/status/{id}/{status}', 'Purchase\PurchaseReceiptController@changeStatus');

    Route::get('purchase-receipts/{id}/invoice', 'Purchase\PurchaseInvoiceController@createInvoice');

    Route::post('purchase-invoices', 'Purchase\PurchaseInvoiceController@store');
    Route::get('purchase-invoices', 'Purchase\PurchaseInvoiceController@index');
    Route::get('purchaseInvoicesList', 'Purchase\PurchaseInvoiceController@purchaseInvoicesList')->name('purchaseInvoicesList');

    Route::get('purchase-invoices/download-file/{id}', 'Purchase\PurchaseInvoiceController@downloadFile');

    Route::get('purchase-invoices/status/{id}/{status}', 'Purchase\PurchaseInvoiceController@changeStatus');
    Route::get('purchase-invoices/delete/{id}', 'Purchase\PurchaseInvoiceController@delete');

    // Route::get('purchase-invoice/status/{purchase-invoice}/{purchase-receipts-id}/{status}','Purchase\PurchaseReceiptController@changeStatus');

    Route::get('getItemDetails', 'Purchase\PurchaseOrdersController@getItemDetails')->name('getItemDetails');
    Route::get('getVariant', 'Purchase\PurchaseOrdersController@getVariant')->name('getVariant');
    Route::get('shippingruleRate', 'Purchase\PurchaseOrdersController@shippingruleRate')->name('shippingruleRate');
    Route::get('taxsValue', 'Purchase\PurchaseOrdersController@taxsValue')->name('taxsValue');

    Route::resource('product_bundles', 'ProductBundleController');
    Route::get('itemsList/{product}', 'ProductBundleController@itemsList')->name('itemsList');
    Route::get('getItems', 'ProductBundleController@getItems')->name('getItems');
    // Route::get('products/{id}/manage_bundle', 'ProductBundleController@manageProductBundles');
    Route::get('product_bundles/delete/{item}', 'ProductBundleController@delete');
    Route::get('product_bundle/manage_bundle', 'ProductBundleController@createProductBundleView');
    Route::patch('product_bundle/manage_bundle/{bundle_id}', 'ProductBundleController@updateproductbundle');

    Route::get('brandsList', 'Brands\BrandsController@brandsList')->name('brandsList');
    Route::resource('brands', 'Brands\BrandsController');
    Route::get('brands/delete/{id}', 'Brands\BrandsController@delete');

    Route::get('slabsList', 'Slabs\SlabsController@slabsList')->name('slabsList');
    Route::resource('slabs', 'Slabs\SlabsController');
    Route::get('slabs/delete/{id}', 'Slabs\SlabsController@delete');

    Route::get('uomsList', 'Products\UOMController@uomsList')->name('uomsList');
    Route::resource('uom', 'Products\UOMController');
    Route::get('uom/delete/{id}', 'Products\UOMController@delete');

    Route::get('seasonsList', 'Products\SeasonsController@seasonsList')->name('seasonsList');
    Route::resource('seasons', 'Products\SeasonsController');
    Route::get('seasons/delete/{id}', 'Products\SeasonsController@delete');

    Route::get('complainsList', 'Complains\CustomerComplainsController@complainsList')->name('complainsList');
    Route::resource('users-complains', 'Complains\CustomerComplainsController');
    // Route::get('brands/delete/{id}', 'Brands\BrandsController@delete');
    Route::get('complain/details/{id}', 'Complains\CustomerComplainsController@complainDetails');
    Route::patch('complain/details/{id}', 'Complains\CustomerComplainsController@complainAnswer');

    Route::get('supplierTypesList', 'supplierTypes\SuppliersTypesController@supplierTypesList')->name('supplierTypesList');
    Route::resource('supplier-types', 'supplierTypes\SuppliersTypesController');
    Route::get('supplier-types/delete/{id}', 'supplierTypes\SuppliersTypesController@delete');

    Route::get('suppliersList', 'suppliers\SuppliersController@suppliersList')->name('suppliersList');
    Route::resource('suppliers', 'suppliers\SuppliersController');
    Route::get('suppliers/delete/{id}', 'suppliers\SuppliersController@delete');

    Route::resource('variations', 'Variants\VariationsController');
    Route::get('variations/variant/delete/{value}', 'Variants\VariationsController@removeVariantValue');
    Route::get('variantsList', 'Variants\VariationsController@variantsList')->name('variantsList');
    Route::get('variations/status/{id}', 'Variants\VariationsController@Status');
    Route::get('variations/delete/{id}', 'Variants\VariationsController@delete');

    Route::resource('warehouses', 'warehouses\WarehousesController');
    Route::get('warehousesList', 'warehouses\WarehousesController@warehousesList')->name('warehousesList');
    Route::get('warehouses/status/{id}', 'warehouses\WarehousesController@Status');
    Route::get('warehouses/{id}/details', 'warehouses\WarehousesController@details');
    Route::get('detailsList', 'warehouses\WarehousesController@detailsList')->name('detailsList');

    Route::resource('companies', 'Company\CompanyController');
    Route::get('companiesList', 'Company\CompanyController@companiesList')->name('companiesList');
    Route::get('companies/status/{id}', 'Company\CompanyController@Status');

    Route::resource('payment-methods', 'Payment\PaymentController');
    Route::get('paymentList', 'Payment\PaymentController@paymentList')->name('paymentList');
    Route::get('payment-methods/status/{id}', 'Payment\PaymentController@Status');
    Route::get('payment-methods/delete/{id}', 'Payment\PaymentController@delete');

    // Route::get('category/order','CategoryController@getOrderPage');
    // Route::get('category/reorder', 'CategoryController@reOrder');
    Route::get('payments/create', 'Payments\PaymentsController@create');
    Route::post('payments/create', 'Payments\PaymentsController@store');
    Route::get('payments', 'Payments\PaymentsController@index');
    Route::get('paymentsList', 'Payments\PaymentsController@paymentsList')->name('payments_List');
    Route::get('payments/status/{id}/{status}', 'Payments\PaymentsController@changeStatus');
    Route::get('payments/delete/{id}', 'Payments\PaymentsController@delete');

    Route::get('promocodes', 'Promocode\PromocodeController@index');
    Route::get('promocodes_list', 'Promocode\PromocodeController@PromocodesList')->name('promocodes_list');
    Route::get('promocode/add', 'Promocode\PromocodeController@createPromoCode');
    Route::post('promocode/add', 'Promocode\PromocodeController@storePromoCode');
    Route::get('promocode/{id}/edit', 'Promocode\PromocodeController@edit');
    Route::post('promocode/{id}/update', 'Promocode\PromocodeController@update');

    //Branches
    Route::resource('branches', 'Branches\BranchesController');
    Route::get('branches_list', 'Branches\BranchesController@branchesList')->name('branches_list'); //Ajax
    Route::get('branches/{branch}/status', 'Branches\BranchesController@status');

    //Districts
    Route::resource('districts', 'Districts\DistrictsController');
    Route::get('districts_list', 'Districts\DistrictsController@districtsList')->name('districts_list'); //Ajax
    Route::get('districts/{district}/status', 'Districts\DistrictsController@status');

    Route::get('district/reorder', 'Districts\DistrictsController@districtsReorder');

// ----------------------------------
    Route::post('runsheet/orders/cancel', 'DeliveryOrdersCms\DeliveryOrderController@caneclOrders');
    Route::post('runsheet/orders/status', 'DeliveryOrdersCms\DeliveryOrderController@statusOrder');
// ----------------------------------

    Route::post('runsheet/order/status', 'DeliveryOrdersCms\DeliveryOrderController@changeStatus');
    Route::get('runsheet/delete/{id}', 'DeliveryOrdersCms\DeliveryOrderController@destroy');

    // Payment Entry Of Sales Order
    // Route::get('delivery/orders/{order_id}/payment','DeliveryOrdersCms\DeliveryOrderController@testgetPaymentEntry');
    Route::get('delivery-order-payment', 'DeliveryOrdersCms\DeliveryOrderController@getPaymentEntry')->name('payment');
    Route::post('runsheet/orders/payment/{order_id}', 'DeliveryOrdersCms\DeliveryOrderController@postPaymentEntry');

    Route::get('delivery-order-sales-invoice', 'DeliveryOrdersCms\DeliveryOrderController@getSalesInvoicePage')->name('sales_invoice');
    Route::post('runsheet/orders/invoice/{order_id}', 'DeliveryOrdersCms\DeliveryOrderController@postSalesInvoice');
    Route::post('runsheet/orders/email-invoice', 'DeliveryOrdersCms\DeliveryOrderController@emailSalesInvoice');

    /*     * **********************Analytic************************ */
    Route::get('/analytics', 'AnalyticController@analytics');

    Route::get('/payments/excel', [
        'as' => 'admin.analytic.list',
        'uses' => 'AnalyticController@excel',
    ]);
    Route::get('/payments/excelbunddle', [
        'as' => 'admin.analytic.list',
        'uses' => 'AnalyticController@excelbunddle',
    ]);

    Route::get('/refresh', 'AnalyticController@refresh');
    Route::get('/backup/items', 'Category\CategoryController@getBackupItems');
    Route::get('/backup/itemgroups', 'Category\CategoryController@getBackupItemgroups');
    Route::get('/backup/slides-shows', 'Category\CategoryController@getBackupSlidesShowItems');

    // Push Notification
    Route::get('/notifications', 'pushApi\pushApiController@listPushNotifications');
    Route::get('/notifications/add', 'pushApi\pushApiController@showPushNotificationForm');
    Route::post('/notifications/add', 'pushApi\pushApiController@sendPushNotification');

    // Push Notification to specific user
    Route::get('/notifications/users/add', 'pushApi\pushApiController@showUsersPushNotificationForm');
    Route::post('/notifications/users/add', 'pushApi\pushApiController@sendUsersPushNotification');

    // Push Notification to specific version
    Route::get('/notifications/app/add', 'pushApi\pushApiController@showAppPushNotificationForm');
    Route::post('/notifications/app/add', 'pushApi\pushApiController@sendAppPushNotification');

    Route::get('/notifications/app/version', 'pushApi\pushApiController@getAppVersion');

    Route::get('reorder', 'AutoReorderController@products');

    Route::get('customer-orders', 'AnalyticController@customerOrders');
    //Supplier Types
    // Route::resource('supplier/types', 'SupplierTypes\SupplierTypesController');
    // Route::get('supplier_types_list', 'SupplierTypes\SupplierTypesController@supplierTypesList')->name('supplier_types_list'); //Ajax
    //  Route::get('supplier/types/{suppliertype}/status', 'SuppliersTypes\SupplierTypesController@status');

    // Route::get('supplier/types/refresh/data', 'SupplierTypes\SupplierTypesController@getAllSupplierTypes');

    //Suppliers
    // Route::resource('suppliers', 'Suppliers\SuppliersController');
    // Route::get('suppliers_list', 'Suppliers\SuppliersController@suppliersList')->name('suppliers_list'); //Ajax
    //Route::get('supplier/types/{suppliertype}/status', 'SuppliersTypes\SupplierTypesController@status');

    Route::get('suppliers/refresh/data', 'Suppliers\SuppliersController@getAllSuppliers');

    Route::post('images', 'ImageController@store');
    Route::get('images/order', 'ImageController@reOrder');
    Route::get('images/delete/{image}/{type}', 'ImageController@delete');

    Route::get('Salaries', 'Salaries_drawingController@index');

    // Ajax to load data in DataTable
    Route::get('Salaries/all', 'Salaries_drawingController@AllSalaries')->name('allDrawings');
    // Add New Salary View
    Route::get('Salaries/add', 'Salaries_drawingController@addView')->name('addDrawingView');
    // Add New Salary Request {post request}
    Route::post('Salaries/Add', 'Salaries_drawingController@CreateDrawing')->name('AddNewDrawing');
    // Delete Salary Post Request
    Route::post('Salaries/Delete', 'Salaries_drawingController@delete')->name('deleteDrawing');
    // Edit Salary  View
    Route::get('Salaries/{id}/edit', 'Salaries_drawingController@EditView')->name('EditSalary-Drawing');
    // Edit Salary  Post Request
    Route::post('Salaries/edit', 'Salaries_drawingController@EditDrawingRequest')->name('EditSalary-DrawingRequest');

    ## Exporting
    Route::get('export/csv/stock', 'Exports\ExportsCSVController@stock')->name('export.csv.stock');
    Route::get('export/pdf/stock', 'Exports\ExportsPDFController@stock')->name('export.pdf.stock');

    Route::get('export/csv/sales', 'Exports\ExportsCSVController@sales')->name('export.csv.sales');
    Route::get('export/pdf/sales', 'Exports\ExportsPDFController@sales')->name('export.pdf.sales');
    Route::post('export/csv/sales', 'Exports\ExportsCSVController@filterSales')->name('export.csv.sales.filter');

    Route::get('export/csv/invoice', 'Exports\ExportsCSVController@invoice')->name('export.csv.invoice');
    Route::get('export/pdf/invoice', 'Exports\ExportsPDFController@invoice')->name('export.pdf.invoice');


});

Route::get('/login', 'UsersController@login');
Route::post('/login', 'UsersController@dologin');

Route::get('/register', 'UsersController@register');
Route::post('/register', 'UsersController@doregister');

Route::get('login/facebook', 'Auth\RegisterController@redirectToProvider');
Route::get('login/facebook/callback', 'Auth\RegisterController@handleProviderCallback');

// Route::get('facebook', function () {
//     return view('facebook');
// });
// Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook');
// Route::get('auth/facebook/callback', 'Auth\FacebookController@handleFacebookCallback');

Route::get('/erorr', function () {
    return view('erorr');
});
