<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
    <style>
        .color{
            color: #fff;
        }
    </style>
</head>


<body>

<div style="background: #fff;" class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="account-bg">
        <div style="border: #901a1d" class="card-box m-b-0">
            <div class="text-xs-center m-t-20">
                <a href="#" class="color logo">
                    {{-- <i class="color zmdi zmdi-group-work icon-c-logo"></i> --}}
                    <img src="https://khotwh.com/assets/images/icon/logo.png" height="70" width="180" alt="">
                    {{-- <span  class="color"> Retailak</span> --}}
                </a>
            </div>
            <div class="m-t-10 p-20">

                <form class="m-t-20" method="POST" action="{{ url('/reset/password') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                        <div class="col-xs-12">
                            <input class="form-control"
                                   id="password" type="password" min="6" name="password" required="" placeholder="Password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif

                        </div>
                    </div>

                    <div class="form-group row">
                        <div  class="color col-md-12">
                            <input id="confirm_password" type="password"
                                   class="form-control" placeholder="confirm password"
                                   min="6"
                                   name="password_confirmation" required>
                        </div>
                    </div>


                    <div class="form-group text-center row m-t-10">
                        <div class="col-xs-12">
                          </div>
                    </div>


                    <div class="form-group text-center row m-t-10">
                        <div class="col-xs-12">
                            <button style="background-color:#6d7e87; color:#fff;" class=" btn btn-success btn-block waves-effect waves-light" type="submit">Submit</button>
                            <input type="hidden" name="token" value="{{$user->token}}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-xs-12">
                            <div  class="color col-sm-12">

                                <label for="">
                                Enter Your New Password And Press Submit
                                </label>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end card-box-->

</div>
<!-- end wrapper page -->


<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{url('public/admin/js/jquery.min.js')}}"></script>
<script src="{{url('public/admin/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
<script src="{{url('public/admin/js/bootstrap.min.js')}}"></script>
<script src="{{url('public/admin/js/detect.js')}}"></script>
<script src="{{url('public/admin/js/fastclick.js')}}"></script>
<script src="{{url('public/admin/js/jquery.blockUI.js')}}"></script>
<script src="{{url('public/admin/js/waves.js')}}"></script>
<script src="{{url('public/admin/js/jquery.nicescroll.js')}}"></script>
<script src="{{url('public/admin/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{url('public/admin/js/jquery.slimscroll.js')}}"></script>
<script src="{{url('public/admin/plugins/switchery/switchery.min.js')}}"></script>

<!-- App js -->
<script src="{{url('public/admin/js/jquery.core.js')}}"></script>
<script src="{{url('public/admin/js/jquery.app.js')}}"></script>

<script>

    var password = document.getElementById("password")
        , confirm_password = document.getElementById("confirm_password");

    function validatePassword(){
        if(password.value !== confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>
</body>
</html>
