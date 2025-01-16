<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Purchasing  Request</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">


    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>
    
<body id="page-top">
    <div id="wrapper">
        @include('layouts.sidebar4')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
            @include('layouts.navigation')


            <div class="container-fluid">
    

                    <!-- Tombol untuk menampilkan form baru -->
                    <button id="toggleRequestFormButton" class="btn btn-primary mb-3">Tambah Request Baru</button>

                        <!-- Form Request (initially hidden) -->
                        <div id="requestForm" style="display: none;">
                            <form action="{{ route('request.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select class="form-control" name="kategori" id="kategori">
                                            <option value="asset">Asset</option>
                                            <option value="sparepart">Sparepart</option>
                                        </select>
                                    </div>

                                    <!-- Form Sparepart -->
                                    <div id="sparepart-section" style="display: none;">
                                        <h3>Daftar Sparepart</h3>
                                        <table id="sparepart-table" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sparepart</th>
                                                    <th>Jumlah</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" id="filterInput" placeholder="Cari Sparepart..." class="form-control mb-2" />
                                                        <select name="spareparts[0][id]" id="sparepartDropdown" class="form-control" size="5" required>
                                                            @foreach($spareparts as $sparepart)
                                                                <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->id_sparepart }} - {{ $sparepart->nama_sparepart }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                    <label for="quantity">Quantity:</label>
                                                    <input type="number" name="spareparts[0][quantity]" required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-primary" onclick="addRow()">Tambah Sparepart</button>
                                    </div>

                                    <!-- Field Nomor PP -->
                                    <div id="no-pp-container" style="display: none;">
                                        <label for="no_pp">Nomor PP</label>
                                        <input type="text" class="form-control" name="no_pp" id="no_pp" placeholder="Isi Nomor PP">
                                    </div>

                                    <!-- Form Asset -->
                                    <div id="assetForm" style="display: none;">
                                        <div class="form-group">
                                            <label for="kode_material">Kode Material</label>
                                            <input type="text" class="form-control" name="kode_material" id="kode_material" placeholder="Isi kode material">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_asset">Nama Asset</label>
                                            <input type="text" class="form-control" name="nama_asset" id="nama_asset" placeholder="Isi nama asset">
                                        </div>
                                        <div class="form-group">
                                        <label for="quantity">Jumlah</label>
                                        <input type="number" class="form-control" name="quantity" id="quantity" required>
                                    </div>
                                    </div>

                                    <!-- Gudang dan Keterangan -->
                                    <div class="form-group">
                                        <label for="warehouse_id">Pilih Gudang:</label>
                                        <select name="warehouse_id" id="warehouse_id" class="form-control">
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success">Submit</button>
                                </form>
                        </div>


   
                <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Requests Data</h6>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                Export to CSV
            </button>
        </div>
        <div class="card-body">

            <div class="table-responsive">
       

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Material</th>
                    <th>Kategori</th>
                    <th>No PP</th>
                    <th>No PO</th>
                    <th>Sumber Dana</th>
                    <th>Nama Sparepart</th>
                    <th>Gudang</th>
                    <th>Jumlah</th>
                    <th>Unit Price</th>
                    <th>Net Price</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($request->kategori === 'sparepart')
                                    {{ $request->sparepart?->kode_material ?? '-' }}
                                @elseif($request->kategori === 'asset')
                                    {{ $request->kode_material ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $request->kategori }}</td>
                            <td>{{ $request->no_pp }}</td>
                            <td>{{ $request->no_po }}</td>
                            <td>{{ $request->sumberdana }}</td>
                            <td>
                                @if($request->kategori === 'asset')
                                    {{ $request->nama_asset ?? '-' }}
                                @elseif($request->kategori === 'sparepart')
                                    {{ $request->sparepart?->nama_sparepart ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $request->warehouse->name }}</td>
                            <td>{{ $request->quantity }}</td>
                            <td>{{ 'Rp ' . number_format($request->unit_price, 0, ',', '.') }}</td>
                            <td>{{ 'Rp ' . number_format($request->price, 0, ',', '.') }}</td>
                            <td>{{ $request->tanggal_request }}</td>
                            <td>
                                <span class=" 
                                    @if($request->status === 'pending') bg-danger text-white 
                                    @elseif($request->status === 'pp') bg-warning text-dark
                                    @elseif($request->status === 'po') bg-warning text-dark
                                    @elseif($request->status === 'approved') bg-success text-white
                                    @endif 
                                    px-2 py-1 rounded">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                @if($request->status === 'pending')
                                    <!-- Button to show PP form -->
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#poModal{{ $request->id }}">PO</button>
                                    <div class="modal fade" id="poModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="poModalLabel{{ $request->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="poModalLabel{{ $request->id }}">PP Form</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('request.updateStatus', $request->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')

                                                        <!-- PO Number Field -->
                                                        <div class="form-group">
                                                            <label for="no_pp">Nomor PP</label>
                                                            <input type="text" name="no_pp" placeholder="Masukkan No PP" required class="form-control">
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Submit PP</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($request->status === 'pp')
                                    <!-- Button to show PO form -->
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#poModal{{ $request->id }}">PO</button>
                                    <div class="modal fade" id="poModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="poModalLabel{{ $request->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="poModalLabel{{ $request->id }}">PO Form</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('request.updateStatus', $request->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')

                                                        <!-- PO Number Field -->
                                                        <!-- Form untuk mengisi PO -->
                                                            <div class="form-group">
                                                                <label for="no_po">Nomor PO</label>
                                                                <input type="text" name="no_po" id="no_po" class="form-control" required>
                                                            </div>

                                                            <!-- Dropdown untuk memilih sumberdana -->
                                                            <div class="form-group">
                                                                <label for="sumberdana">Sumber Dana</label>
                                                                <select name="sumberdana" id="sumberdana" class="form-control" required>
                                                                    <option value="Capex">Capex</option>
                                                                    <option value="Opex">Opex</option>
                                                                </select>
                                                            </div>

                                                            <!-- Form untuk harga dan vendor -->
                                                            <div class="form-group">
                                                                <label for="price">Harga</label>
                                                                <input type="number" name="price" id="price" class="form-control" min="1">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="vendor">Vendor</label>
                                                                <input type="text" name="vendor" id="vendor" class="form-control">
                                                            </div>

                                                        <button type="submit" class="btn btn-warning">Submit PO</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($request->status === 'po')
                                    <!-- Button to show Approve form -->
                                    <button class="btn btn-success" data-toggle="collapse" data-target="#approveForm{{ $request->id }}">Approve</button>
                                        <div id="approveForm{{ $request->id }}" class="collapse">
                                            <form action="{{ route('request.approve', $request->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                        </div>
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

    <!-- JavaScript untuk menampilkan/menghilangkan form -->


        <script>

        // Panggil fungsi awal untuk menyesuaikan saat halaman dimuat


        const toggleButton = document.getElementById('toggleRequestFormButton');
        const requestForm = document.getElementById('requestForm');

        document.getElementById('toggleRequestFormButton').addEventListener('click', function() {
            const requestForm = document.getElementById('requestForm');
            // Toggle the display of the form
            if (requestForm.style.display === 'none' || requestForm.style.display === '') {
                requestForm.style.display = 'block'; // Show the form
            } else {
                requestForm.style.display = 'none'; // Hide the form
            }
        });

        
        function updateKodeMaterial() {
        var sparepartSelect = document.getElementById('sparepart_id');
        var kodeMaterialInput = document.getElementById('kode_material');
        var selectedOption = sparepartSelect.options[sparepartSelect.selectedIndex];
        
        // Set kode material berdasarkan data-kode yang ada di option
        kodeMaterialInput.value = selectedOption ? selectedOption.getAttribute('data-kode') : '';
    }

    // Event listener untuk kategori perubahan
        // Event listener for category change
        document.getElementById('kategori').addEventListener('change', function() {
            const kategori = this.value;

            const sparepartSection = document.getElementById('sparepart-section');
            const assetForm = document.getElementById('assetForm');
            const noPPContainer = document.getElementById('no-pp-container');

            // Input fields
            const quantityInput = document.getElementById('quantity'); // Input jumlah pada asset
            const sparepartInputs = document.querySelectorAll('#sparepart-section input, #sparepart-section select');

            if (kategori === 'sparepart') {
                sparepartSection.style.display = 'block';
                assetForm.style.display = 'none';
                noPPContainer.style.display = 'block';

                // Tambahkan required pada sparepart fields
                sparepartInputs.forEach(input => input.setAttribute('required', 'required'));
                // Hapus required pada asset fields
                quantityInput.removeAttribute('required');
            } else if (kategori === 'asset') {
                sparepartSection.style.display = 'none';
                assetForm.style.display = 'block';
                noPPContainer.style.display = 'none';

                // Hapus required pada sparepart fields
                sparepartInputs.forEach(input => input.removeAttribute('required'));
                // Tambahkan required pada asset fields
                quantityInput.setAttribute('required', 'required');
            }
        });

        // Panggil fungsi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const kategori = document.getElementById('kategori').value;

            // Trigger perubahan kategori untuk sinkronisasi awal
            document.getElementById('kategori').dispatchEvent(new Event('change'));
        });






        document.addEventListener('DOMContentLoaded', function () {
            function toggleNoPPField() {
                const kategori = document.getElementById('kategori').value;
                const noPPContainer = document.getElementById('no-pp-container');
                const noPPInput = document.getElementById('no_pp');

                if (kategori === 'sparepart') {
                    noPPContainer.style.display = 'block'; // Tampilkan input no_pp
                    noPPInput.removeAttribute('disabled'); // Aktifkan input
                    noPPInput.setAttribute('required', 'required'); // Jadikan input required
                } else {
                    noPPContainer.style.display = 'none'; // Sembunyikan input no_pp
                    noPPInput.setAttribute('disabled', 'disabled'); // Nonaktifkan input
                    noPPInput.removeAttribute('required'); // Hapus atribut required
                    noPPInput.value = ''; // Reset nilai input
                }
            }

            // Toggle field ketika kategori berubah
            document.getElementById('kategori').addEventListener('change', toggleNoPPField);

            // Panggil fungsi awal saat halaman dimuat
            toggleNoPPField();
        });



        // Function to filter sparepart options based on user input
        function filterOptions() {
            const input = document.getElementById('kode_material_input').value.toLowerCase();
            const select = document.getElementById('kode_material_select');
            const options = select.options;

            for (let i = 1; i < options.length; i++) { // Start from 1 to skip placeholder
                const text = options[i].text.toLowerCase();
                if (text.includes(input)) {
                    options[i].style.display = ''; // Show option
                } else {
                    options[i].style.display = 'none'; // Hide option
                }
            }
        }   


        document.querySelectorAll('.pp-submit').forEach(button => {
            button.addEventListener('click', function() {
                // Cek jika kategori adalah sparepart dan no_pp diisi
                const ppNumber = document.getElementById('no_pp').value;
                const kategori = document.querySelector('select[name="kategori"]').value; // Asumsi kategori ada di dropdown select

                if (kategori === 'sparepart' && ppNumber) {
                    // Jika kategori sparepart dan no_pp diisi, submit form
                    const form = this.closest('form');
                    form.submit(); // Kirim form untuk memperbarui status ke 'pp'
                } else if (kategori === 'asset') {
                    // Jika kategori asset, jangan meminta no_pp
                    alert('Kategori Asset tidak memerlukan nomor PP.');
                } else {
                    // Jika tidak ada no_pp untuk sparepart, tampilkan peringatan
                    alert('Nomor PP harus diisi untuk kategori Sparepart.');
                }
            });
        });
        

     // Global index untuk baris sparepart
    let sparepartIndex = 1;

        // Fungsi untuk menampilkan/menghilangkan bagian form berdasarkan kategori
        function toggleForm() {
            const kategori = document.getElementById('kategori').value;
            const sparepartSection = document.getElementById('sparepart-section');
            const assetForm = document.getElementById('assetForm');
            const noPPContainer = document.getElementById('no-pp-container');

            if (kategori === 'sparepart') {
                sparepartSection.style.display = 'block';
                assetForm.style.display = 'none';
                noPPContainer.style.display = 'block';
            } else if (kategori === 'asset') {
                sparepartSection.style.display = 'none';
                assetForm.style.display = 'block';
                noPPContainer.style.display = 'none';
            }
        }

// Fungsi untuk menambahkan baris baru di tabel sparepart
    function addRow() {
        const tableBody = document.getElementById('sparepart-table').getElementsByTagName('tbody')[0];
        const newRow = tableBody.insertRow();
        const uniqueId = `row-${sparepartIndex}`;
        newRow.innerHTML = `
            <td>
              <input type="text" placeholder="Cari Sparepart..." class="form-control mb-2 filterInput" data-id="${uniqueId}" />
                <select name="spareparts[${sparepartIndex}][id]" class="form-control sparepartDropdown" id="${uniqueId}" size="5" required>
                    @foreach($spareparts as $sparepart)
                        <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->id_sparepart }} - {{ $sparepart->nama_sparepart }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="spareparts[${sparepartIndex}][quantity]" min="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button>
            </td>
        `;
        sparepartIndex++;
        }

        document.addEventListener('keyup', function (event) {
        if (event.target.classList.contains('filterInput')) {
            const filterValue = event.target.value.toLowerCase();
            const dropdownId = event.target.getAttribute('data-id');
            const dropdown = document.getElementById(dropdownId);
            const options = dropdown.options;

            for (let i = 0; i < options.length; i++) {
                const optionText = options[i].text.toLowerCase();
                options[i].style.display = optionText.includes(filterValue) ? '' : 'none';
            }
        }
    });

    document.addEventListener('input', function (event) {
        if (event.target.classList.contains('filterInput')) {
            const dropdownId = event.target.getAttribute('data-id');
            const dropdown = document.getElementById(dropdownId);
            const options = Array.from(dropdown.options).filter(option => option.style.display !== 'none');
            if (options.length === 1) {
                dropdown.value = options[0].value;
            }
        }
    });

    // Fungsi untuk menghapus baris di tabel sparepart
    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
    }

    // Panggil toggleForm saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', toggleForm);

    // Event listener untuk perubahan kategori
    document.getElementById('kategori').addEventListener('change', toggleForm);

             // Event listener for toggling the form visibility in dropdown
             document.querySelectorAll('.dropdown-toggle').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Get the ID of the form to toggle
                    const formId = this.getAttribute('aria-expanded') === 'false' ? this.nextElementSibling.querySelector('form').id : null;
                    if (formId) {
                        $('#' + formId).collapse('toggle');  // Bootstrap's collapse function to toggle the form visibility
                    }
                });
            });


    </script>


<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,  // Enable pagination
            "lengthChange": false,  // Disable changing the number of items per page
            "searching": true,  // Enable search
            "ordering": true,  // Enable column sorting
            "info": true,  // Show table info
            "autoWidth": false  // Disable auto width adjustment
        });
    });
</script>


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
                <form action="{{ route('request.export.csv') }}" method="GET">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Tanggal Selesai:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>
                    <div class="form-group">
                        <label for="sumberdana">Sumber Dana:</label>
                        <select name="sumberdana" id="sumberdana" class="form-control">
                            <option value="">Semua</option>
                            <option value="Capex" {{ old('sumberdana') == 'Capex' ? 'selected' : '' }}>Capex</option>
                            <option value="Opex" {{ old('sumberdana') == 'Opex' ? 'selected' : '' }}>Opex</option>
                        </select>
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

</body>

</html>

