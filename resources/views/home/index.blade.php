@extends('templates.main')

@section('content')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
        @production
            <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
                style="background-image: url('https://i.ibb.co/WpK2ThV/Whats-App-Image-2023-06-23-at-01-12-19-1.jpg'); background-position: cover;">
                <span class="mask bg-gradient-dark opacity-6"></span>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 text-center mx-auto">
                            <h1 class="text-white mb-2 mt-5">Selamat datang </h1>
                            <h2 class="text-white mb-2">{{ $user->name }}!</h2>
                            <p class="text-lead text-white">Di Sistem Informasi E-Commerce D&G Store, Selamat Berbelanja</p>
                        </div>
                    </div>
                </div>
            </div>
        @endonce
    @endif
    <div class="container">
        @foreach ($top_resellers as $tr)
            @if ($tr->id == $user->id)
                <div class="row justify-content-center mt-3">
                    <div class="col-md-10 col-lg-10 col-sm-4">
                        <h4 class="fst-italic">Produk Terbatas Khusus Reseller</h4>
                        <div class="d-flex gap-3 mb-5" style="flex-wrap: wrap;">
                            @foreach ($special_products as $key => $sp)
                                <div class="card shadow-md" style="width: 20rem;">
                                    <img src="{{ asset('storage/' . $sp->photo) }}" alt="Image {{ $sp->name }}"
                                        class="image-fluid"
                                        style="object-fit: cover; height: 15rem; padding: 12px; border-radius: 24px;">
                                    <div class="card-body">
                                        <h3>{{ $sp->name }}</h3>
                                        <p class="fst-italic">{{ substr($sp->desc, 0, 50) . '.....' }}</p>
                                        <a href="{{ route('us.product.detail', ['product' => $sp->id]) }}"
                                            class="text-primary">Lihat Lebih Detail <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{ $special_products->links() }}
                    </div>
                </div>
            @endif
        @endforeach
        <hr class="border border-1 border-dark">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 col-lg-10 col-sm-4">
                <h4 class="fst-italic">Produk</h4>
                <div class="d-flex gap-3 mb-5" style="flex-wrap: wrap;">
                    @foreach ($products as $key => $product)
                        @if ($product->special_status != 'Limited Edition')
                            <div class="card shadow-md" style="width: 20rem;">
                                <img src="{{ asset('storage/' . $product->photo) }}" alt="Image {{ $product->name }}"
                                    class="image-fluid"
                                    style="object-fit: cover; height: 15rem; padding: 12px; border-radius: 24px;">
                                <div class="card-body">
                                    <h3>{{ $product->name }}</h3>
                                    <p class="fst-italic">{{ substr($product->desc, 0, 50) . '.....' }}</p>
                                    <a href="{{ route('us.product.detail', ['product' => $product->id]) }}"
                                        class="text-primary">Lihat Lebih Detail <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                {{ $products->links() }}
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
                        <p>{!! Session::get('message') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
