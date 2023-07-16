@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-body px-5 p-3 pb-2">
                        <h4 class="my-3">Tabel Pesanan</h4>
                        <div class="table-responsive my-3">
                            <table class="table align-items-center mb-0" id="table_invoice">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            No</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Invoice Code</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                           Tanggal</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Grand Total</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status Pembayaran</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status Pemesanan</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Metode Pembayaran</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $key => $invoice)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm text-center">{{ $key + 1 }}</h6>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->invoice_code }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->created_at }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">Rp.
                                                    {{ number_format($invoice->grand_total, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                @if ($invoice->payment_method == 'cash' && $invoice->status != 'Lunas')
                                                    <a href="{{ route('su.invoice.confirm-cash', ['invoice' => $invoice->id]) }}"
                                                        class="btn btn-sm bg-gradient-warning">Konfirmasi</a>
                                                @else
                                                    <span class="text-xs font-weight-bold">
                                                        {{ $invoice->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    @foreach ($invoice->order as $key => $order)
                                                        {{ $order->status }}
                                                    @break
                                                @endforeach
                                            </span>
                                        </td>
                                        <td class="align-middle text-sm">
                                            <span class="text-xs font-weight-bold">{{ $invoice->payment_method }}</span>
                                        </td>
                                        <td class="align-middle text-sm">
                                            <a href="{{ route('su.invoice.detail', ['invoice' => $invoice->id]) }}"
                                                class="btn btn-sm bg-gradient-primary">Tracking</a>
                                            <a href="{{ route('su.invoice.print_pdf', ['invoice' => $invoice->id]) }}"
                                                class="btn btn-sm bg-gradient-primary" target="_blank">Cetak Invoice</a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
<script>
    $(document).ready(function() {
        $('#table_invoice').DataTable();
    });
</script>
@endsection
