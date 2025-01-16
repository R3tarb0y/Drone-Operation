<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Transfer</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
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
                <div class="container mx-auto p-4">
                    <h2 class="text-lg font-semibold mb-4 text-center">Form Transfer Spareparts</h2>

    <form action="{{ route('transfer.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="sparepart_id">Pilih Sparepart</label>
            <input type="text" id="filterInput" placeholder="Cari Sparepart..." class="form-control mb-2" />
            <select name="sparepart_id" id="sparepartDropdown" class="form-control select2" required>
                <option value="">-- Pilih Sparepart --</option>
                @foreach($spareparts as $sparepart)
                    <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->id_sparepart }} - {{ $sparepart->nama_sparepart }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="gudang_pengirim">Gudang Pengirim</label>
            <select name="gudang_pengirim" class="form-control" required>
                <option value="">-- Pilih Gudang Pengirim --</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="gudang_penerima">Gudang Penerima</label>
            <select name="gudang_penerima" class="form-control" required>
                <option value="">-- Pilih Gudang Penerima --</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah_barang">Jumlah Barang</label>
            <input type="number" name="jumlah_barang" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" name="keterangan" class="form-control">
        </div>

        <div class="form-group">
            <label for="nama_pengguna">Nama Pengguna</label>
            <input type="text" name="nama_pengguna" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>

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

    <script>
    document.getElementById('filterInput').addEventListener('keyup', function() {
        let filterValue = this.value.toLowerCase();
        let dropdown = document.getElementById('sparepartDropdown');
        let options = dropdown.options;

        // Tampilkan opsi yang cocok
        for (let i = 0; i < options.length; i++) {
            let optionText = options[i].text.toLowerCase();
            if (optionText.includes(filterValue)) {
                options[i].style.display = ''; // Tampilkan opsi yang cocok
            } else {
                options[i].style.display = 'none'; // Sembunyikan opsi yang tidak cocok
            }
        }
    });

    // Secara otomatis memilih opsi pertama jika hanya satu yang cocok
    document.getElementById('filterInput').addEventListener('input', function() {
        let dropdown = document.getElementById('sparepartDropdown');
        let options = Array.from(dropdown.options).filter(option => option.style.display !== 'none');
        if (options.length === 1) {
            dropdown.value = options[0].value;
        }
    });
</script>


    </body>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const sparepartSelect = document.getElementById('sparepartDropdown');
    const gudangPengirimSelect = document.querySelector('select[name="gudang_pengirim"]');
    const stokDisplay = document.createElement('div');
    stokDisplay.classList.add('mt-2', 'alert', 'alert-info');
    stokDisplay.textContent = 'Stok: -';
    gudangPengirimSelect.closest('.form-group').appendChild(stokDisplay);

    async function fetchStok() {
        const sparepartId = sparepartSelect.value;
        const warehouseId = gudangPengirimSelect.value;

        if (!sparepartId || !warehouseId) {
            stokDisplay.textContent = 'Stok: -';
            return;
        }

        try {
            const response = await fetch(`{{ route('transfer.getStok') }}?sparepart_id=${sparepartId}&warehouse_id=${warehouseId}`);
            const data = await response.json();
            stokDisplay.textContent = `Stok: ${data.stok}`;
        } catch (error) {
            stokDisplay.textContent = 'Stok: Error';
            console.error('Error fetching stok:', error);
        }
    }

    sparepartSelect.addEventListener('change', fetchStok);
    gudangPengirimSelect.addEventListener('change', fetchStok);
});

</script>

 </body>
</html>