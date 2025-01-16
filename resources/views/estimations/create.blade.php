<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Drone Operation - Create Estimation</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.sidebar3')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.navigation')

                <div class="container mx-auto p-4">
                    <h1>Buat Estimasi Baru</h1>

                    <form id="estimation-form" method="POST" action="{{ route('estimations.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="has_report">Pilih Report:</label>
                            <select id="has_report" name="has_report" class="form-control">
                                <option value="1">Dengan Report</option>
                                <option value="0">Tanpa Report</option>
                            </select>
                        </div>

                        <div class="form-group" id="report-section">
                            <label for="report_id">Pilih Report</label>
                            <select id="report_id" name="report_id" class="form-control">
                                <option value="">-- Pilih Report --</option>
                                @foreach ($reports as $report)
                                    <option value="{{ $report->id }}">{{ $report->asset->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="asset-section" class="form-group d-none">
                            <label for="asset_id">Pilih Asset:</label>
                            <select id="asset_id" name="asset_id" class="form-control">
                                <option value="">Pilih Asset</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id_asset }}">{{ $asset->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sparepart Section --}}
                        <div id="spareparts-section" class="mt-4">
                            <label for="sparepart_select">Tambah Sparepart:</label>
                            <div class="d-flex align-items-center mb-3">
                            <select id="sparepart_select" class="form-control" style="width: 100%;">
                                    <option value="">Pilih Sparepart</option>
                                    @foreach ($sparepartsData as $sparepart)
                                        <option value="{{ $sparepart['id_sparepart'] }}" data-harga="{{ $sparepart['unit_price'] }}">
                                            {{ $sparepart['nama_sparepart'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" id="add-sparepart" class="btn btn-primary">Tambah</button>
                            </div>
                            <div id="spareparts-list"></div>
                            <div>
                                <strong>Total Quantity:</strong> <span id="total_quantity">0 pcs</span>
                            </div>
                            <div>
                                <strong>Total Price:</strong> <span id="total_price">Rp 0</span>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-success mt-4">Simpan Estimasi</button>
                    </form>
                </div>

                @include('layouts.footer')
            </div>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script>
        $('#sparepart_select').select2({
    placeholder: 'Cari Sparepart',
    allowClear: true,
    ajax: {
        url: '/spareparts/search', // URL untuk memuat data sparepart
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                query: params.term, // Term pencarian
            };
        },
        processResults: function (data) {
            return {
                results: data.map(sparepart => ({
                    id: sparepart.id_sparepart,
                    text: sparepart.nama_sparepart,
                })),
            };
        },
        cache: true,
    },
});

    </script>
    <script>
  $(document).ready(function () {
    const sparepartsSection = $('#spareparts-list');

    // Handle "Dengan Report" atau "Tanpa Report"
    $('#has_report').change(function () {
        const selectedOption = $(this).val();
        console.log('Option Report:', selectedOption);

        if (selectedOption === '1') {
            // Jika memilih "Dengan Report"
            $('#report_id').parent().removeClass('d-none'); // Tampilkan dropdown Report
            $('#asset-section').addClass('d-none'); // Sembunyikan dropdown Asset
            $('#asset_id').val(''); // Reset Asset
            sparepartsSection.empty(); // Bersihkan sparepart
            resetTotals();
        } else {
            // Jika memilih "Tanpa Report"
            $('#report_id').parent().addClass('d-none'); // Sembunyikan dropdown Report
            $('#asset-section').removeClass('d-none'); // Tampilkan dropdown Asset
            $('#report_id').val(''); // Reset Report
            sparepartsSection.empty(); // Bersihkan sparepart
            resetTotals();
        }
    });

    // Menambahkan sparepart secara manual
    $('#add_sparepart_button').click(function () {
        console.log('Tombol tambah sparepart diklik');

        const sparepartId = $('#sparepart_select').val();
        const quantity = parseInt($('#sparepart_quantity').val());

        console.log('Sparepart ID:', sparepartId, 'Quantity:', quantity);

        if (!sparepartId || isNaN(quantity) || quantity < 1) {
            alert('Pilih sparepart dan masukkan jumlah yang valid!');
            console.log('Input tidak valid:', { sparepartId, quantity });
            return;
        }

        // Ambil data harga satuan dari server
        $.ajax({
            url: '/estimations/get-price-quantity',
            method: 'GET',
            data: { sparepart_id: sparepartId },
            success: function (response) {
                console.log('Response dari server:', response);
                const unitPrice = response.unit_price;
                const sparepartName = response.sparepart.nama_sparepart;

                appendSparepart(sparepartId, sparepartName, quantity, unitPrice);
            },
            error: function (xhr, status, error) {
                console.log('AJAX Error:', status, error);
                console.log('Response:', xhr.responseText);
                alert('Gagal mengambil harga satuan.');
            }
        });
    });

    // Fungsi menambahkan sparepart ke dalam daftar
        function appendSparepart(id, name, quantity, unitPrice) {
            console.log('Menambahkan sparepart:', { id, name, quantity, unitPrice });

            if (sparepartAlreadyAdded(id)) {
                alert('Sparepart sudah ditambahkan!');
                console.log('Sparepart sudah ada di daftar:', id);
                return;
            }

            const row = `
                <tr class="sparepart-row" data-sparepart-id="${id}">
                    <td>${name}</td>
                    <td><input type="number" class="quantity form-control" value="${quantity}" min="1" /></td>
                    <td><input type="text" class="unit-price form-control" value="Rp${unitPrice.toLocaleString()}" disabled /></td>
                    <td><span class="subtotal form-control">Rp ${(quantity * unitPrice).toLocaleString()}</span></td>
                    <td><button type="button" class="remove-sparepart btn btn-danger">Remove</button></td>
                </tr>
            `;
            sparepartsSection.append(row);
            calculateTotals();
        }

        // Fungsi menghitung total harga dan total quantity
        function calculateTotals() {
            let totalPrice = 0;
            let totalQuantity = 0;

            $('.sparepart-row').each(function () {
                const quantity = parseInt($(this).find('.quantity').val()) || 0;
                const unitPrice = parseFloat($(this).find('.unit-price').val().replace(/[^0-9.]/g, '')) || 0;

                const subtotal = quantity * unitPrice;
                totalPrice += subtotal;
                totalQuantity += quantity;

                $(this).find('.subtotal').text('Rp ' + subtotal.toLocaleString());
            });

            $('#total_price').text('Rp ' + totalPrice.toLocaleString());
            $('#total_quantity').text(totalQuantity + ' pcs');
        }

        // Reset total harga dan quantity
        function resetTotals() {
            $('#total_price').text('Rp 0');
            $('#total_quantity').text('0 pcs');
        }

        // Cek jika sparepart sudah ada
        function sparepartAlreadyAdded(id) {
            return $(`.sparepart-row[data-sparepart-id="${id}"]`).length > 0;
        }

        // Event ketika quantity diubah
        $(document).on('input', '.quantity', function () {
            calculateTotals();
        });

        // Event menghapus sparepart
        $(document).on('click', '.remove-sparepart', function () {
            $(this).closest('.sparepart-row').remove();
            calculateTotals();
        });

        $('#add-sparepart').click(function () {
        console.log('Tombol tambah sparepart diklik');

        const sparepartId = $('#sparepart_select').val();
        const quantity = 1; // Set default quantity jika tidak ada input untuk quantity

        if (!sparepartId) {
            alert('Pilih sparepart!');
            return;
        }

        // Ambil data harga satuan dari server
        $.ajax({
            url: '/estimations/get-price-quantity',
            method: 'GET',
            data: { sparepart_id: sparepartId },
            success: function (response) {
                console.log('Response dari server:', response);

                const unitPrice = response.unit_price;
                const sparepartName = response.sparepart.nama_sparepart;

                appendSparepart(sparepartId, sparepartName, quantity, unitPrice);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                alert('Gagal mengambil harga satuan.');
            }
        });
    });

       // Event saat report dipilih
       $('#report_id').change(function () {
            const reportId = $(this).val();
            console.log('Report ID yang dipilih:', reportId);

            sparepartsSection.empty();
            resetTotals();

            if (reportId) {
                $.ajax({
                    url: '/estimations/get-data-by-report',
                    method: 'GET',
                    data: { report_id: reportId },
                    success: function (response) {
                        console.log('Spareparts dari report:', response.spareparts);
                        console.log('Asset ID dari report:', response.asset ? response.asset.id : 'No asset');
                        
                        // Jika ada asset, tampilkan asset_id di frontend
                        if (response.asset) {
                            $('#asset_id').val(response.asset.id); // Misalnya mengisi input hidden atau field asset_id
                            console.log('Asset ID:', response.asset.id);
                        }

                        response.spareparts.forEach(sparepart => {
                            appendSparepart(sparepart.id, sparepart.nama_barang, sparepart.quantity, sparepart.unit_price);
                        });
                    },
                    error: function () {
                        console.log('Gagal memuat data sparepart dari report');
                        alert('Gagal memuat data sparepart dari laporan.');
                    }
                });
            }
        });


        // Tambahkan sparepart ke hidden input sebelum submit form
        $('#estimation-form').submit(function () {
            const spareparts = [];
            $('.sparepart-row').each(function () {
                const id = $(this).data('sparepart-id');
                const quantity = parseInt($(this).find('.quantity').val()) || 0;

                if (id && quantity > 0) {
                    spareparts.push({ id, quantity });
                }
            });

            $('<input>').attr({
                type: 'hidden',
                name: 'spareparts',
                value: JSON.stringify(spareparts),
            }).appendTo('#estimation-form');

            const totalPrice = parseFloat($('#total_price').text().replace(/[^0-9.]/g, '')) || 0;
            $('<input>').attr({
                type: 'hidden',
                name: 'total_price',
                value: totalPrice,
            }).appendTo('#estimation-form');
        });

        $('#sparepart_select').select2({
        placeholder: 'Cari Sparepart',
        allowClear: true,
    });

    });



    </script>
</body>

</html>
