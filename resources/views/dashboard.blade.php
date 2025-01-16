<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Dashboard</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.navigation') <!-- Navbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">


                <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                    <!-- <label for="year" class="form-label">Filter Tahun:</label> -->
                    <select name="year" id="year" class="form-control w-auto d-inline">
                        @foreach (range(Carbon\Carbon::now()->year, 2000) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="exportButton"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            </div>

            @php
            function formatRupiah($number) {
                return 'Rp ' . number_format($number, 0, ',', '.');
            }
            @endphp
    
            <div class="row" id="chartContainer">

                    <!-- Request Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Requests</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRequests }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Receive Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Receives</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReceives }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Reports</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReports }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Total Maintenance</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMaintenance }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                        <!-- Area Chart -->
                        <!-- Earnings Overview Chart -->
                        <div class="col-xl-8 col-lg-7 mb-4">
                    <div class="card shadow mb-4">
                        <div id="earningsCapex" data-earnings="{{ json_encode($earningsCapex) }}">
                        <div id="earningsOpex" data-earnings="{{ json_encode($earningsOpex) }}">
                        <div id="earningsData" data-earnings="{{ json_encode($earningsData) }}">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Pengeluaran </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="myAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Opex and Capex Cards -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="row">
                        <!-- Opex Card -->
                        <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Opex Expenditure</div>
                                    @php
                                        $opexClass = $totalOpexUsed > $totalOpex ? 'text-danger' : 'text-success';
                                        $opexSign = $totalOpexUsed > $totalOpex ? '-' : '+';
                                    @endphp
                                    <div class="h5 mb-0 font-weight-bold {{ $opexClass }}">
                                        {{ $opexSign }}{{ formatRupiah($totalOpexRemaining) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Capex Card -->
                        <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Capex Expenditure</div>
                                    @php
                                        $capexClass = $totalCapexUsed > $totalCapex ? 'text-danger' : 'text-success';
                                        $capexSign = $totalCapexUsed > $totalCapex ? '-' : '+';
                                    @endphp
                                    <div class="h5 mb-0 font-weight-bold {{ $capexClass }}">
                                        {{ $capexSign }}{{ formatRupiah($totalCapexRemaining) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div id="revenueSources" data-sources="{{ json_encode($revenueSources) }}">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Budget Breakdown (Capex)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-pie pt-4 pb-2">
                                            <canvas id="myPieChartCapex"></canvas>
                                        </div>
                                        <div class="text-xs font-weight-bold text-primary">Capex Utilization: {{ number_format($percentageCapex, 2) }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div id="revenueSources" data-sources="{{ json_encode($revenueSources) }}">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Budget Breakdown (Opex)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-pie pt-4 pb-2">
                                            <canvas id="myPieChartOpex"></canvas>
                                        </div>
                                        <div class="text-xs font-weight-bold text-success">Opex Utilization: {{ number_format($percentageOpex, 2) }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>


          

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

    <!-- Page level custom scripts -->


    <script>
    // Mengambil data dari HTML element
    var earningsDataElement = document.getElementById('earningsData');
    var revenueSourcesElement = document.getElementById('revenueSources');
    var earningsCapexElement = document.getElementById('earningsCapex');
    var earningsOpexElement = document.getElementById('earningsOpex');

    var earningsCapex = JSON.parse(earningsCapexElement.getAttribute('data-earnings'));
    var earningsOpex = JSON.parse(earningsOpexElement.getAttribute('data-earnings'));

    // Parsing data JSON yang disimpan di data-attributes
    var earningsData = JSON.parse(earningsDataElement.getAttribute('data-earnings'));
    var revenueSources = JSON.parse(revenueSourcesElement.getAttribute('data-sources'));

    // Output untuk memeriksa data yang diterima
    console.log(earningsData);

    console.log(earningsData);
    console.log(earningsCapex);
    console.log(earningsOpex);
    console.log(revenueSources);

    // Membuat chart.js atau manipulasi lainnya
   // Membuat Bar Chart untuk Pengeluaran

// Fungsi untuk memastikan data memiliki nilai untuk setiap bulan
function processEarningsData(rawData) {
    let processedData = Array.from({ length: 12 }, (_, i) => ({ month: i + 1, total: 0 }));
    rawData.forEach(item => {
        const index = item.month - 1; // Sesuaikan bulan ke indeks array (0-11)
        if (processedData[index]) {
            processedData[index].total = parseInt(item.total, 10); // Pastikan angka menjadi integer
        }
    });
    return processedData.map(item => item.total);
}

// Memproses data untuk Capex dan Opex
var earningsCapexProcessed = processEarningsData(earningsCapex);
var earningsOpexProcessed = processEarningsData(earningsOpex);

console.log("Processed Capex:", earningsCapexProcessed);
console.log("Processed Opex:", earningsOpexProcessed);

// Data untuk chart
var chartData = {
    labels: Array.from({ length: 12 }, (_, i) => new Date(0, i).toLocaleString('default', { month: 'short' })),
    datasets: [
        {
            label: 'Pengeluaran Capex',
            data: earningsCapexProcessed,
            backgroundColor: 'rgba(78, 115, 223, 0.8)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 1
        },
        {
            label: 'Pengeluaran Opex',
            data: earningsOpexProcessed,
            backgroundColor: 'rgba(28, 200, 138, 0.8)',
            borderColor: 'rgba(28, 200, 138, 1)',
            borderWidth: 1
        }
    ]
};







function formatRupiah(number) {
    return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

var ctx = document.getElementById('myAreaChart').getContext('2d');
var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function (context) {
                        var value = context.raw; // Get raw data value
                        return `${context.dataset.label}: ${formatRupiah(value)}`;
                    }
                }
            }
        },
        scales: {
            x: {
                gridLines: {
                    display: false
                }
            },
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                        return formatRupiah(value);
                    }
                },
                gridLines: {
                    color: 'rgba(234, 236, 244, 1)',
                    zeroLineColor: 'rgba(234, 236, 244, 1)'
                }
            }]

        }
    }
});




var ctxPieCapex = document.getElementById('myPieChartCapex').getContext('2d');
new Chart(ctxPieCapex, {
    type: 'pie',
    data: {
        labels: ['Utilized', 'Remaining'],
        datasets: [{
            data: [revenueSources.CapexUsed, revenueSources.CapexRemaining],
            backgroundColor: ['#007bff', '#f6c23e'],
            hoverBackgroundColor: ['#0056b3', '#dda20a']
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {

                    
                    label: function(context) {
                        var value = context.raw; // Get raw data value
                        return `${context.dataset.label}: ${formatRupiah(value)}`;
                    }
                }
            }
        }
    }
});

// Membuat Pie Chart untuk Opex
var ctxPieOpex = document.getElementById('myPieChartOpex').getContext('2d');
new Chart(ctxPieOpex, {
    type: 'pie',
    data: {
        labels: ['Utilized', 'Remaining'],
        datasets: [{
            data: [revenueSources.OpexUsed, revenueSources.OpexRemaining],
            backgroundColor: ['#28a745', '#ffc107'],
            hoverBackgroundColor: ['#1c7430', '#e6a700']
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        var value = context.raw; // Get raw data value
                        return `${context.dataset.label}: ${formatRupiah(value)}`;
                    }
                }
            }
        }
    }
});




</script>
<script>
document.getElementById('exportButton').addEventListener('click', function() {
        const chartContainer = document.querySelector('#chartContainer');
        if (!chartContainer) {
            console.error('Element with ID "chartContainer" not found.');
            return;
        }

        html2canvas(chartContainer).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pdfWidth = pdf.internal.pageSize.width;
            const pdfHeight = pdf.internal.pageSize.height;
            const imgWidth = canvas.width;
            const imgHeight = canvas.height;
            const ratio = Math.min(pdfWidth / imgWidth, pdfHeight / imgHeight);
            const width = imgWidth * ratio;
            const height = imgHeight * ratio;
            pdf.addImage(imgData, 'PNG', 0, 0, width, height);
            pdf.save('dashboard-report.pdf');
        }).catch(function(error) {
            console.error('Error generating report:', error);
        });
    });
</script>


</body>

</html>