@php
    $dataSidebar = [
        0 => [
            'id' => 1,
            'name' => 'Dashboard',
            'icon' => 'ni ni-tv-2 text-primary',
            'url' => 'owner',
        ],
        1 => [
            'id' => 2,
            'name' => 'Laporan Penjualan',
            'icon' => 'ni ni-send text-dark',
            'url' => 'owner.sales-report',
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
            @endforeach
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $menu[0] == 'Profile' ? 'active' : '' }}" href="{{ route('owner.profile') }}">
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
