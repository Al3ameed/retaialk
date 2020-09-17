<?php

namespace App\Http\Transformers;

use App\Products;
use Carbon\Carbon;

class ProductsTransformer extends Transformer
{
    public function transform($product, $filter = 1)
    {
        $variants = $variant_products = [];
        if (isset($product['variants_products']) && count($product['variants_products']) > 0) {
            list($variants, $variant_products) = $this->getVariantsProducts($product['variants_products']);
        }

        if ((count($variants) > 0) && count($variant_products) > 0) {
            $chartImages = null;
            if (isset($product['chart_image_filename']) && $product['chart_image_filename'] !== null) {
                $chartImages = getChartImage($product['chart_image_filename']);
            }
            return [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'description' => $product['description'],
                'favourite' => isset($product['favourite']['product_id']) ? true : false,
                'images' => $this->getImages($product['images']),
                'chart_images' => $chartImages,
                'variants' => $variants,
                'variants_products' => $variant_products,
            ];
        }
//        the else is used if you want return product without variance
// else {
//            $discounted_rate = 0;
//            $discount_tag = '';
//            $productObjectForDiscountCalc = Products::findOrFail($product['id']);
//            if ($productObjectForDiscountCalc->priceRuleRelation !== null) {
//                if ($productObjectForDiscountCalc->priceRuleRelation->discount_type == 'percentage') {
//                    $discount_tag = $productObjectForDiscountCalc->priceRuleRelation->discount_rate . '%';
//                    $discounted_rate =  $productObjectForDiscountCalc->standard_rate - ($productObjectForDiscountCalc->standard_rate * ($productObjectForDiscountCalc->priceRuleRelation->discount_rate / 100));
//                } else {
//                    $discount_tag = $productObjectForDiscountCalc->priceRuleRelation->discount_rate . 'EGP';
//                    $discounted_rate =  $productObjectForDiscountCalc->standard_rate -  $productObjectForDiscountCalc->priceRuleRelation->discount_rate;
//                }
//            }
//
//            return  [
//                'id' => $product['id'],
//                'name' => $product['name'],
//                'slug' => $product['slug'],
//                'description' => $product['description'],
//                'favourite' => isset($productObjectForDiscountCalc->favourite->product_id) ? true : false,
//                'images' => $this->getImages($product['images']),
//                'standard_rate' => itemSellingPrice($product['id']),
//                'has_variants' => $productObjectForDiscountCalc['has_variants'],
//                'stock_qty' => getProductStocks($product['id']),
//                'variants' => $variants,
//                'variants_products' => $variant_products,
//                'discount' => $productObjectForDiscountCalc->priceRuleRelation ? [
//                    // 'item_price_id' => $pro->priceRuleRelation->itemPrice->id,
//                    'discount_tag' => $discount_tag,
//                    'discounted_rate' => $discounted_rate,
//                    'discount_rule' => $productObjectForDiscountCalc->priceRuleRelation->price_rule_name
//                ] : null,
//            ];
//        }
    }

    private function getVariantsProducts($variantProducts) {

        $variants = $variants_keys = $variantsProductsArray = [];
        foreach ($variantProducts as $variantProduct) {

            $stock = isset($variantProduct['stock'][0]['projected_qty']) &&
            $variantProduct['stock'][0]['projected_qty'] > 0 ? $variantProduct['stock'][0]['projected_qty']
                : 0;
            // if the stock is empty , skip the loop

             if (!$stock && $stock == 0) {
                 if (strpos(request()->url(), 'filter') !== false) {
                     $variants = $variant_products = [];
                 }
                 continue;
             }
             // get variant options
            list($variation_options, $variation_codes) = $this->getVariationOptions($variantProduct['variations']);
            list($variants, $variants_keys) = Products::getVariants($variantProduct['variations'], $variants, $variants_keys);
            $price = $this->getPrice($variantProduct);
            // get discount of product
            $discount = $this->getDiscountProduct($variantProduct['price_rule_relation'] , $price);
           // $parent_images = getImagesVarientProduct($variantProduct['parent_variant_id'],$variantProduct['id']);
            $varient_images =  $this->getImages($variantProduct['images']);
           // $images = array_merge($parent_images,$varient_images);
            $variantsProductsArray[] = [
                'id' => $variantProduct['id'],
                'parent_id' => $variantProduct['parent_variant_id'],
                'parent_slug' => $variantProduct['slug'],
                'name' => $variantProduct['name'],
                'description' => $variantProduct['description'],
                'stock_qty' => $stock,
                'season' => $variantProduct['season_id'],
                'price' => $price,
                'images' => $varient_images,
                'variant_option' => $variation_options,
                'variant_code' => $variation_codes,
                'discount' => $discount
            ];
        }
        return array($variants, $variantsProductsArray);
    }
    // get discount price
    private function getDiscountProduct($price_rule_relation , $standard_rate) {
        $discount = null;
        if ($price_rule_relation !== null) {
            if ($price_rule_relation['discount_type'] == 'percentage') {
                $discount_tag = $price_rule_relation['discount_rate'] . '%';
                $discounted_rate = $standard_rate - ($standard_rate * ($price_rule_relation['discount_rate'] / 100));
            } else {
                $discount_tag = $price_rule_relation['discount_rate'] . 'EGP';
                $discounted_rate =  $standard_rate -  $price_rule_relation['discount_rate'];
            }
            $discount = [
                'item_price_id' => $price_rule_relation['item_price']['id'],
                'discount_tag' => $discount_tag,
                'discounted_rate' => $discounted_rate,
                'discount_rule' => $price_rule_relation['price_rule_name']
            ];
        }
        return $discount;
    }

    public function getVariationOptions($variations)
    {
        $variation_codes = $variation_options = $variants_value_id = $variants_key_id = [];
        $x = 0;
        if (isset($variations)) {
            foreach ($variations as $variation) {
                if (is_null($variation['variation_meta'])) {
                    continue;
                }
                $key_id = $variation['variation_data']['id'];
                $value_id = $variation['variation_meta']['id'];
                $key = $variation['variation_data']['key'];
                $value = $variation['variation_meta']['value'];
                $variation_options[$x]['key_id'] = $key_id;
                $variation_options[$x]['value_id'] = $value_id;
                $variation_options[$x]['value'] = $value;
                $variation_options[$x]['key'] = $key;
                $variation_codes[] = $value;
                $x++;
            }
        }
        return array($variation_options, $variation_codes);
    }
    // Getting Product Images url Array
    public function getImages($images)
    {
        $urls = [];
        foreach ($images as $image) {
            if (isset($image['image'])) {
                $image_url = url('/public/imgs/products/' . $image['image']);
                $urls[] = $image_url;
            } else {
                $urls[] = $image;
            }

        }
        return $urls;
    }


    // Getting Product Price url Array
    public function getPrice($product)
    {
        return isset($product['price']['rate']) ? $product['price']['rate'] : null;
    }
}
