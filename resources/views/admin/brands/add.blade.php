<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- App Favicon -->
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


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
                       Add Brand
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Brands
                @endslot
                You are not allowed to access this resource!
                @endcomponent  
            <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/brands', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    
                     <div style="margin-left: 5px" class="card-text">


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Name(English) :<span style="color:red;">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' value="{{ old('name_en') }} " required name="name_en" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12" style="float: right; text-align: right">
                                            <label style="margin-bottom: 0;" class="form-group" for="from"><span style="color:red;">*</span>: الاسم
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                            <div class='input-group date' id='datetimepicker1' style="display: inline; direction:rtl; text-align: right;">
                                                <input type='text' required name="name" value="{{ old('name') }}" class="form-control">
                                            </div>
                                        </div>


                                    </div>
                                    

                                </div>

                         <div class="row">
                             <div class="col-lg-6">
                                 <div class="col-lg-12">
                                     <label style="margin-bottom: 0;" class="form-group" for="from">Description (English)
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0px">
                                     <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                     <textarea class="form-control"  id="" name="description_en" rows="3">{{ old('description_en') }}</textarea>                                            </div>
                                 </div>
                             </div>
                             <div class="col-lg-6">
                                 <div class="col-lg-12" style="float: right; text-align: right">
                                     <label style="margin-bottom: 0;" class="form-group" for="from">الوصف
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                     <div class='input-group date' id='datetimepicker1' style="display: inline;  text-align: right">
                                     <textarea class="form-control" dir="rtl" id="" name="description" rows="3">{{ old('description') }}</textarea>                                            </div>
                                 </div>
                             </div>
                             
                         </div>
                         <div class="row">
                             <div class="col-lg-6">
                                 <div class="col-lg-12">
                                     <label style="margin-bottom: 0;" class="form-group" for="from">Company
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0px">
                                      <select class="form-control select2" name="company_id" id="crewgender">
                                <option disabled="disabled" selected>Select Company</option>
                                @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->name_en}}</option>
                                @endforeach
                            </select>
                                 </div>
                             </div>
                             
                         </div>


                             <div class="row ">
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <label for="image">Logo : <span style="color:red;">*</span></label>
                                        <input type="file" name="logo"  id="upload" multiple class="form-group" >

                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                       <img style="height: 150px;width: 200px;" id="img" src="{{asset('public/imgs/default.jpg')}}" />
                                            </div>
                                    </div>
                                </div>

                                 </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                                </div>
                            </div>

                {!! Form::close() !!}
            </div>
        </div>


    </div>


</div>


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


<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('public/clock/dist/bootstrap-clockpicker.min.js')}}"></script>

<script type="text/javascript" src="{{url('public/clock/assets/js/highlight.min.js')}}"></script>



 

<script type="text/javascript">
        function preview(input) {
         if (input.files && input.files[0]) {
           var reader = new FileReader();
           reader.onload = function (e) { $('#img').attr('src', e.target.result);  }
           reader.readAsDataURL(input.files[0]);     }   }

       $("#upload").change(function(){
         $("#img").css({top: 0, left: 0});
           preview(this);
           $("#img").draggable({ containment: 'parent',scroll: false });
       });
    </script>

<!-- JAVASCRIPT AREA -->
</body>
</html>