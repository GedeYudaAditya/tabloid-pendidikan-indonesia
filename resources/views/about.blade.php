@extends('layouts.other.app')

@section('other-css')
    <style>
        .border-bottom-blue {
            border-bottom: 3px solid #0d6efd;
        }

        .hover-a:hover {
            background-color: #e9ecef;
        }

        .hover-kab:hover {
            background-color: #024bb9 !important;
        }

        .hover-kec:hover {
            background-color: #009ab9 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                {{-- logo image --}}
                <img src="{{ asset('img/logo_front.jpg') }}" class="img-fluid w-100" alt="logo">
            </div>
            <div class="col-md-8 d-flex justify-content-center align-items-center">
                <div>
                    {{-- title --}}
                    <h1>Yayasan Medkom Indonesia Jaya</h1>
                    <h2>TPI</h2>
                    <h6>Nomor HAU</h6>
                    <hr>
                    {{-- description --}}
                    <p class="text-justify">
                        Tabloid Pendi.Indo adalah media informasi yang bergerak di bidang pendidikan. Tabloid Pendi.Indo
                        merupakan media informasi yang berbasis online dan offline. Tabloid Pendi.Indo berdiri pada tanggal
                        1
                        Januari 2021. Tabloid Pendi.Indo berada di bawah naungan PT. Pendidikan Indonesia.
                    </p>
                </div>
            </div>
        </div>
        <h2>Struktur Organisasi</h2>
        <hr>
        <div class="row" style="flex-wrap: nowrap; overflow: auto">
            @forelse ($sistem_informasi as $item)
                <div class="col-md-3">
                    <div class="card mb-3" style="position: relative">
                        <img src="{{ asset('img/sistem-informasi/' . $item->foto) }}" class="card-img-top"
                            style="height: 300px; object-fit: cover; object-position: center"
                            alt="{{ asset('img/sistem-informasi/' . $item->foto) }}">
                        <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                            <h6 class="card-title mb-1" style="font-size: small">{{ $item->nama }}</h6>
                            <small class="text-dark" style="font-size: x-small; font-weight: bold;">
                                {{ $item->jabatan }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-danger text-center" role="alert">
                        Data tidak ditemukan!
                    </div>
                </div>
            @endforelse
        </div>
        <h2 class="mt-3">
            Program-program
        </h2>
        <div class="row">
            {{-- programs --}}
            @forelse ($program as $item)
                <div class="col-md-4">
                    <div class="card mb-3" style="position: relative">
                        <img src="{{ asset('img/program/' . $item->foto) }}" class="card-img-top"
                            style="height: 200px; object-fit: cover; object-position: center"
                            alt="{{ asset('img/program/' . $item->foto) }}">
                        <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                            <h5 class="card-title mb-1">{{ $item->nama_program }}</h5>
                            {{-- <small class="text-dark">
                                {{ $item->jabatan }}
                            </small> --}}
                        </div>
                    </div>
                    <p>
                        {{ $item->deskripsi }}
                    </p>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-danger text-center" role="alert">
                        Data tidak ditemukan!
                    </div>
                </div>
            @endforelse
        </div>

    </div>
@endsection
