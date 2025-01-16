<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Realitation</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('layouts.sidebar3  ')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                @include('layouts.navigation')

                <!-- Begin Page Content -->
         <!-- Begin Page Content -->
         <div class="container-fluid">
    <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Realisasi Data</h6>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                            Export to CSV
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                <table class="table table-bordered" id="dataTable8" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Asset</th>

                <th>Sparepart</th>
                <th>Total Cost</th>
                <th>Payment Type</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($realisasi as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->asset->nama_barang }}</td>
                    <td>
                        <ul>
                            @foreach (json_decode($item->spareparts, true) as $sparepart)
                                <li>
                                    {{ $sparepart['nama_barang'] }} - Qty: {{ $sparepart['quantity'] }} - Price: {{ number_format($sparepart['total_price'], 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </td>

                    <td>{{ number_format($item->total_cost, 2) }}</td>
                    <td>{{ ucfirst($item->payment_type) }}</td>
                    <td>{{ $item->is_approved ? 'Approved' : 'Pending' }}</td>
                    <td>
                    @if (!$item->is_approved)
                            <button type="button" class="btn btn-success approve-btn"
                                data-action="{{ route('realisasi.approve', $item->id) }}">
                                Approve
                            </button>
                        @else
                            <button class="btn btn-secondary" disabled>Approved</button>
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

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->


<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>

<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Filter Export to CSV</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form Filter Export -->
                    <form action="{{ route('realisasi.exportCsv') }}" method="GET">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Export to CSV</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal for Approving -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Realisasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <label for="payment_type">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-control" required>
                        <option value="garansi">Garansi</option>
                        <option value="asuransi">Asuransi</option>
                        <option value="asuransi_pilot">Auransi(Pilot)</option>
                        <option value="bayar_sendiri">Consumable</option>
                        <option value="pilot">Pilot</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        $('#dataTable8').DataTable({
            "paging": true,  // Enable pagination
            "lengthChange":true,  // Disable changing the number of items per page
            "searching": true,  // Enable search
            "ordering": true,  // Enable column sorting
            "info": true,  // Show table info
            "autoWidth": false  // Disable auto width adjustment
        });
    });

        $(document).ready(function () {
        // Handle approve button click
        $('.approve-btn').click(function () {
            const formAction = $(this).data('action'); // Get route from data attribute
            $('#approveForm').attr('action', formAction); // Update form action
            $('#approveModal').modal('show'); // Show modal
        });
    });

    </script>

</body>

</html>
