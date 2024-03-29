@php
    $dataSidebar = [
        0 => [
            'id' => 1,
            'name' => 'Dashboard',
            'icon' => 'ni ni-tv-2 text-primary',
            'url' => 'su.dashboard',
            'role' => ['Admin', 'Owner'],
        ],
        1 => [
            'id' => 2,
            'name' => 'Produk',
            'icon' => 'ni ni-app text-warning',
            'url' => 'su.product',
            'role' => ['Admin'],
        ],
        2 => [
            'id' => 3,
            'name' => 'Kategori',
            'icon' => 'ni ni-tag text-primary',
            'url' => 'su.category',
            'role' => ['Admin'],
        ],
        3 => [
            'id' => 4,
            'name' => 'Admin dan Driver',
            'icon' => 'ni ni-single-02 text-danger',
            'url' => 'su.admin',
            'role' => ['Admin'],
        ],
        4 => [
            'id' => 5,
            'name' => 'Reseller',
            'icon' => 'ni ni-cart text-danger',
            'url' => 'su.reseller',
            'role' => ['Admin'],
        ],
        5 => [
            'id' => 6,
            'name' => 'Customer',
            'icon' => 'ni ni-circle-08 text-dark',
            'url' => 'su.customer',
            'role' => ['Admin'],
        ],
        6 => [
            'id' => 7,
            'name' => 'Pesanan',
            'icon' => 'ni ni-box-2 text-warning',
            'url' => 'su.order',
            'role' => ['Admin'],
        ],
        7 => [
            'id' => 8,
            'name' => 'Area',
            'icon' => 'ni ni-map-big text-dark',
            'url' => 'su.area',
            'role' => ['Admin'],
        ],
        8 => [
            'id' => 9,
            'name' => 'Grafik Penjualan',
            'icon' => 'ni ni-chart-pie-35 text-dark',
            'url' => 'su.sales-graph',
            'role' => ['Admin', 'Owner'],
        ],
        9 => [
            'id' => 10,
            'name' => 'Laporan Penjualan',
            'icon' => 'ni ni-send text-dark',
            'url' => 'su.sales-report',
            'role' => ['Admin', 'Owner'],
        ],
        10 => [
            'id' => 11,
            'name' => 'List Pengiriman',
            'icon' => 'ni ni-app text-warning',
            'url' => 'su.delivery',
            'role' => ['Driver'],
        ],
    ];
@endphp

<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header mx-3">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html "
            target="_blank">
            <img src="{{ asset('argon/img/logos/logo.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">DnG Store</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @foreach ($dataSidebar as $data)
                @if (in_array($user->role, $data['role']))
                    <li class="nav-item">
                        <a class="nav-link {{ $data['name'] == $menu[0] ? 'active' : '' }}"
                            href="{{ route($data['url']) }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="{{ $data['icon'] }} text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ $data['name'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $menu[0] == 'Profile' ? 'active' : '' }}" href="{{ route('su.profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="return confirm('Apakah anda yakin ingin keluar?')">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-button-power text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
