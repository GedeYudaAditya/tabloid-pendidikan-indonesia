@extends('layouts.admin.app')

{{-- @dd($liputan) --}}

@section('other-css')
    <style>
        .app-overlay-bg {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Sekapur sirih</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <h4>
            Pengaturan Sekapur Sirih
        </h4>

        @if ($hari_peringatan != null)
            <div class="card mb-3" style="position: relative">
                <img src="{{ asset('img/hariraya/' . $hari_peringatan->gambar) }}" class="card-img-top"
                    style="height: 400px; object-fit: cover; object-position: center"
                    alt="{{ asset('img/hariraya/' . $hari_peringatan->gambar) }}">
                <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                    <h5 class="card-title mb-1">{{ $hari_peringatan->judul }}</h5>
                    {{-- time created --}}
                    <small class="text-white">
                        {{ $hari_peringatan->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        @else
            <h6 class="text-center">
                Tidak ada hari peringatan
            </h6>
        @endif

        {{-- form --}}
        <form action="{{ route('redaksi.hari-peringatan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="judul" required>
                </div>
                {{-- file gambar --}}
                <div class="col-md-6 mb-3">
                    <label for="gambar">Gambar</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" required>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Tambah</button>
        </form>

        {{-- error input --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>
            </div>
        @endif
    </div>
@endsection
