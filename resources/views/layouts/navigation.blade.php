<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <!-- Icon with Counter -->
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter -->
            <span class="badge badge-danger badge-counter">
                {{ array_sum(array_map(fn($items) => count($items), $notifications)) }}
            </span>
        </a>

        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">Alerts Center</h6>

            <!-- Loop Through Notifications Per Section -->
            @foreach ($notifications as $type => $items)
                @if ($items->isNotEmpty())
                    <!-- Section Header -->
                    <div class="dropdown-header bg-light text-primary font-weight-bold">
                        {{ ucfirst($type) }}
                    </div>
                    
                    <!-- Notifications -->
                    @foreach ($items as $item)
                    @php
                        $routeName = Route::has($type . '.index') ? route($type . '.index') : '#';
                    @endphp
                        <a class="dropdown-item d-flex align-items-center" href="{{ $routeName }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                <i class="{{ $type == 'transfers' ? 'fas fa-exchange-alt' : ($type == 'requests' ? 'fas fa-file-alt' : 'fas fa-bell') }} text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="text-truncate d-block font-weight-bold">
                                {{ $item->receives->requests->sparepart->nama_sparepart ?? $item->request->nama_asset ?? $item->receives->requests->nama_asset ?? $item->nama_sparepart ?? $item->nama_asset ?? 'Item ID: ' . $item->id }}
                                </span>
                                <div class="small text-gray-500">{{ $item->created_at->format('F j, Y') }}</div>
                            </div>
                        </a>
                    @endforeach
                @endif
            @endforeach

            <!-- If No Notifications -->
            @if (empty(array_filter($notifications, fn($items) => count($items) > 0)))
                <div class="dropdown-item text-center text-gray-500">
                    No new notifications.
                </div>
            @endif

            <!-- View All Link -->
            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
        </div>
    </li>



        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('admin_assets/img/logo-media.jpg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
