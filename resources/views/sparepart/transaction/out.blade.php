<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sparepart - Out</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body>
<div id="wrapper">
        @include('layouts.sidebar3')
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
            @include('layouts.navigation')
               
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Spareparts - OUT </h1>

    <!-- Tombol untuk trigger modal -->
    <button class="btn btn-warning mb-3" data-toggle="modal" data-target="#addSparepartOutModal">
        Kurangi Sparepart
    </button>

    <!-- Modal untuk Out Barang -->
    <div class="modal fade" id="addSparepartOutModal" tabindex="-1" role="dialog" aria-labelledby="addSparepartOutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('sparepart.transaction.out.submit') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSparepartOutModalLabel">Kurangi Sparepart</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Pilihan Sparepart -->
                        <div class="form-group">
                            <label for="sparepart_id">Pilih Sparepart</label>
                            <select name="sparepart_id" id="sparepart_id" class="form-control" required>
                                @foreach ($spareparts as $sparepart)
                                    <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->nama_sparepart }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilihan Asset -->
                        <div class="form-group">
                            <label for="asset_id">Pilih Asset</label>
                            <select name="asset_id" id="asset_id" class="form-control" required>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id_asset }}">
                                        {{ $asset->nama_barang }} ({{ $asset->serial_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jumlah Barang -->
                        <div class="form-group">
                            <label for="quantity">Jumlah</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nama Sparepart</th>
            <th>Asset Terkait</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
        </tr>
        </thead>
        <tbody>
                @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->sparepart->nama_sparepart }}</td>
                <td>{{ $transaction->asset->nama_barang }}</td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ $transaction->created_at }}</td>
                
            </tr>
        @endforeach
        </tbody>
    </table>
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


</body>
</html>
