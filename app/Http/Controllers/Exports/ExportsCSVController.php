<?php

namespace App\Http\Controllers\Exports;


use App\Http\Controllers\Controller;
use App\OrderItems;
use App\Orders;
use App\Products;
use Carbon\Carbon;
use Excel;
use DB;
use Illuminate\Http\Request;

class ExportsCSVController extends Controller
{
    public function stock()
    {
        $products = Products::Variant()->with(['variantStock', 'price', 'variations'])->distinct()->get();
        $productArray = [];
        $i = 0;
        foreach ($products as $product) {
            if (isset($product->variantStock) && isset($product->variations[0]) && isset($product->variations[1])) {
                $itemCategoryName = (($product->itemGroup) ? $product->itemGroup->name_en : 'No Category');
                $color = ($product->variations[0]->variationMeta)? $product->variations[0]->variationMeta->value :'';
                $size =  ($product->variations[1]->variationMeta) ? $product->variations[1]->variationMeta->value: '';

                // we had to concat # to itemcode because of issue in excel with large numbers so we had to make it string
                $itemCode = '#'.$product->item_code;
                $stockQty = 0;
                if (isset($product->variantStockReport[0]) && $product->variantStockReport[0]->projected_qty == 0) {
                    $stockQty = '0';
                } elseif (isset($product->variantStockReport[0]) && $product->variantStockReport[0]->projected_qty > 0) {
                    $stockQty = $product->variantStockReport[0]->projected_qty;
                } else {
                    $stockQty = 'N/A';
                }

                $productArray[] = [
                    'ID' => $product->id,
                    'Name' => $product->name_en,
                    'Code' => $itemCode,
                    'Standard Rate' => isset($product->price->rate) ? $product->price->rate : 'N/A',
                    'Warehouse' => isset($product->variantStockReport[0]) ? $product->variantStockReport[0]->name : 'N/A',
                    'Stock Qty' => $stockQty,
                    'Color' => $color,
                    'Size' => $size,
                    'Category Name' => $itemCategoryName
                ];
//                foreach ($product->variantStock as $stock) {
//                    $color = $product->variations[0]->variationMeta->value;
//                    $size = $product->variations[1]->variationMeta->value;
//
//                    $productArray[] = ['id' => $product->id, 'name_en' => $product->name_en, 'item_code' => $product->item_code,
//                        'standard_rate' => isset($product->price->rate) ? $product->price->rate : 'N/A',
//                        'warehouse' => $stock->name, 'stock_qty' => $stock->pivot->projected_qty,
//                        'color' => $color, 'size' => $size, 'cat_name' => $itemCategoryName
//                    ];
//                }
            }
            $i++;
        }

        Excel::create('Stock-Reports', function ($excel) use ($productArray){
            $excel->sheet('sheet', function ($sheet) use ($productArray){
                $sheet->fromArray($productArray);
            });
        })->export('csv');

        return;
    }

    public function sales()
    {
        $salesReportsArray = OrderItems::join('products', 'order_items.item_id', 'products.id')
            ->join('categories', 'products.item_group', 'categories.id')
            ->select(DB::raw('SUM(qty) as qty '), DB::raw('DATE(order_items.created_at) as date'), 'item_id',
                'products.name_en as product_name', 'products.cost as cost', 'order_items.rate as price'
                , 'categories.name_en as category_name', 'products.item_code as barcode')
            ->groupBy('date', 'item_id', 'products.name_en', 'categories.name_en', 'products.item_code'
                , 'products.cost', 'order_items.rate')
            ->get();

        Excel::create('Sales-Reports', function ($excel) use ($salesReportsArray){
            $excel->sheet('sheet', function ($sheet) use ($salesReportsArray){
                $sheet->fromArray($salesReportsArray);
            });
        })->export('csv');

        return;
    }

    public function filterSales(Request $request)
    {
        $date = $request->get('date');
         // total amount

        $salesReport = OrderItems::join('orders', 'order_items.order_id', 'orders.id')
            ->join('address', 'orders.address_id', 'address.id')
            ->join('districts', 'address.district_id', 'districts.id')
            ->leftJoin('shipment', 'orders.id', 'shipment.order_id')
            ->join('users', 'orders.user_id', 'users.id')
            ->select(
                DB::raw('SUM(qty) as qty'), 
                'orders.id as order_number',
                'users.id as customer_number',
                'users.name as customer_name',
                'address.title as address_title',
                'address.street as address_street',
                'districts.district_en as district', 
                'shipment.shipment_company as shipment_company',
                'order_items.created_at as date', 
                'item_id as item_id',
                'order_items.rate as price')
            ->groupBy(
                'orders.id',
                'users.id',
                'users.name',
                'address.title',
                'address.street',
                'districts.district_en', 
                'shipment.shipment_company',
                'order_items.created_at', 
                'item_id',
                'order_items.rate');
        if($date) {
            $salesReport->whereDate('orders.date', Carbon::parse($date)->toDateString());
        }
        $salesReportsArray = $salesReport->get();

        Excel::create('Sales-Reports', function ($excel) use ($salesReportsArray){
            $excel->sheet('sheet', function ($sheet) use ($salesReportsArray){
                $sheet->fromArray($salesReportsArray);
            });
        })->export('csv');

        return;
    }

    public function invoice()
    {
        $data = orders::with('user')->get();
        $values = [];
        foreach ($data as $one) {
            if($one->user)
            $values[] = ['Order No.' => $one->id, 'User Name' => $one->user->name];
            // dd($one->user);
        }

        Excel::create('Invoice-Reports', function ($excel) use ($values){
            $excel->sheet('sheet', function ($sheet) use ($values){
                $sheet->fromArray($values);
            });
        })->export('csv');

        return;
    }

}
