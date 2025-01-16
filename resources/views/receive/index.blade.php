<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Purchasing Receive</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('layouts.sidebar4')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                @include('layouts.navigation')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="text-2xl font-bold mb-4">Receive</h1>

                    <!-- Tombol untuk menampilkan form baru -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <!-- Bagian ini untuk kategori asset -->

                    <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Receive Data</h6>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                            Export to CSV
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No GR</th>
                                <th>Kategori</th>
                                <th>Kode Material</th>
                                <th>Nama Sparepart</th>
                                <th>NO PO</th>
                                <th>Jumlah Diminta</th>
                                <th>Jumlah Diterima</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Menampilkan daftar Receive -->
                            @foreach($receives as $receive)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $receive->gr_number }}</td>
                                    <td>{{ $receive->request->kategori }}</td>
                                    <td>
                                        @if($receive->request?->kategori === 'sparepart')
                                            {{ $receive->request->sparepart?->kode_material ?? '-' }}
                                        @elseif($receive->request?->kategori === 'asset')
                                            {{ $receive->request->kode_material ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($receive->request?->kategori === 'asset')
                                            {{ $receive->request->nama_asset ?? '-' }}
                                        @elseif($receive->request?->kategori === 'sparepart')
                                            {{ $receive->request->sparepart?->nama_sparepart ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $receive->request->no_po }}</td>
                                    <td>{{ $receive->request->quantity }}</td>
                                    <td>{{ $receive->received_quantity }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($receive->status === 'pending') badge-danger
                                            @elseif($receive->status === 'pending_delivered') badge-warning
                                            @elseif($receive->status === 'delivered') badge-success
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $receive->status)) }}
                                        </span>
                                    </td>
                                    <!-- Bagian Tombol untuk Menampilkan Form Modal -->
                                        <td>
                                            @if($receive->status !== 'delivered')
                                                @if($receive->request?->kategori === 'sparepart' || $receive->request?->kategori === 'asset')
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#receiveModal{{ $receive->id }}">
                                                        {{ $receive->status === 'pending' ? 'Pending Delivered' : 'Delivered' }}
                                                    </button>
                                                    <!-- Modal Form for Receive -->
                                                    <div class="modal fade" id="receiveModal{{ $receive->id }}" tabindex="-1" role="dialog" aria-labelledby="receiveModalLabel{{ $receive->id }}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="receiveModalLabel{{ $receive->id }}">
                                                                        {{ $receive->status === 'pending' ? 'Submit Pending Delivered' : 'Submit Delivered' }}
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{ route('receive.storeApproval', $receive->id) }}" method="POST">
                                                                        @csrf
                                                                        <input type="number" name="received_quantity" id="received_quantity_{{ $receive->id }}" data-id="{{ $receive->id }}" data-kategori="{{ $receive->request->kategori }}" placeholder="Jumlah Diterima" required class="form-control mb-2">
                                                                        
                                                                        <!-- Asset fields dynamic -->
                                                                        <div id="assetFields_{{ $receive->id }}"></div>

                                                                        <button type="submit" class="btn @if($receive->status === 'pending') btn-warning @elseif($receive->status === 'pending_delivered') btn-success @endif btn-sm">
                                                                            {{ $receive->status === 'pending' ? 'Submit Pending Delivered' : 'Submit Delivered' }}
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>


                                </tr>
                            @endforeach

                        </tbody>
                    </table>
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
                    <form action="{{ route('receive.exportCsv') }}" method="GET">
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



            <!-- Tambahkan ini sebelum penutupan </body> -->
    <script src="{{ asset('admin_assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('admin_assets/js/demo/datatables-demo.js')}}"></script>

    
        <script>

            
document.addEventListener("DOMContentLoaded", function () {
    // Cari semua input dengan nama "received_quantity"
    const inputs = document.querySelectorAll('input[name="received_quantity"]');

    // Tambahkan event listener untuk menangani perubahan nilai
            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    const id = this.dataset.id; // Ambil ID dari atribut data-id
                    const kategori = this.dataset.kategori; // Ambil kategori dari atribut data-kategori
                    const value = parseInt(this.value); // Ambil nilai input sebagai integer

                    // Panggil fungsi untuk membuat form dinamis di dalam modal
                    generateDynamicFields(id, kategori, value);
                });
            });
        });

        function generateDynamicFields(id, kategori, value) {
            // Pilih elemen tempat menampilkan form (dalam modal)
            const assetFieldsContainer = document.querySelector(`#assetFields_${id}`);

            // Kosongkan form sebelumnya
            assetFieldsContainer.innerHTML = '';

            // Jika kategori bukan 'asset' atau nilai <= 0, tidak ada form yang dibuat
            if (kategori !== 'asset' || value <= 0) return;

            // Buat form sesuai jumlah barang diterima
            for (let i = 1; i <= value; i++) {
                const form = `
                    <div class="mb-3">
                        <label for="asset_nama_${id}_${i}">Nama Asset ${i}</label>
                        <input type="text" id="asset_nama_${id}_${i}" name="assets[${i}][nama]" class="form-control" required>

                        <label for="asset_jenis_${id}_${i}">Jenis Asset ${i}</label>
                        <input type="text" id="asset_jenis_${id}_${i}" name="assets[${i}][jenis]" class="form-control" required>

                        <label for="asset_manufacture_${id}_${i}">Manufacture ${i}</label>
                        <input type="text" id="asset_manufacture_${id}_${i}" name="assets[${i}][manufacture]" class="form-control" required>

                        <label for="asset_tahun_${id}_${i}">Tahun ${i}</label>
                        <input type="text" id="asset_tahun_${id}_${i}" name="assets[${i}][tahun]" class="form-control" required>
                    </div>
                `;
                assetFieldsContainer.innerHTML += form;
            }
        }


        $(document).ready(function() {
        $('#dataTable2').DataTable({
            "paging": true,  // Enable pagination
            "lengthChange":true,  // Disable changing the number of items per page
            "searching": true,  // Enable search
            "ordering": true,  // Enable column sorting
            "info": true,  // Show table info
            "autoWidth": false  // Disable auto width adjustment
        });
    });
    </script>

    





</body>

</html>
