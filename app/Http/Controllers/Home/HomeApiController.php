<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Category\CategoryApiController;
use App\Http\Controllers\CollectionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SliderController;

class HomeApiController extends Controller
{
    //
    public function index()
    {
        $categories = new CategoryApiController();
        $slider = new SliderController();
        $collection = new CollectionController();
        $categories = $categories->getCategoriesHaveSub(request());
        $collection = $collection->getApiCollections();
        $slider = $slider->getSlider();
        return response()->json([
            'collection' => $collection,
            'categories' => $categories->original,
            'slider' => $slider
        ]);
    }
}
