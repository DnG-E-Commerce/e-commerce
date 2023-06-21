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
                <h3>Pesanan</h3>
            </div>
        </div>
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-4">List</h4>
                    <h4>Total : {{ count($orders) }}</h4>
                </div>
                @if (!$orders)
                    <div class="card shadow mb-3">
                        <div class="card-body p-3">
                            <h4 class="text-center">Tidak ada Item!</h4>
                        </div>
                    </div>
                @endif
                @foreach ($orders as $key => $order)
                    <div class="card shadow-lg mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <img src="{{ asset('storage/' . $order->product->photo) }}"
                                        style="width: 100%; object-fit: cover;" class="rounded"
                                        alt="Photo {{ $order->product->name }}">
                                </div>
                                <div class="col-lg-8">
                                    <div class="d-flex gap-3">
                                        <h5 class="card-title">{{ $order->product->name }}</h5>
                                    </div>
                                    <div class="d-flex gap-5 g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="qty" class="col-form-label">Kuantitas</label>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" name="qty[{{ $order->id }}]"
                                                class="form-control-plaintext" value="{{ $order->qty }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="total" class="col-form-label">Total</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="total[{{ $order->id }}]"
                                                class="form-control-plaintext" value="{{ $order->total }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
