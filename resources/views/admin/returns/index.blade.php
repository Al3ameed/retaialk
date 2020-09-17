    <!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- DataTables -->
</head>

<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
@include('layouts.admin.topbar')
<!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
@include('layouts.admin.sidemenu')


<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog"
                 aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            Confirmation
                        </div>
                        <div class="modal-body">
                            Are you Sure That You Want To Change This Item  Status?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                            <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                        class="glyphicon glyphicon-trash"></i> Change Status </a>

                        </div>
                    </div>

                </div>
            </div>
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
                @endcomponent            <!--End Bread Crumb And Title Section -->
                <div class="row">

                  @if(session()->has('message'))
                      <div class="alert alert-success">
                          {{ session()->get('message') }}
                      </div>
                  @endif

                  
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


                  <div class="card card-block">
                    <div class="card-title">

                      <!-- Add Company Button -->
                      <div class="row">
                        <div class="col-sm-3">
                          <label for="">Order Id</label>
                            <input type="number" placeholder="Order Id" class="form-control" id="order_id" name="order_id">
                        </div> 
                      </div>
                       
                    </div>

                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Customer Name</th>
                                    <th>Customer Phone</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>


                            </table>


                            <!-- Table End-->
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
<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>

<script>
     
 <?php $editurl = url('/admin/products/');?>
 <?php $changestatus = url('/admin/product/status'); ?>
 <?php $order = url('/admin/products');?>
 <?php $manageUrl = url('/admin/products/'); ?>
 <?php $details = url('/admin/product-details/');?>

 var item_group = 0;

 // var ckbox = $('#item_group');


 var table = $('#items_datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
          url:'{!! route('SalesOrderManageList') !!}',
          type:"GET",
          data: function(d){
            d.order_id=document.getElementById('order_id').value;
          }
    },
    columns: [
          {data:'id',searchable:true ,name:'id'},
          // {data:'user',searchable:true ,name:'user'},
          {data:'date',name:'date'},
          // {data: 'customer_name', name: 'customer_name', orderable: false,visible: false, searchable: true},
          // {data: 'customer_phone', name: 'customer_phone', orderable: false,visible: false, searchable: true},
        ]
    });


$('#order_id').on('input',function(){
  table.ajax.reload();
});

 $(document).ready(function () {
     $("[data-toggle='tooltip']").tooltip();
 });


 function openModal(id) {
     $('#delItem').one('click',function (e) {
         e.preventDefault();
         delete_record(id);
     });
 }

 function delete_record(id) {

     // ajax delete data to database
     $.ajax({
         url: "{{$changestatus}}/" + id,
         type: "GET",

         success: function (data) {
             //if success reload ajax table
             // console.log(data.success);
             $('#myModal').modal('hide');

             if(data =='success'){
                 $('#items_datatable').DataTable().draw(false)

             }
         //                reload_table();
             
         },
         error: function (jqXHR, textStatus, errorThrown) {
             alert('Error deleting data');
         }
     });

     // $('#myModal').modal('toggle');

 //            setTimeout(function () {
 //                table.ajax.reload();
 //            }, 300);


         }
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>