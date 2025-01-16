<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon rotate">
        <img src="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" alt="logo" style="height: 4em; width: 4em;">
        </div>
        <div class="sidebar-brand-text mx-3">Drone Operation </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item ">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Purchasing</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ route('request.index') }}">Request</a>
            <a class="collapse-item" href="{{ route('receive.index') }}">Receive</a>
            </div>
        </div>
    </li>

    <!-- Inventory -->
    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-table"></i>
            <span>Inventory</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Components</h6>
                <a class="collapse-item" href="{{ route('asset.index') }}">Assets</a>
                <a class="collapse-item" href="{{ route('sparepart.index') }}">Spareparts</a>
                <a class="collapse-item" href="{{ route('transfer.index') }}">Transfer</a>
            </div>
        </div>
    </li>

    <!-- Utilities -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Maintenance</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Maintenance</h6>
                <a href="{{ route('reports.index') }}" class="collapse-item">Report</a>
                <a href="{{ route('estimations.index') }}" class="collapse-item">Estimation</a>
                <a href="{{ route('realisasi.index') }}" class="collapse-item">Realisasi</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
   <!-- Addons -->
   <div class="sidebar-heading">
        Process
    </div>

    <!-- Charts -->
    <li class="nav-item">
    <a class="nav-link" href="{{ route('budget.index') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Budget</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
