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
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-8 mb-lg-0 mb-4">
                <h3>{{ $title }}</h3>

                <p>{{ $transaction }}</p>
                <hr class="border border-1 border-dark">
                {{-- <h4>{{ $invoice->order->product->name }} ({{ $invoice->order->product->uom }}) --}}
                </h4>
                <div class="row">
                    <div class="col-3">
                        {{-- <img src="{{ asset('storage/' . $invoice->order->product->photo) }}"
                            alt="Photo {{ $invoice->order->product->name }}" class="img-fluid rounded"> --}}
                    </div>
                    <div class="col-9">
                        <div class="table-responsive">
                            {{-- <table class="table">
                                <tr>
                                    <th class="w-30">Kuantitas</th>
                                    <td>:</td>
                                    <td>{{ $invoice->order->qty }}</td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td>:</td>
                                    <td>{{ $invoice->grand_total }}</td>
                                </tr>
                                <tr>
                                    <th>Dikirim ke</th>
                                    <td>:</td>
                                    <td>{{ $invoice->order->send_to }}</td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>:</td>
                                    <td>{{ $invoice->payment_method }}</td>
                                </tr>
                                <tr>
                                    <th>Status Pembayaran</th>
                                    <td>:</td>
                                    <td>{{ $invoice->status }}</td>
                                </tr>
                            </table> --}}
                        </div>
                    </div>
                </div>
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
