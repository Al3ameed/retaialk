<!DOCTYPE html>
<html>

<head>
    @include('layouts.admin.head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
        type="text/css" />
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
                    @include('layouts.admin.breadcrumb')
                    <!--End Bread Crumb And Title Section -->

                    <div class="col-md-12 card-box bg-faded">
                        <div class="card-title card-header">
                            <h4 class="text-primary" style="text-align: center;">Order Tracking Details</h4>
                        </div>
                        {{--Bosta Integration--}}
                        @if($type === 1)
                        <div class="card-body p-5">
                            <p>
                                <b>Tracking Order : </b>
                                {{$shipment->order->id}}
                            </p>
                            <p>
                                <b>By : </b>
                                Bosta
                            </p>
                            <p>
                                <b>Shipping Number :</b>
                                {{ $shipment->shipment_id }}
                            <input type="text" hidden name="" id="shipmentId" value="{{$shipment->shipment_id}}">
                            </p>
                            <p>
                                <b>PDF URL :</b>
                                <span id="pdfData">
                                    <a href="{{$shipment->label_url}}" target="_blank" download="proposed_file_name" id="pdfURL">Download</a>
                                </span>
                            </p>
                            <p>
                                <b>Tracking Status : </b>
                                @if(isset($tracking['state-history']))
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tracking['state-history'] as $track)
                                        <tr>
                                            @if(is_object($track->state))
                                            <td>
                                                {{ $track->state->after }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($track->timestamp) }}
                                            </td>
                                            @else
                                            <td>
                                                {{ $track->state }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($track->timestamp) }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                there is no status for this order
                                @endif
                            </p>
                        </div>
                        @endif
                        {{--Aramex Integration--}}
                        @if($type === 0)
                        <div class="card-body p-5">
                            <p>
                                <b>Tracking Order  : </b>
                                {{$shipment->order->id}}
                            </p>
                            <p>
                                <b>By : </b>
                                Aramex
                            </p>
                            <p>
                                <b>Shipping Number :</b>
                                {{ $shipment->shipment_id }}
                            </p>
                            <p>
                                <b>Pdf Url :</b>
                                <a download='proposed_file_name' target="_blank"
                                    href="{{ $shipment->label_url  }}"> Download file </a>
                            </p>
                            <p>
                                <b>Tracking Status : </b>
                                {{ $shipment->order->status }}
                            </p>
                        </div>
                        @endif
                    </div>
                    <div class="result">

                    </div>
                    <!-- container -->
                </div>
                <!-- content -->
            </div>
            <!-- End content-page -->
            <!-- Footer Area -->
            @include('layouts.admin.footer')
            <!-- End Footer Area-->
        </div>

        @include('layouts.admin.javascript')

        <!-- END wrapper -->
        <script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}">
        </script>
        <script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}">
        </script>
        <script>
                    <?php $storeShipmentReceiptsUrl = url('/api/shipment/receipt/store');?>
            fetchShipmentDocument();
            {{--function track() {--}}
            {{--const trackingUrl = '{{url('shipment')}}';--}}
            {{--$('#track').click(function () {--}}
                {{--$.ajax({--}}
                    {{--url: trackingUrl,--}}
                    {{--type: "GET",--}}
                    {{--success: function (data) {--}}
                        {{--$(".result").html(data);--}}
                    {{--},--}}
                    {{--error: function (jqXHR, textStatus, errorThrown) {--}}
                        {{--alert('Error tracking shipment');--}}
                    {{--}--}}
                {{--});--}}
            {{--})--}}
        {{--}--}}

        <!-- JAVASCRIPT AREA -->
        <!-- JAVASCRIPT AREA -->
            {{--function fetchShipmentDocument() {--}}
            {{--    var id = $('#shipmentId').val();--}}
            {{--    var auth_key = "{{config('global.bosta_token')}}"--}}
            {{--    var url = "{{config('global.bosta_url')}}"--}}
            {{--    $.ajax({--}}
            {{--          url: url +'deliveries/awb/'+ id,--}}
            {{--          headers: {--}}
            {{--              "Authorization": auth_key--}}
            {{--          },--}}
            {{--         method:"GET",--}}
            {{--            success: function(result){--}}
            {{--                var pdf = result.data;--}}
            {{--                const linkSource = `data:application/pdf;base64,${pdf}`;--}}
            {{--                const downloadLink = document.getElementById("pdfURL");--}}
            {{--                const fileName = "tracking.pdf";--}}
            {{--                downloadLink.href = linkSource;--}}
            {{--                downloadLink.download = fileName;--}}
            {{--                $('pdfData').html(downloadLink);--}}
            {{--            },--}}
            {{--            error: function(error) {--}}
            {{--                console.log('error: ', error);--}}
            {{--            }--}}
            {{--        });--}}
            {{--     }--}}

$(document).ready(function() {
    console.log('here');
    // fetchShipmentDocument();
});


        </script>
    </div>
</body>

</html>
