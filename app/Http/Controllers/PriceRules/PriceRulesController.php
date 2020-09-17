<?php

namespace App\Http\Controllers\PriceRules;

use App\Categories;
use App\Http\Controllers\Controller;
use App\ItemPrice;
use App\PriceList;
use App\PriceRules;
use DB;
use App\Products;
use Carbon\Carbon;
use File;
use Image;

use Illuminate\Http\Request;

class PriceRulesController extends Controller
{
    public function priceRuleView($product_id)
    {
        $product = Products::find($product_id);
        $price_lists_ids = ItemPrice::where('product_id',$product_id)->distinct()->pluck('price_list_id')->toArray();
        $price_lists = PriceList::whereIn('id', $price_lists_ids)->get();
        $rate =  $product->ItemPrice->where('price_list_id' , '=' , '1')->first();
        if($rate) {
            $rate = $rate->rate;
        }
        return view('admin.price_rules.add', compact('product', 'product_id', 'price_lists' , 'rate'));
    }

    public function createOrUpdatePriceRule(Request $request, $product_id)
    {
        $this->validate($request, [
            'discount_type' => 'required',
            'discount_rate' => 'required',
            'price_rule_name' => 'required',
        ]);

        $discount_type = request('discount_type');
        $discount_rate = request('discount_rate');
        $price_rule_name = request('price_rule_name');
        $valid_from = date('Y-m-d H:i:s', strtotime($request->valid_from));
        $valid_to = date('Y-m-d H:i:s', strtotime($request->valid_to));
        PriceRules::updateOrCreate(
            ['product_id' => $product_id],
            [
                'price_rule_name' => $price_rule_name,
                'valid_from' => $valid_from,
                'valid_to' => $valid_to,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
            ]
        );
        return redirect('admin/products')->with('message', 'Price Rule Updated Successfully');
    }

    public function deletePriceRule($id) {
       $priceRules = PriceRules::find($id);
       if(!$priceRules) {
           return redirect()->back()->withErrors([' price rule not exists anymore']);
       }
       $priceRules->delete();
       return redirect()->back()->with('message' , 'price rule deleted successfully');
    }

}
