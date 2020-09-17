<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    @include('layouts.admin.scriptname_desc')
    <script src="http://malsup.github.com/jquery.form.js">
    </script>
    <link href="{{url('public/multi-images/dist/styles.imageuploader.css')}}" rel="stylesheet">
    <script src="{{url('public/multi-images/dist/jquery.imageuploader.js')}}"></script>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <!-- App Favicon -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"/>

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    {{-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.css"/> -->
    <link href="{{url('public/lou/css/multi-select.css')}}" media="screen" rel="stylesheet" type="text/css">

    <style type="text/css">

        body {
            font-family: 'Segoe UI';
            font-size: 12pt;
        }

        header h1 {
            font-size: 12pt;
            color: #fff;
            background-color: #1BA1E2;
            padding: 20px;

        }

        article {
            width: 80%;
            margin: auto;
            margin-top: 10px;
        }

        /* The switch - the box around the slider */

    </style>
    <!--[if lt IE 9]>
    <script src="{{url('public/clock/assets/js/html5shiv.js')}}"></script>
    <script src="{{url('public/clock/assets/js/respond.min.js')}}"></script>
    <![endif]-->


</head>

<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
@include('layouts.admin.topbar')
<!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
@include('layouts.admin.sidemenu')
<!-- Left Sidebar End -->

    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
                @component('layouts.admin.breadcrumb')
                    @slot('title')
                        Products
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Products
                    @endslot
                    You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="row">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>


                <div class="modal fade bs-example-modal-sm" id="variant_modal" tabindex="-1" data-backdrop="static"
                     data-keyboard="false" role="dialog"
                     aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                Variations
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @forelse($variation_data as $data)
                                <div class="modal-body">
                                    <label for="">{{$data->variation}}</label>
                                    <select  name="variation" class="form-control selected_variation">
                                        <option value="">Choose Values</option>
                                        @foreach($data->variation_options as $dat)
                                            <option value="{{$dat->id}}">{{$dat->variation_value_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @empty
                            @endforelse
                            <div class="modal-footer">
                                <a class="btn btn-sm btn-primary" id="variant_added" title="Add Variation"><i
                                            class="glyphicon glyphicon-trash"></i> Add Variation</a>
                            </div>
                        </div>

                    </div>
                </div>


                {!! Form::open(['url' => '/admin/products', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">

                    <div style="margin-left: 5px" class="card-text">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Name(English): <span
                                                style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' required name="name_en" value="{{ old('name_en') }}"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12" style="float: right; text-align: right">
                                    <label style="margin-bottom: 0;" class="form-group" for="from"><span
                                                style="color:red;">*</span>:الاسم
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px;">
                                    <div class='input-group date' id='' style="display: inline;">
                                        <input type='text' required style="direction: rtl;" name="name"
                                               class="form-control" value="{{ old('name') }}">
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Description
                                        (English): <span style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <textarea class="form-control" id="" name="description_en"
                                                  rows="3">{{ old('description_en') }}</textarea></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12" style="float: right; text-align: right">
                                    <label style="margin-bottom: 0;" class="form-group" for="from"><span
                                                style="color:red;">*</span>:الوصف
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                    <div class='input-group date' id='' style="display: inline;  text-align: right">
                                        <textarea class="form-control" dir="rtl" id="" name="description"
                                                  rows="3">{{ old('description') }}</textarea></div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Main Category:<span
                                                style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <div class='input-group date' id='' style="display: inline;">
                                        <select required name="item_group" id="item_group" class="form-control select2">
                                            <option disabled selected>Select Main Category</option>
                                            @foreach($parent_category as $key=> $category)
                                                <optgroup label="{{$key}}">
                                                    @foreach($category as $cat)
                                                        <option value="{{$cat->id}}"
                                                                id="cat{{$cat->id}}">{{$cat->name_en}}</option>

                                                    @endforeach
                                                </optgroup>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="col-sm-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">2nd Category
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <div class='input-group date' id='' style="display: inline;">
                                        <select name="second_item_group" id="second_item_group"
                                                class="form-control select2">
                                            <!-- <option></option> -->
                                            <option  selected>Select Main Category</option>
                                            @foreach($parent_category as $key=> $category)
                                                <optgroup label="{{$key}}">
                                                    @foreach($category as $cat)
                                                        <option value="{{$cat->id}}"
                                                                id="cat{{$cat->id}}">{{$cat->name_en}}</option>

                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group">Price:<span
                                                style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group' style="display: inline;" id=''>
                                        <input type="text" required="" class="form-control" value="{{ old('standard_rate') }}" id="standard_rate" name="standard_rate">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12" style="">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Cost:<span
                                                style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px;">
                                    <div class='input-group date' id='' style="display: inline;  text-align: right">
                                        <input type="text" id="cost" required="" class="form-control" value="{{ old('cost') }}" name="cost">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(checkProductConfig('variations'))
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12" style="">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Season:<span
                                                style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px;">
                                    <div class='input-group date' id='' style="display: inline;  text-align: right">
                                        <select name="season_id" class="form-control">
                                            <option value="" selected="">Choose Season</option>
                                            @foreach($seasons as $season)
                                            <option value="{{$season->id}}">{{$season->name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            @if(checkProductConfig('uom_and_weight'))
                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group">UOM:<span
                                                style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group' style="display: inline;" id=''>
                                        <select name="uom" id="uom" class="form-control" required="required">
                                            @foreach($query as $qu)
                                                <option value="{{$qu->id}}">{{$qu->type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="col-lg-12" style="">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Weight:<span
                                                style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px;">
                                    <div class='input-group date' id='' style="display: inline;  text-align: right">
                                        <input class="form-control" id="weight" name="weight" placeholder=""
                                               required="required" type="text" value="{{ old('weight') }}"/>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(checkProductConfig('item_code'))
                                <div class="col-lg-3" style="display: none;">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">SKU:<span
                                                    style="color:red;">*</span>
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input class="form-control" id="item_code" name="item_code"
                                                   placeholder=""   type="text" value="{{ old('item_code') }}"/>
                                        </div>
                                    </div>
                                </div>
                            @else
                            <input type="text" hidden="hidden" name="item_code" value="0">
                            @endif
                            @if(checkProductConfig('brand'))
                                <div class="col-lg-3">
                                    <div class="col-sm-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Brand:<span
                                                    style="color:red;">*</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class='input-group date' id='' style="display: inline;">
                                            <select required name="brand_id" id="brand" class="form-control">
                                                <option value="-1" disabled selected>Select Brand</option>
                                                <?php foreach ($brands as $brand) {?>
                                                <option value="{{$brand->id}}">{{$brand->name_en}}
                                                    ##{{$brand->name}}</option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <input type="text" hidden="hidden" name="brand_id" value="8">
                            @endif

                            @if(checkProductConfig('foods'))
                                <div class="col-lg-2">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Is Extra?
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <label class="switch">
                                            <input type="checkbox" value="1" id="is_extra" name="is_food_extras">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3" id="extra_price" style="display: none;">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Extra Price
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                            <input type="text" value="" id="extra_price_input" class="form-control" name="extra_price">
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if(!checkProductConfig('foods') && !checkProductConfig('variations'))
                        <div class="row">
                            <!-- Is Bundle -->
                            <div class=" col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 " style="margin-top: 10px;">
                                <div class="col-lg-3">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Is Bundle
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <label class="switch">
                                            <input type="checkbox" value="1" name="is_bundle">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(checkProductConfig('foods'))
                            <hr>
                            @include('admin2.foods.create');
                        @endif
                        @if(checkProductConfig('variations'))
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 "
                                     style="">
                                    <span style="margin-right: 35px;"><b>Variations:</b></span>
                                </div>
                            </div>
                                <div class="row " id="variations_data">
                                    <?php $i = 1;?>
                                    @foreach($variation_data as $data)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-1" style="margin-left: 30px;">
                                                    <label for="">{{$data->variation}}</label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <!-- <input type="text" value=""> -->
                                                    <select name="variant_metas[]" class="form-control select2 variant_metas select2-multiple" multiple="" id="variant_meta{{$i}}">
                                                        @foreach($data->variation_options as $variant_meta)
                                                        <option value="{{$variant_meta->id}}">{{$variant_meta->variation_value_en}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++;?>
                                    @endforeach
                                </div>
                                <div class="row" id="variation_values" style="display: none;">

                                </div>
                            <hr>
                            <div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 "
                                 style="margin-top: 10px;">
                                <div class="col-lg-3">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Has Attributes?
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <label class="switch">
                                            <input type="checkbox" value="1" id="has_attributes" name="has_attributes">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="display_attribute" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12" id="text"
                                         style="margin-bottom: 60px;">
                                        <div class="row" id="dates" class="DateRow">
                                            <div class="row DateRow" id="field1"
                                                 style="margin-left: 20px;margin-top: 10px">
                                                <div class="col-sm-4">
                                                    <label for="contentType"> Attribute : <span
                                                                class="required-red">*</span></label>
                                                    <select  name="attributes_keys[]" id="key1"
                                                            class="form-control attribute_keys">
                                                        <option value="">Choose Key</option>
                                                        @foreach($var_attributes as $attribute)
                                                            <option value="{{$attribute->id}}"
                                                                    id="">{{$attribute->name_en}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="contentType">Value : <span class="required-red">*</span></label>
                                                    <select  name="attributes_value[]" id="value1"
                                                            class="form-control attributes_value">
                                                        <option value="">Choose Values</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-1">
                                                    <button class="btn btn-danger" style="margin-top:24px;"
                                                            type="button" id="removeDate"><i class="fa fa-minus"   aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button style="margin-left: 20px;" class="btn btn-primary" type="button"
                                                id="newDate"><i class="fa fa-plus"></i></button> &nbsp;&nbsp; Add an extra attribute
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endif
{{--                image upload section--}}
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-6 col-md-6 col-xl-6 ">
                                <br>
                                <hr>
                                <label for="image">Image : <span style="color:red;">*</span></label>
                                @if(checkProductConfig('multi_images') == true)
                                    <input type="file" name="images[]" id="upload_multi" multiple="" class="form-group">
                                @else
                                    <input type="file" name="images" id="upload" class="form-group">
                                @endif
                                <div id="image_upload" style="">
                                    <div class="col-md-3">
                                        <output id="result"/>
                                        <br>
                                        <img style="height: 150px;width: 200px;" id="img"
                                             src="{{asset('public/imgs/default.jpg')}}"/>
                                    </div>
                                    <div id="multi_images" style="display: none;">
                                        <div class="uploader__box js-uploader__box l-center-box">

                                            <div class="uploader__contents">
                                                <label class="button button--secondary" for="fileinput">Select Files</label>
                                                <input id="fileinput" name="images[]" class="uploader__file-input"
                                                       type="file" value="Select Files">
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-6 col-md-6 col-xl-6 ">
                                <br>
                                <hr>
                                <label for="image">Size Chart Image (Optional): <span style="color:red;"></span></label>
                                @if(checkProductConfig('multi_images') == true)
                                    <input type="file" name="chartImages[]" id="upload_multi" multiple="" class="form-group">
                                @else
                                    <input type="file" name="chartImages" id="upload" class="form-group">
                                @endif
                                <div id="image_upload" style="">
                                    <div class="col-md-3">
                                        <output id="result"/>
                                        <br>
                                        <img style="height: 150px;width: 200px;" id="img"
                                             src="{{asset('public/imgs/default.jpg')}}"/>
                                    </div>
                                    <div id="multi_images" style="display: none;">
                                        <div class="uploader__box js-uploader__box l-center-box">

                                            <div class="uploader__contents">
                                                <label class="button button--secondary" for="fileinput">Select Files</label>
                                                <input id="fileinputSizeChart" name="chartImages[]" class="uploader__file-input"
                                                       type="file" value="Select Files">
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-32">
                                <button type="submit" onclick="disableBtn(this)" id="btnSubmit" style="margin-left: 12px" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>


        </div>


    </div>


    -
</div>


</div>
</div> <!-- container -->
</div> <!-- content -->
</div>
<!-- End content-page -->
<!-- Footer Area -->
@include('layouts.admin.footer')

<!-- End Footer Area-->
</div>
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>
<script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>

<!-- <script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.js"></script> -->
<script src="{{url('public/admin/plugins/select2/js/select2.js')}}"></script>

<script src="{{url('public/lou/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script>
var count_variation_data = '<?php echo count($variation_data); ?>';

function disableBtn(ele) {
    setTimeout(function(){$(ele).prop('disabled', true);},50);
}
    $('.variant_metas').on('change',function(){
        var variant_array = [];
        $('.variant_metas').each(function(){
            var attr_id =  $(this).attr('id');
            var attr_num = attr_id.match(/\d+/);
            variant_array[attr_num] = $('#'+attr_id).val();
        });
        var standard_rate = $('#standard_rate').val();
        if(standard_rate == ''){
            standard_rate = 0;
        }
        var cost = $('#cost').val();
        if(cost == ''){
            cost = 0;
        }
         variant_array = JSON.stringify(variant_array.clean());
        $.ajax({
            method: 'GET',
            url: '{!! route('variationTable') !!}',
            data: {'variants': JSON.stringify(variant_array),'standard_rate':standard_rate,'cost':cost},
            success: function (response) {
                if(response.data.length>0){
                    $('#variation_values').html(response.data);
                    $('#variation_values').css('display','block');
                }
                console.log(response);
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });

    $('#standard_rate').on('input',function(){
        var value = $(this).val();
        $('.variant_price').each(function(){
            $(this).val(value);
        });
    });
    $('#cost').on('input',function(){
        var value = $(this).val();
        $('.variant_cost').each(function(){
            $(this).val(value);
        });
    });

    $('#has_attributes').on('change', function () {
        if ($(this).is(':checked')) {
            $('#display_attribute').css('display', 'block');
        } else {
            $('#display_attribute').css('display', 'none');
        }
    });

    function getAllValues() {
        var values = [];
        $('.attributes_value').each(function () {
            values.push($(this).val());
        });
        return values;
    }

    $(document).on('change', '.attribute_keys', function (e) {
        var attr_id = $(this).attr('id');
        var key = $(this).val();
        var attr_digit = $(this).attr('id').match(/\d+/);
        var attribute_values = getAllValues();
        $.ajax({
            method: 'GET',
            url: '{!! route('attributes') !!}',
            data: {'key': key, 'values': attribute_values},
            success: function (response) {
                $('#value' + attr_digit).empty();
                var options = '';
                $('#value' + attr_digit).append('<option value="">Choose Values</option>');

                $.each(response.attributes_values, function (index, value) {
                    options += '<option value="' + value.id + '">' + value.variation_value_en + '</option>';
                });
                $('#value' + attr_digit).append(options);
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });


    $(document).ready(function () {
        $(document).on('click', '#removeDate', function () {
            var numItems = $('.DateRow').length;
            if (numItems > 1) {
                //$('#removeDate').prop('disabled',false);
                $(this).parent().parent().remove();
            }
        });
        $('#newDate').click(function () {
            var id = $('.DateRow:last').attr('id');
            var id_num = $('.DateRow:last').attr('id').match(/\d+/);
            var attr_key = $('#' + id + ' #key' + id_num).val();
            var attr_value = $('#' + id + ' #value' + id_num).val();
            if (attr_key == null || attr_key == '' || attr_value == null || attr_value == '') {
            } else {
                var newClone = $('.DateRow:last-of-type').clone();
                newClone.appendTo('#dates');
                var contentTypeInput = $('.DateRow:last');
                var cTypeIncrementNum = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) + 1;
                contentTypeInput.attr('id', 'field' + cTypeIncrementNum);
                $('.DateRow:last #key' + id_num).attr('id', 'key' + cTypeIncrementNum);
                $('.DateRow:last #value' + id_num).attr('id', 'value' + cTypeIncrementNum);
                $('#value' + cTypeIncrementNum).empty();
                $('#value' + cTypeIncrementNum).append('<option value="">Choose Values</option>');
            }
        });
    });

var count_variation_data = '<?php echo count($variation_data); ?>';
    $(document).on('click', '#variant_added', function (e) {
        var selected_variations = [];
        $(".selected_variation").each(function () {
            selected_variations[i++] = $(this).val(); //this.id
        });
        if(count_variation_data == 0 || count_variation_data =='0'){
            alert('Variation list is empty');
            return false;
        }
        selected_variations.clean(undefined);
        $.ajax({
            method: 'GET',
            url: '{!! route('addVariationView') !!}',
            data: {'selected_variations': selected_variations},
            success: function (response) {
                // console.log(response.data);
                if (response == 'false') {
                    alert('No Variation Selected');
                    return false;
                }
                var div_exist = ($('#variations_data .card-block:last').length > 0);
                if (!div_exist) {
                    $('#variations_data').append(response.data);
                    var id = $('#variations_data .card-block:last').attr('id');
                    var contentTypeInput = $('#variations_data .card-block:last').prop('id');
                } else {
                    var id = $('#variations_data .card-block:last').attr('id');
                    var contentTypeInput = $('#variations_data .card-block:last').prop('id');
                    $('#variations_data').append(response.data);
                    var cTypeIncrementNum = parseInt(id.match(/\d+/g), 10) + 1;
                    $('#variations_data .card-block:last').attr('id', 'field' + cTypeIncrementNum);
                    $('#variations_data #field' + cTypeIncrementNum + ' .variation_item1').each(function () {
                        $(this).attr("name", 'variation_item' + cTypeIncrementNum + '[]');
                        $(this).attr('class', 'variation_item' + cTypeIncrementNum);
                    });
                    $('#variations_data .card-block:last #variation_item1').attr('id', 'variation_item' + cTypeIncrementNum);
                    $('#variations_data .card-block:last #variation_image1').attr('id', 'variation_image' + cTypeIncrementNum);
                    $('#variations_data .card-block:last #del1').attr('id', 'del' + cTypeIncrementNum);
                    var field = document.getElementById("variation_image" + cTypeIncrementNum);
                    field.setAttribute("name", 'variation_image' + cTypeIncrementNum + '[]');
                }
                $('#variant_modal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });

    function getSecondPart(str) {
        return str.split('-')[1];
    }

    $(document).on('click', '.delete_variation', function () {
        var id = $(this).attr('id');
        var deleted_div_id = $(this).attr('id').match(/\d+/); // 123456
        $('#field' + deleted_div_id).remove();
    });
    $(document).on('change', '.variant_value', function () {
        var id = $(this).attr('id');
        var select_id = $(this).attr('id').match(/\d+/); // 123456
        var variant_value = $(this).val().match(/\d+/)
        var variation_name = $('#variation_name' + select_id).val();
        var name = variation_name + '_' + variant_value + '[]';
        var field = document.getElementById("image" + select_id);
        field.setAttribute("name", name);  // using .setAttribute() method
    });

$('.variant_metas').each(function(){
    var attr_id = $(this).attr('id');
    // console.log(attr_id);
    $('#'+attr_id).select2();
});
    // $(".variant_metas").select2();

    $("#item_group").change(function (e) {
        var item_group = e.target.value;
        $.ajax({
            method: 'GET',
            url: '{!! route('item_group') !!}',
            data: {'item_group': item_group},
            success: function (response) {
                $('#second_item_group').empty();
                $('#second_item_group').attr('class', 'form-control select2');
                $('#second_item_group').append('<option value="All Item Groups" disabled selected>Second Item Group</option>');
                $.each(response, function (index, value) {
                    var options = '';
                    $.each(value, function (index, value) {
                        options += '<option value="' + value.id + '">' + value.name_en + '</option>';
                    });
                    $('#second_item_group').append('<optgroup label="' + index + '">' + options + '</optgroup');

                });
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
