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
                    <div class="card-header pb-0">
                        <h4>Detail Invoice</h4>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <h6>List Pemesanan</h6>
                                <ol class="list-group list-group-numbered">
                                    @foreach ($invoice->order as $order)
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">{{ $order->product->name }}</div>
                                                <ul>
                                                    <li>Harga Satuan :
                                                        Rp.
                                                        {{ number_format($order->user->role == 'customer' ? $order->product->customer_price : $order->product->reseller_price, 0, ',', '.') }}
                                                    </li>
                                                    <li>Total Harga :
                                                        Rp. {{ number_format($order->total, 0, ',', '.') }}
                                                    </li>
                                                </ul>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">QTY : {{ $order->qty }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="col-lg-6">
                                <h6>Invoice</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>No Invoice</th>
                                            <td>:</td>
                                            <td>{{ $invoice->invoice_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>:</td>
                                            <td>{{ $invoice->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pemesan</th>
                                            <td>:</td>
                                            <td>
                                                @foreach ($invoice->order as $key => $io)
                                                    @if ($key == 0)
                                                        {{ $io->user->name }}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>:</td>
                                        <td>{{ $invoice->send_to }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td>:</td>
                                        <td>{{ $invoice->notes }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ongkir</th>
                                        <td>:</td>
                                        <td>Rp. {{ number_format($invoice->ongkir, 0, '.', ',') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Harga</th>
                                        <td>:</td>
                                        <td>Rp. {{ number_format($invoice->grand_total, 0, '.', ',') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>:</td>
                                        <td>{{ $invoice->status }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Orderan</th>
                                        <td>:</td>
                                        <td>
                                            @foreach ($invoice->order as $i => $order)
                                                @if ($order->status == 'Diterima')
                                                    {{ $order->status }}
                                                @break

                                            @else
                                                <form
                                                    action="{{ route('admin-order.update-status', ['invoice' => $invoice->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group">
                                                        <select name="status" id="status" class="form-select">
                                                            @foreach ($status as $s)
                                                                @foreach ($invoice->order as $key => $io)
                                                                    @if ($key == 0)
                                                                        <option value="{{ $s }}"
                                                                            {{ $io->status == $s ? 'selected' : '' }}>
                                                                            {{ $s }}
                                                                        </option>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                    <button
                                                        class="input-group-text bg-gradient-warning">Update</button>
                                                </div>
                                            </form>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.invoices') }}" class="btn btn-sm bg-gradient-danger">Kembali</a>
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
