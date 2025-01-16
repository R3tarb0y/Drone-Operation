<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Asset</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    @include('layouts.sidebar2')
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
            @include('layouts.navigation')
               
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Asset  Data</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable4" width="100%" cellspacing="0">
                <thead >
            <tr>
                <th >#</th>
                <th >Manufacture</th>
                <th >Jenis</th>
                <th >Nama Barang</th>
                <th >Serial Number</th>
                <th >Tahun</th>
                <th >Tanggal</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assets as $asset)
            <tr >
                <td >{{ $loop->iteration }}</td>
                <td >{{ $asset->manufacture }}</td>
                <td >{{ $asset->jenis }}</td>
                <td >{{ $asset->nama_barang }}</td>
                <td >{{ $asset->serial_number }}</td>
                <td >{{ $asset->tahun }}</td>
                <td >{{ $asset->tanggal }}</td>
                <td >
                    <a href="{{ route('asset.history', $asset->id_asset) }}" 
                    style="background-color: #4299e1; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; display: inline-block; border: 1px solid gray;">
                    History
                    </a>
                    <a href="{{ route('asset.edit', $asset->id_asset) }}" 
                    style="background-color: #f6ad55; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; display: inline-block; border: 1px solid gray;">
                    Edit
                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>       

            <!-- Footer -->
            @include('layouts.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

`    <!-- Logout Modal-->
  <!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js')}}"></script>

    <script src="{{ asset('admin_assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('admin_assets/js/demo/datatables-demo.js')}}"></script>
    <script>
            $(document).ready(function() {
        // Initialize DataTables
        $('#dataTable4').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });

    </script>
</body>

</html>


