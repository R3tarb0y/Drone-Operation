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
                <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mb-4">Spareparts - Report </h1>
                <form action="{{ route('reports.store') }}" method="POST">
                @csrf

                        <!-- Pilih Drone -->
                        <div class="form-group">
                            <label for="asset_id">Drone</label>
                            <select name="asset_id" id="asset_id" class="form-control">
                                <option value="" disabled selected>Select Drone</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id_asset }}" {{ old('asset_id') == $asset->id_asset ? 'selected' : '' }}>
                                        {{ $asset->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilot Name -->
                        <div class="form-group">
                            <label for="pilot_name">Pilot Name</label>
                            <input type="text" name="pilot_name" id="pilot_name" class="form-control" value="{{ old('pilot_name') }}">
                        </div>

                        <!-- Chronology -->
                        <div class="form-group">
                            <label for="chronology">Chronology</label>
                            <textarea name="chronology" id="chronology" class="form-control">{{ old('chronology') }}</textarea>
                        </div>

                        <!-- Section for adding spareparts dynamically -->
                        <div id="spareparts-container">
                            <!-- Sparepart Item Template -->
                        </div>

                        <button type="button" id="add-sparepart-btn" class="btn btn-secondary">Add Sparepart</button>

                        <button type="submit" class="btn btn-primary mt-3">Submit Report</button>
                    </form>
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
    <script>
        // When spareparts selection changes
        document.getElementById('add-sparepart-btn').addEventListener('click', function () {
            const container = document.getElementById('spareparts-container');
            const sparepartIndex = container.childElementCount; // Get current index for naming
            const uniqueId = `row-${sparepartIndex}`;

            const sparepartTemplate = `
                <div class="sparepart-item">
                    <hr>
                    <div class="form-group">
                       <input type="text" placeholder="Cari Sparepart..." class="form-control mb-2 filterInput" data-id="${uniqueId}" />
                        <select name="spareparts[${sparepartIndex}][id]" class="form-control sparepartDropdown" id="${uniqueId}" size="5" required>
                            @foreach($spareparts as $sparepart)
                                <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->id_sparepart }} - {{ $sparepart->nama_sparepart }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="spareparts[${sparepartIndex}][quantity]">Quantity</label>
                        <input type="number" name="spareparts[${sparepartIndex}][quantity]" class="form-control" placeholder="Enter quantity">
                    </div>
                    <div class="form-group">
                        <label for="spareparts[${sparepartIndex}][damage_part]">Damage Part</label>
                        <input type="text" name="spareparts[${sparepartIndex}][damage_part]" class="form-control" placeholder="Enter part (e.g., left, right)">
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', sparepartTemplate);
        });


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

    </script>
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
