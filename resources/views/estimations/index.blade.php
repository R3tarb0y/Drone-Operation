<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Drone Operation - Estimation</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    @include('layouts.sidebar3')

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navigation')   
            <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Estimasi</h6>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                            Export to CSV
                        </button>

                <div class="card-body">
          
                    <div class="table-responsive">

      
                        <table class="table table-bordered" id="dataTable7" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Asset</th>
                                <th>Report</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Spareparts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estimations as $estimation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $estimation->asset->nama_barang }}</td>
                                    <td>{{ $estimation->report->id ?? 'No Report' }}</td>
                                    <td>{{ 'Rp ' . number_format($estimation->total_cost, 0, ',', '.') }}</td>
                                   <td>
                                    <span class=" 
                                        @if($estimation->status === 'pending') bg-danger text-white 
                                        @elseif($estimation->status === 'update') bg-warning text-dark
                                        @elseif($estimation ->status === 'approved') bg-success text-white
                                        @endif 
                                        px-2 py-1 rounded">
                                        {{ ucfirst($estimation->status) }}
                                    </span>
                                   </td>
                                    <td>
                                        @if (json_decode($estimation->spareparts))
                                            <ul>
                                            @foreach (json_decode($estimation->spareparts, true) as $sparepart)
                                                <li>{{ $sparepart['nama_barang'] }} - {{ $sparepart['quantity'] }}</li>
                                            @endforeach

                                            </ul>
                                        @else
                                            No Spareparts
                                        @endif
                                    </td>
                                    <td>
                                        @if ($estimation->status == 'update')
                                            <form action="{{ route('estimations.approve', $estimation->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                        @elseif ($estimation->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <form action="{{ route('estimations.updateStatus', $estimation->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateModal-{{ $estimation->id }}">Update</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('estimations.create') }}" class="btn btn-primary mb-3">Buat Estimasi Baru</a>
            </div>
        </div>
    </div>
</div>

            @foreach ($estimations as $estimation)
                <div class="modal fade" id="updateModal-{{ $estimation->id }}" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel-{{ $estimation->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel-{{ $estimation->id }}">Update Spareparts for Estimation #{{ $estimation->id }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="updateForm-{{ $estimation->id }}" method="POST" action="{{ route('estimations.updateSpareparts', $estimation->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group">
                                        <label for="spareparts">Spareparts</label>
                                        <table class="table table-bordered" id="spareparts-table-{{ $estimation->id }}" data-estimation-id="{{ $estimation->id }}">
                                            <thead>
                                                <tr>
                                                    <th>Sparepart</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach (json_decode($estimation->spareparts, true) as $sparepart)
                                            <tr data-sparepart-id="{{ $sparepart['id_sparepart'] }}">
                                                <td>
                                                    <select name="spareparts[{{ $sparepart['id_sparepart'] }}][sparepart_id]" class="form-control sparepart-select">
                                                        <!-- Add sparepart options dynamically -->
                                                        <option value="{{ $sparepart['id_sparepart'] }}" selected>{{ $sparepart['nama_barang'] }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="spareparts[{{ $sparepart['id_sparepart'] }}][quantity]" 
                                                        value="{{ $sparepart['quantity'] }}" class="form-control update-quantity" 
                                                        data-unit-price="{{ $sparepart['unit_price'] }}">
                                                </td>
                                                <td>{{ number_format($sparepart['unit_price'], 2) }}</td>
                                                <td>
                                                    <input type="number" class="form-control total-price" 
                                                        value="{{ $sparepart['quantity'] * $sparepart['unit_price'] }}" readonly>
                                                </td>
                                                <td> 
                                                <button type="button" class="btn btn-danger btn-sm remove-sparepart">Remove</button>
                                            </td>
                                            </tr>
                                        @endforeach

                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-success btn-sm add-sparepart" data-estimation-id="{{ $estimation->id }}">Add Sparepart</button>

                                    </div>

                                    <div class="form-group">
                                        <label for="total_cost">Total Cost</label>
                                        <input type="number" name="total_cost" id="total-cost-{{ $estimation->id }}" class="form-control total-cost" 
                                               value="{{ $estimation->total_cost }}" readonly>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" form="updateForm-{{ $estimation->id }}" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

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
                        <form action="{{ route('estimations.exportCsv') }}" method="GET">
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

<script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
<script src="{{ asset('admin_assets/js/sb-admin-2.min.js')}}"></script>

<script>

$(document).on('click', '.add-sparepart', function () {
    const estimationId = $(this).data('estimation-id');
    const modal = $(this).closest('.modal');
    const table = modal.find('table#spareparts-table-' + estimationId);

    // Add a new row to the table with dynamic sparepart options
    const newRow = `<tr>
    <td>
        <select name="spareparts[${table.find('tbody tr').length}][sparepart_id]" class="form-control sparepart-select">
            <option value="">Select Sparepart</option>
            @foreach ($sparepartsData as $sparepart)
                <option value="{{ $sparepart['id_sparepart'] }}" data-harga="{{ $sparepart['unit_price'] }}">
                    {{ $sparepart['nama_sparepart'] }}
                </option>
            @endforeach
        </select>
    </td>
    <td><input type="number" name="spareparts[${table.find('tbody tr').length}][quantity]" class="form-control quantity" value="1"></td>
    <td><input type="text" class="form-control unit-price" readonly></td>
    <td><input type="number" class="form-control subtotal" readonly></td>
    <td><button type="button" class="btn btn-danger btn-sm remove-sparepart">Remove</button></td>
</tr>`;



    table.find('tbody').append(newRow);
});

// Handle change in quantity or sparepart selection
$(document).on('change', '.sparepart-select, .quantity', function () {
    const row = $(this).closest('tr');
    const quantity = parseFloat(row.find('.quantity').val()) || 0;
    const sparepartId = row.find('.sparepart-select').val();

    if (sparepartId) {
        // Fetch unit price and update row
        $.ajax({
            url: '/estimations/get-price-quantity',
            type: 'GET',
            data: { sparepart_id: sparepartId },
            success: function (data) {
                if (data.unit_price !== undefined) {
                    const unitPrice = parseFloat(data.unit_price);
                    row.find('.unit-price').val('Rp ' + unitPrice.toLocaleString());

                    const subtotal = quantity * unitPrice;
                    row.find('.subtotal').val(subtotal.toFixed(2));

                    updateTotalCost(row.closest('table')); // Update total cost after change
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching unit price:', error);
            }
        });
    }
});

function updateTotalCost(table) {
    let totalCost = 0;

    // Calculate total cost by adding all the subtotals in the table
    table.find('.subtotal').each(function () {
        totalCost += parseFloat($(this).val()) || 0;
    });

    const estimationId = table.data('estimation-id');
    $('#total-cost-' + estimationId).val(totalCost.toFixed(2));
}

$(document).on('click', '.remove-sparepart', function () {
    const row = $(this).closest('tr');
    const sparepartId = row.find('.sparepart-select').val(); // Get sparepart ID
    const estimationId = $(this).closest('table').data('estimation-id'); // Get estimation ID

            $.ajax({
            url: `/estimations/${estimationId}/remove-sparepart`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sparepart_id: sparepartId,
            },
            success: function (response) {
                if (response.success) {
                    row.remove();
                    updateTotalCost(row.closest('table'));
                } else {
                    console.error("Error: " + response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error removing sparepart:', error);
                console.log(xhr.responseText); // Log the full response for debugging
            }
        });


});








</script>

<script src="{{ asset('admin_assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('admin_assets/js/demo/datatables-demo.js')}}"></script>
    <script>
            $(document).ready(function() {
        // Initialize DataTables
        $('#dataTable7').DataTable({
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
