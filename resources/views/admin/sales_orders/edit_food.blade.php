<!DOCTYPE html>
<head>
    @include('layouts.admin.head')
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    @include('layouts.admin.scriptname_desc')
    <style>
        .page-title-box {
            background-color: #ffffff;
            margin-left: -5px;
            width: 94%;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>
</head>

<div class="modal fade bd-example-modal-lg" id="foodModal" tabindex="-1" role="dialog" data-backdrop="static"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
</div>

<!-- Latest compiled and minified bootstrap-select CSS -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
<div class="modal fade bd-example-modal-lg" id="myModal3" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa fa-exclamation-triangle" aria-hidden="true">User Cart</i>
            </div>
            <div class="modal-body">
                <table id="myTablee" class=" table order-lists">
                    <thead>
                    <tr>
                        <td>Select Item</td>
                        <td>Items</td>
                        <td>Quantity</td>
                        <td>rate</td>
                        <td>Total Amount</td>
                    </tr>
                    </thead>
                    <tbody id="tblrownew55">
                    <!-- <tr id="fieldset">
                    </tr> -->
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <br>

                    </tr>
                    <tr>
                    </tr>
                    </tfoot>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a id="canceled" class="btn btn-sm btn-danger"
                   href="javascript:void(0)"
                   title="Hapus"><i
                        class="glyphicon glyphicon-trash"></i> Cancelled Products</a>
            </div>
        </div>
    </div>
</div>
<html>
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
                        Sales Orders
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Sales Orders
                    @endslot
                    You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->

                {!! Form::open(['url' => ['/admin/sales-orders', $order->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block" style="width: 93%">

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
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">User
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-9">
                                    <div class='input-group date' id='selected' style="display: inline;">
                                        <input type="hidden" name="user_id" value="{{$order->user_id}}" id="users">

                                        @foreach ($users as $user)
                                            @if($order->user_id == $user->id)  {{$user->name}} @endif
                                        @endforeach

                                        {{--<select required name="user_id"  style="width:300px;" id="users" class="form-control  select2" style="">--}}
                                        {{--<option value="-1"  data-foo="Select Email" selected>Select User</option>--}}
                                        {{--@foreach ($users as $user)--}}
                                        {{--<option value="{{$user->id}}" @if($order->user_id == $user->id)selected @endif data-foo="{{$user->email}}">{{$user->name}}</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                    </div>
                                </div>
                            <!-- <div class="col-sm-3" style="display: inline;">
                              <?php $adduser = url('/admin/users/create'); ?>
                                sales_order_request
                                     <a href="{{$adduser}}?sales_order_request=1"}
                                 class=" button btn btn-primary ">Create User
                              </a>
                            </div> -->
                            </div>


                        </div>

                        <div class="col-lg-6">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Address
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <div class='input-group date' id='' style="display: inline;">
                                        <select required name="address_id" id="address" class="form-control">
                                            <option value="-1" disabled selected>Select Address</option>

                                        </select>
                                        <input type="text" hidden="hidden" id="district" name="district_id"/>
                                        <input type="text" hidden="hidden" id="token" name="token"/>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="display: inline;">
                                    <?php $add_address = url('/admin/address/create'); ?>

                                    <a href="#"
                                       class=" button btn btn-primary " id="create_address">Create Address
                                    </a>
                                </div>
                                <div class="col-sm-3" style="display: inline;">
                                    <?php $edit_address = url('/admin/address/' . $order->address_id . '/edit'); ?>
                                    <a href="{{$edit_address}}"
                                       class=" button btn btn-primary " id="edit_address">Edit Address
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>


                    @if(checkProductConfig('foods') && checkProductConfig('maintaining_stocks') == false)

                        <hr>
                        <div class="row" style="margin-left: 20px;"><span><b>Add Items</b></span></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <div class="col-sm-4">
                                            <select required name="items[]" style="width:300px;" id="items1"
                                                    class="form-control selectpickerr select2 item_data" style="">
                                                <option value="" disabled data-foo="Select Item" selected>Select Item
                                                </option>
                                                @foreach ($products as $product)
                                                    <option value="{{$product->id}}" data-foo="{{$product->name_en}}"
                                                            id="option-{{$product->id}}">{{$product->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2" style="float: right;right: 40px;">
                                            <button style="margin-left: 0px;height: 28px;" class="btn btn-primary"
                                                    type="button" id="newDate"><i class="fa fa-plus"></i></button>
                                            &nbsp;&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>



                        <div class="row" style="margin-left:20px;">
                            <div class="row row-horizon" id="cart_data" style="width: 100%">
                                @if(isset($food_data) && count($food_data)>0)
                                    @foreach($food_data as $cart)
                                        {!! $cart !!}
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <hr>
                    @else
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-sm-12">
                                    <table id="myTable" class=" table order-list">
                                        <thead>
                                        <tr>
                                            <td>Items</td>
                                            <td>Quantity</td>
                                            <td>rate</td>
                                            <td>Total Amount</td>
                                            <td> Actions
                                                {{--<input type="button" class="btn btn-lg btn-primary " id="addrow" value="+" />--}}
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody id="tblrownew0">
                                        <?php $i = 1; ?>
                                        @foreach($item_details as $order_item)
                                            <tr id="{{$order_item->item_id}}" class="itemRow">
                                                <td class="col-sm-3"><input type="hidden" name="items[]"
                                                                            value="{{$order_item->id}}">{{$order_item->name}}
                                                </td>
                                                <td class="col-sm-1"><input type="number" min="1" class="qtyInput"
                                                                            id="{{$order_item->id}}-qty"
                                                                            name="quantity[]"
                                                                            onchange="updateQuantity(this)"
                                                                            value="{{$order_item->qty}}"/></td>
                                                <td class="col-sm-2"><input type="hidden" name="rate[]"
                                                                            value="{{$order_item->rate}}">{{$order_item->rate}}
                                                </td>
                                                <td class="col-sm-2"><input type="text"
                                                                            id="{{$order_item->item_id}}-total"
                                                                            class="total"
                                                                            value="{{$order_item->qty*$order_item->rate}}"
                                                                            disabled="disabled"/></td>
                                                <td><input type="button" class="ibtnDel btn btn-md btn-danger "
                                                           value="Delete">
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 p-5">
                                <hr>
                                <h6 class="p-5"> Select Products items and Variations</h6>
                                <div id="products_variations_table">
                                    <table class="table table-light">
                                        <tbody>
                                        <tr>
                                            <div id="outOfStockItem" class="col-sm-12 alert alert-danger"
                                                 style="display: none">
                                            </div>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="parent_item" id="parent_item" class="form-control"
                                                        style="width:250px !important;">
                                                    <option value="">Select an item</option>
                                                    @foreach($parentProducts as $parentProduct)
                                                        <option
                                                            value="{{$parentProduct->item_code}}">{{$parentProduct->name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class=" col-sm-10" id="VariantSelectorContainer"></div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary" disabled="disabled"
                                                        id="addItem">Add Item
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <input type="text" id="SelectedItemForCart" class="hidden">
                                </div>
                                <hr>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <label for="note">External Reciept id</label>
                            </div>
                            <div class="col-sm-12">
                                <input type='text' name="reciept_id" value="{{$order->external_reciept_id}}"
                                       class="form-control">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <label for="note">Note</label>
                            </div>
                            <div class="col-sm-12">
                                <textarea name="note" cols="30" rows="7">{{$order->note}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Time Section
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <div class='input-group date' id='' style="display: inline;">
                                    <select required name="timesection_id" id="timesection_id" class="form-control">
                                        <option value="-1" disabled selected>Select Time Section</option>
                                        <?php foreach ($time_sections as $time_section) { ?>
                                        <option value="{{$time_section->id}}"
                                                @if($order->timesection_id == $time_section->id)selected @endif>{{$time_section->name}}</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Role
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <div class='input-group date' id='' style="display: inline;">
                                    <select name="shipping_role_id" id="shipping_role_id" class="form-control">
                                        <option value="-1">Select Shipping role</option>
                                        <?php foreach ($shippingRoles as $shipping_rule) { ?>
                                        <option rate="{{$shipping_rule->rate}}" value="{{$shipping_rule->id}}"
                                                @if($order->shipping_role_id == $shipping_rule->id || $shipping_rule->id == $shipping_role->id ) selected @endif >{{ $shipping_rule->shipping_rule_label}}</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rate
                                </label>
                            </div>
                            <div class="col-sm-12">

                                <input type="number" @if(!$admin_user->can('edit_order')) readonly @endif class="form-control" id="shipping_rate"
                                       name="shipping_rate" @if($freeShipping ==0) value="{{$order->shipping_rate}}"
                                       @else value="0" @endif >
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        @if($order->discount == 0)
                        <div class="col-lg-6">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">PromoCode
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" id="promocode" class="form-control promocode" name="promocode" data-order="{{$order->id}}"
                                       @if(isset($promo_code)) readonly="readonly" value="{{$promo_code}}" @endif>

                            </div>
                        </div>


                        <div class="col-lg-6" id="validate" @if(!isset($promo_code)) style="display: none;@endif">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">PromoCode Validate
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" value="Promocode is valid" id="promocode_response"
                                       disabled="disabled" class="form-control promocode" name="promocode" value="">

                            </div>
                        </div>
                        @endif
                    </div>


                    <input type="text" hidden="hidden" name="sales_order_request"
                           value="@if(isset($sales_order_request)){{$sales_order_request}}@endif">

                    <div class="row">
                        <div class="col-lg-12">
                            <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Total amount
                                before shipping
                            </label>
                        </div>

                        <div class="col-lg-6">
                            <input @if(!$admin_user->can('edit_order')) readonly @endif type="text" class="form-control"
                                   name="order_total_price" id="orderTotalPrice"
                                   value="{{$order->total_price}}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6"
                             style="left:20px;width: 480px;right: 30px; ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                            <div class="col-lg-12"
                                 style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand
                                    Total With Shipping Fees
                                </label>
                            </div>
                            <div class="col-lg-12" style="margin-top: 0">
                                <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input disabled type="number" name="grand_total_amount"
                                           style="height: 40px;text-align: center;"
                                           id="grand_total"
                                           class="form-control"
                                           value="{{($order->total_price - $order->discount) + $order->shipping_rate}}" >

                                </div>
                            </div>


                        </div>
                        <div class="col-lg-6" id="discount_block"
                             style="display:none;right:-30px;width: 480px ; height:150px;border: 2px solid gray; margin-top: 100px;">

                            <div class="col-lg-12"
                                 style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Total
                                    After Promo code Discount
                                </label>
                            </div>
                            <div class="col-lg-12" style="margin-top: 0">
                                <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="total_amount_after_discount"
                                           style="height: 40px;text-align: center; " id="total_amount_after_discount"
                                           class="form-control" value="0">

                                </div>
                            </div>


                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-32">
                            <button type="submit" onclick="disableBtn(this)" id="save" style="margin-left: 12px"
                                    class="btn btn-primary">Save
                            </button>
                            <div class="col-sm-32">
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>


        </div>


    </div>


</div>


<!-- End content-page -->
<!-- Footer Area -->
@include('layouts.admin.footer')

<!-- End Footer Area-->
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>
<script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

@include('admin.sales_orders.food_scripts')
<script type="text/javascript">

    function grandTotalAmount() {

        var grand_total_amount = 0;
        var idArray = [];
        $('.total').each(function (value) {
            console.log('Total', this.value);
            idArray.push(this.value);
        });

        console.log('aarr: ', idArray);

        $.each(idArray, function (index, value) {
            console.log(index, ' dd ', value);
            grand_total_amount += parseFloat(value);
        });
        shipping_rule_rate = $('#shipping_rate').val();
        grand_total_amount += parseFloat(shipping_rule_rate);
        $('#grand_total').val(Math.round(grand_total_amount * 100) / 100);
    }

    function updateTotalPrice() {
        let idArray = [];
        let total_price = 0;
        $('.total').each(function (value) {
            console.log('Total', this.value);
            idArray.push(this.value);
        });

        $.each(idArray, function (index, value) {
            console.log(index, ' vv ', value);
            total_price += parseFloat(value);
        });
        $('#orderTotalPrice').val(total_price);
    }

    function disableBtn(ele) {
        setTimeout(function () {
            $(ele).prop('disabled', true);
        }, 50);
    }

    var response_array = [];
    var items_array = <?php  echo json_encode($products);  ?>;

    var selected = [];
    var items_options = [];
    var checkout;
    counter = 0;

    function updateQuantity(e) {
        console.log('called 6');
        updateTotalPrice();
        var qty = $(e).val();
        var thisId = $(e).attr('id');
        var itemId = $(e).closest('tr').attr('id');
        var userId = $('#users').val();
        var currentUser = getUserDetails(userId);
        $.ajax({
            headers: {
                'token': currentUser.token,
                'district_id': $('#district').val()
            },
            method: 'GET',
            url: '{!! url('api/product/cms?id=') !!}' + itemId,
            success: function (Product) {
                if (qty > Product.stock_qty) {
                    alert('There id only ' + Product.stock_qty + ' items left in stock');
                    $('#' + thisId).val(Product.stock_qty);
                    qty = Product.stock_qty;
                    $('#' + itemId + '-total').val(qty * Product.standard_rate);
                } else {
                    $('#' + itemId + '-total').val(qty * Product.standard_rate);
                }
                grandTotalAmount();
            }
        });
    }

    $(document).on('mouseover', 'select', function () {
        $('#parent_item').select2({});
    });
    var selected_parents = [];
    $(document).on('change', '#parent_item', function () {
        console.log('xx');
        selectedItem = $("#parent_item option:selected").val();
        $('#SelectedItemForCart').val(selectedItem.id);
        selected_parents.push(selectedItem);
        district_id = $('#district').val();
        $.ajax({
            url: '{!! url('admin/getProductVariants/') !!}/' + selectedItem,
            headers: {
                'token': token,
                'lang': 'en',
                'district_id': district_id,

            },
            success: function (response) {
                $('#VariantSelector').html('');
                $("#VariantSelectorContainer").html('');
                $("#VariantSelectorContainer").css("display", "none");
                $('#ItemSelectorContainer').css('display', 'none');
                if (Array.isArray(response) && response.length > 0) {
                    variantSelector = '<select class="form-control input-sm" id="VariantSelector"> <option selected disabled> Select Variant</option> ';
                    $('#VariantSelectorContainer').html('');
                    availableVariants = 0;
                    $.each(response, function (index, Variant) {
                        if (Variant.stock_qty > 0) {
                            availableVariants = availableVariants + 1;
                            variantSelector += '<option value=' + Variant.id + '>' + Variant.name + '</option>';
                        }
                    });
                    variantSelector += '</select>';
                    if (availableVariants > 0) {
                        $('#VariantSelectorContainer').html(variantSelector);
                        $('#ItemSelectorContainer').css("display", "none");
                        $("#VariantSelectorContainer").css("display", "block");
                        $('#outOfStockItem').hide();
                    } else {
                        $('#addItem').prop("disabled", true);
                        $('#outOfStockItem').html('The Item (' + selectedItem + ') is out of stock ');
                        $('#outOfStockItem').show();
                        $('#ItemSelectorContainer').css("display", "block");
                        $("#VariantSelectorContainer").css("display", "none");
                    }
                } else {
                    $('#SelectedItemForCart').val(selectedItem.id);
                }
            }
        })
    }); //Beshir
    $(document).on('change', '#VariantSelector', function () {
        console.log('called 1');
        $('#addItem').prop("disabled", false);
        $('#SelectedItemForCart').val($(this).val());
    });
    $(document).on('click', '#addItem', function () {
        console.log('called 2');

        $('#addItem').prop("disabled", true);
        $('#VariantSelectorContainer').html("");
        selectedItemId = $('#SelectedItemForCart').val();
        FirstItem = [];
        FirstItem['id'] = parseInt(selectedItemId);
        let userId = $('#users').val();
        console.log('Itemsss:', document.getElementsByName("items[]"));
        //let selectedItems = (array)document.getElementsByName("items[]");
        let selectedItems = Array.prototype.slice.call(document.getElementsByName("items[]"));
        if (selectedItems.includes(selectedItemId)) {
            $('#VariantSelectorContainer').hide();
            $('#ItemSelectorContainer').show();
            alert('this item is already selected');
        } else {
            var currentUser = getUserDetails(userId);
            console.log('CurrUser', currentUser);
            $.ajax({
                headers: {
                    'token': currentUser.token
                },
                method: 'GET',
                url: '{!! url('api/product/cms?id=') !!}' + FirstItem['id'],
                success: function (Product) {
                    console.log('Product', Product);
                    itemRows = '<tr id="' + Product.id + '" class="itemRow" ><td class="col-sm-3"><input type="hidden" name="items[]" value="' + Product.id + '">' + Product.name + '</td><td class="col-sm-1"><input type="number" min="1" class="qtyInput" id="' + Product.id + '-qty" name="quantity[]" value="1" onchange="updateQuantity(this)"/> </td><td class="col-sm-2"><input type="hidden" name="rate[]" value="' + Product.standard_rate + '" />' + Product.standard_rate + '</td><td class="col-sm-2"><input type ="text" id="' + Product.id + '-total" class="total" value="' + Product.standard_rate + '" disabled = "disabled" /> </td><td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td></tr>';
                    $('#tblrownew0').append(itemRows);
                    console.log('yy');
                    grandTotalAmount();
                }
            });
        }
    });


    function getUserDetails(userId) {
        console.log('jj');

        var userData = fetch("{!! route('getUserData') !!}?id=" + userId)
            .then((response) => response.json());
        $('#token').val(userData.token);
        return userData;
    }

    $(document).on('click', '.ibtnDel', function () {
        console.log('called 4');

        $(this).closest('tr').remove();
        grandTotalAmount();
    });

    $(document).on('change', '#shipping_role_id', function () {
        console.log('called 5');

        //rate = $(this).attr('rate');
        var element = $("option:selected", this);
        rate = element.attr('rate');
        console.log('Rate', rate);
        $('#shipping_rate').val(rate);
        console.log('mmm');
        grandTotalAmount();
    });
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>
