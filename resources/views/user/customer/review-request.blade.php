@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-header pb-0">
                        <a href="{{ route('su.customer') }}" class="btn btn-close bg-danger p-2 float-end"></a>
                        <h6>Request Upgrade</h6>
                    </div>
                    <div class="card-body p-3">
                        <div style="display: grid;" class="justify-content-center">
                            <img src="{{ asset('storage/' . $review->photo) }}"
                                style="width: 30rem; object-fit: cover; border: 1px solid black;" id="canvas-photo">
                        </div>
                        <form action="{{ route('su.customer.request.accept', ['user' => $select_user->id]) }}"
                            method="post" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="req_id" id="req_id" value="{{ $review->id }}">
                            <div class="form-group mb-3">
                                <label for="name">Nama Toko</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ $review->instance_name }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-sm btn-success">Terima</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
