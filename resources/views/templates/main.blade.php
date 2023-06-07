<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argon/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('argon/img/logos/logo.png') }}">
    <title>
        {{ $title }}
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    {{-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> --}}
    <link href="{{ asset('argon/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('argon/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg position-sticky top-0 z-index-3 w-100 shadow-none bg-dark">
        <div class="container">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="{{ route('home') }}">
                DnG Store
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon mt-2">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        @if ($menu == 'home')
                            <form action="" method="get" class="d-flex align-items-center me-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search">
                                    <button class="input-group-text bg-secondary text-white">Cari</button>
                                </div>
                            </form>
                        @endif
                    </li>
                </ul>
                <ul class="navbar-nav my-2">
                    <li class="nav-item">
                        @if (Session::get('name'))
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle mb-0" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $user->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('home.profile', ['user' => $user->id]) }}">Profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('cart', ['user' => $user->id]) }}">Keranjang</a>
                                    </li>
                                    @if ($user->role == 4)
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('cart', ['user' => $user->id]) }}">Mengajukan Reseller</a>
                                    </li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-secondary mb-0">Login</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <main class="main-content mt-0">
        @yield('content')
    </main>
    <!--   Core JS Files   -->
    <script src="{{ asset('argon/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('argon/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('argon/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('argon/js/plugins/smooth-scrollbar.min.js') }}"></script>
    {{-- <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script> --}}
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('argon/js/argon-dashboard.min.js?v=2.0.4') }}"></script>
    {{-- Fontawesome --}}
    <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>
</body>

</html>
