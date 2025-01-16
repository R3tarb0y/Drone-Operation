<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drone Operation - Report</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Tambahkan CSS sesuai kebutuhan -->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
</head>
<body>
     <!-- Page Wrapper -->
     <div id="wrapper">
        @include('layouts.sidebar3')
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
                    <h6 class="m-0 font-weight-bold text-primary">Report Data</h6>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                            Export to CSV
                        </button>
                </div>
                <div class="card-body">
                    <!-- Tombol untuk menuju halaman create -->



                    <div class="table-responsive">

                        <table class="table table-bordered" id="dataTable6" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Asset</th>
                                    <th>Pilot</th>
                                    <th>Tanggal</th>
                                    <th>Chronology</th>
                                    <th>Spareparts</th>
                                    <th>Damages</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report  ->asset->nama_barang}}</td>
                                        <td>{{ $report->pilot_name }}</td>
                                        <td>{{ $report->reported_at }}</td>
                                        <td>{{ $report->chronology }}</td>
                                        <td>
                                        @foreach($report->spareparts_details as $sparepart)
                                            <p>{{ $sparepart['nama_barang'] }} </p>
                                        @endforeach
                                        </td>
                                        <td>
                                            @if($damages = json_decode($report->damages))
                                                @foreach ($damages as $damage)
                                                    <p> Quantity: {{ $damage->quantity ?? 0 }} ({{ $damage->damage_part ?? 'N/A' }})</p>
                                                @endforeach
                                            @else
                                                <p>No damages reported</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                   
                            </tbody>
                        </table>
                        <a href="{{ route('reports.create') }}" class="btn btn-primary mb-3">Buat Laporan Baru</a>
                      
                    </div>
        </div>
    </div>
</div>

                   

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
                    <form action="{{ route('reports.exportCsv') }}" method="GET">
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
        $('#dataTable6').DataTable({
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