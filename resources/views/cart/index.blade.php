@extends('templates.main')
@section('content')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
        <div class="card-header pb-0">
            <div class="text-center">
                <h3>Keranjang</h3>
            </div>
        </div>

        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-4">List Keranjang</h4>
                    <h4>Total : {{ count($carts) }}</h4>
                </div>
                <form action="{{ route('cart.checkout') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if (!$carts)
                        <div class="card shadow mb-3">
                            <div class="card-body p-3">
                                <h4 class="text-center">Tidak ada Item!</h4>
                            </div>
                        </div>
                    @endif
                    @foreach ($carts as $key => $cart)
                        <div class="card shadow-lg mb-3">
                            <div class="card-body">

                                <div class="d-flex gap-5 form-check form-check-inline float-end">
                                    <div class="form-group m-0">
                                        <input type="hidden" name="product_id[{{ $cart->id }}]"
                                            value="{{ $cart->product_id }}">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox"
                                            name="cart[{{ $cart->id }}]">
                                        <label class="form-check-label" for="cart_select">Pilih</label>
                                    </div>
                                    <div class="dropdown">
                                        <button class="fa-solid fa-ellipsis-vertical" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('cart.delete', ['cart' => $cart->id]) }}"
                                                    onclick="return confirm('Apakah anda ingin menghapus produk ini dari keranjang?')">Hapus</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <img src="{{ asset('storage/' . $cart->product->photo) }}"
                                            style="width: 100%; object-fit: cover;" class="rounded"
                                            alt="Photo {{ $cart->product->name }}">
                                    </div>
                                    <div class="col-lg-8">
                                        <h5 class="card-title">{{ $cart->product->name }}</h5>
                                        </p>
                                        <div class="row gap-3 g-3 align-items-center mb-3">
                                            <div class="col-2">
                                                <label for="qty" class="col-form-label">Kuantitas</label>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="qty[{{ $cart->id }}]" id="qty"
                                                    class="form-control-plaintext" value="{{ $cart->qty }}">
                                            </div>
                                        </div>
                                        <div class="row gap-3 g-3 align-items-center mb-3">
                                            <div class="col-2">
                                                <label for="total" class="col-form-label">Total</label>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" name="total[{{ $cart->id }}]" id="total"
                                                    class="form-control-plaintext" value="{{ $cart->total }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($carts)
                        <div class="d-grid">
                            <button class="btn btn-lg bg-gradient-success float-end">Checkout</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Notification --}}
    <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification"
        aria-hidden="true">
        <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-notification">{{ Session::get('type') }}</h6>
                    <button type="button" class="bg-danger btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="py-3 text-center">
                        <i class="ni ni-bell-55 ni-3x"></i>
                        <h4 class="text-gradient text-{{ Session::get('class') }} mt-4">{{ Session::get('alert') }}</h4>
                        <p>{{ Session::get('message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
