@extends('templates.driver')
@section('content')
    @include('templates.layouts.driver-navbar')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
        <div class="card z-index-2 p-3">
            <div class="card-header pb-0">
                <h6>Tabel Pengiriman Pesanan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-stripped table-hover" id="example">
                        <thead>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Invoice Code</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Grand Total</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Status Pembayaran</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Status Pemesanan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Opsi</th>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $key => $invoice)
                                @foreach ($invoice->order as $i => $order)
                                    @if (in_array($order->status, ['Dikirim', 'Diterima']))
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm text-center">{{ $key + 1 }}</h6>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->invoice_code }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->grand_total }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->status }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    @if ($i == 0)
                                                        {{ $order->status }}
                                                    @endif

                                                </span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <a href="{{ route('driver.invoice', ['invoice' => $invoice->id]) }}"
                                                    class="btn btn-sm bg-gradient-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endif
                                @break
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
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
