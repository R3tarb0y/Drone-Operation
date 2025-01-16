<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Spareparts</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

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

                <!-- Begin Page Content -->
                <div class="container-fluid">
                <div class="flex justify-center mb-4">
                        <button class="btn btn-primary" id="toggleRequestFormButton">
                            Tambah Sparepart Baru
                        </button>
                    </div>

                    <div id="requestForm" style="display: none;" class="bg-white p-4 rounded shadow max-w-lg mx-auto">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('sparepart.add') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="kode_material" class="block text-gray-700 font-semibold">Kode Material</label>
                                <input type="text" id="kode_material" name="kode_material" class="form-input w-full border rounded p-2" value="{{ old('kode_material') }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="nama_sparepart" class="block text-gray-700 font-semibold">Nama Sparepart</label>
                                <input type="text" id="nama_sparepart" name="nama_sparepart" class="form-input w-full border rounded p-2" value="{{ old('nama_sparepart') }}" required>
                            </div>
                            <button type="submit" class="btn btn-success">Tambah Sparepart</button>
                        </form>
                    </div>


                    <!-- Pesan Sukses -->
                    @if(session('success'))
                        <div class="alert alert-success mt-4 text-center">
                            {{ session('success') }}
                        </div>
                    @endif
                <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Spareparts Data</h6>
                </div>
                <div class="card-body">
                <form method="GET" action="{{ route('sparepart.index') }}" class="flex justify-center mb-4">
                        <select name="warehouse_id" class="form-select mb-4 mr-2">
                            <option value="">Pilih Gudang</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                            <thead>
                   

                                <tr>
                                    <th >#</th>
                                    <th >Kode Material</th>
                                    <th >Nama Spareparts</th>
                                    <th >Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spareparts as $sparepart)
                                <tr >
                                    <td >{{ $loop->iteration }}</td>
                                    <td >{{ $sparepart->kode_material }}</td>
                                    <td>{{ $sparepart->nama_sparepart }}</td>
                                    <td >
                                        @if(request('warehouse_id'))
                                            @foreach ($sparepart->warehouses as $warehouse)
                                                @if($warehouse->id == request('warehouse_id'))
                                                    {{ $warehouse->pivot->quantity }}
                                                @endif
                                            @endforeach
                                        @else
                                            {{ $sparepart->totalQuantity() }}
                                        @endif
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

    <!-- Page level custom scripts -->

    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('admin_assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>


    <script>
            $(document).ready(function() {
        // Initialize DataTables
        $('#dataTable3').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
    </script>
<script>


    document.getElementById('toggleRequestFormButton').addEventListener('click', function() {
        const requestForm = document.getElementById('requestForm');
        if (requestForm.style.display === 'none' || requestForm.style.display === '') {
            requestForm.style.display = 'block';
        } else {
            requestForm.style.display = 'none';
        }
    });
</script>

</body>

</html>
