<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')

    <script src="http://malsup.github.com/jquery.form.js">
    </script>
    <!-- App Favicon -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"/>

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
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
                        Add Price Rule To Product {{$product_id}}
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Price Rules
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

                @if(Session::has('message'))
                    <p class="alert alert-success">{{ Session::get('message') }}</p>
                @endif

                {!! Form::open(['url' => '/admin/products/'.$product_id.'/manage/price_rule',
                 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}
                    <div class=" row card card-block">

                    <div class="col-md-12 form-group  ">
                        <div class="row">
                            <div class='col-md-12'>
                                <label>Price Rule Name</label>
                                <input class="form-control" type="text" name="price_rule_name"
                                       @if(isset($product->priceRuleRelationCMS)) value="{{$product->priceRuleRelationCMS->price_rule_name}}"
                                       @else  value="{{ old('price_rule_name') }}" @endif/>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="col-md-12 form-group">
                        <div class="row">
                            <div class='col-md-6'>
                                <label>Valid From</label>
                                <div class="input-group date" id="datetimepicker">
                                    <input type="text" name="valid_from"
                                           value="{{isset($product->priceRuleRelationCMS) ?
                                           date ('m/d/Y h:i:m',strtotime($product->priceRuleRelationCMS->valid_from)) : old('valid_from')}}"
                                           class="form-control">
                                    <span class="input-group-addon">
                                     <span class="zmdi zmdi-calendar"></span>
                                </span>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <label>Valid To</label>
                                <div class="input-group date" id="datetimepicker1">
                                    <input type="text" name="valid_to"
                                           value="{{isset($product->priceRuleRelationCMS) ?
                                           date ('m/d/Y h:i:m',strtotime($product->priceRuleRelationCMS->valid_to)) : old('valid_to')}}"
                                           class="form-control">
                                    <span class="input-group-addon">
                                     <span class="zmdi zmdi-calendar"></span>
                                </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12 form-group">

                        <div class="row">
                            <div class='col-md-6'>
                                <label>Price or Discount</label>
                                <select class="form-control" id="discount_type" name="discount_type" onchange="changeLE()">
                                    <option value="price"
                                            @if(isset($product->priceRuleRelationCMS)
                                            && $product->priceRuleRelationCMS->discount_type === 'price')selected @endif>
                                        Price
                                    </option>
                                    <option value="percentage"
                                            @if(isset($product->priceRuleRelationCMS)
                                             && $product->priceRuleRelationCMS->discount_type === 'percentage')selected @endif>
                                        Discount Percentage
                                    </option>
                                </select>
                            </div>

                            <div class='col-md-4' id="price" style="">
                                <label> Discount Rate </label>
                                <input class="form-control" type="number" min="0" max="{{$rate}}" step="0.01" id="discount_rate"
                                       name="discount_rate"
                                       @if(isset($product->priceRuleRelationCMS)) value="{{$product->priceRuleRelationCMS->discount_rate}}"
                                       @else  value="{{ old('discount_rate') }}" @endif/>
                            </div>
                            <div class="col-md-2">
                                <br><br>
                                <span id="discount_rate_type" style="font-weight: bold">{{isset($product->priceRuleRelationCMS) ?
                                ($product->priceRuleRelationCMS->discount_type === 'price' ? 'LE' : '%') : 'LE'}}</span>
                            </div>
                        </div>

                    </div>

                    <div class='col-md-3'>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>

                </div>
                {!! Form::close() !!}
                @if(isset($product->priceRuleRelationCMS))
                    <div>
                        {!! Form::open(['url' => '/admin/products/price_rule/'.$product->priceRuleRelationCMS->id,
                            'class'=>'form-hirozontal' , 'method' => 'delete']) !!}
                        <button class="btn btn-danger" type="submit">Delete Price Rule</button>
                        {!! Form::close() !!}
                    </div>
                @endif
            </div>

        </div>
    </div>
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

<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script>
    $(function () {
        $('#datetimepicker').datetimepicker({
            icons: {
                time: 'zmdi zmdi-time',
                date: 'zmdi zmdi-calendar',
                up: 'zmdi zmdi--up',
                down: 'zmdi zmdi--down',
                //previous: 'glyphicon glyphicon-chevron-left',
                previous: 'zmdi zmdi-backward',
                next: 'zmdi zmdi-right',
                today: 'zmdi zmdi-screenshot',
                clear: 'zmdi zmdi-trash',
                close: 'zmdi zmdi-remove'
            },

        });
        $('#datetimepicker1').datetimepicker({
            icons: {
                time: 'zmdi zmdi-time',
                date: 'zmdi zmdi-calendar',
                up: 'zmdi zmdi--up',
                down: 'zmdi zmdi--down',
                //previous: 'glyphicon glyphicon-chevron-left',
                previous: 'zmdi zmdi-backward',
                next: 'zmdi zmdi-right',
                today: 'zmdi zmdi-screenshot',
                clear: 'zmdi zmdi-trash',
                close: 'zmdi zmdi-remove'
            },
        });
    });

    function changeLE() {
        $('#discount_rate_type').html($('#discount_type').val() == 'price' ? 'LE' : '%');
        $('#discount_rate').val('');
        $('#discount_rate').attr({
           "max": 100 ,
           "min": 0 ,
            "step": 0.1
        });
    }
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>
