@extends('layouts.admin.app')

{{-- @dd($liputan) --}}

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Sekapur Sirih</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
            </button>
        </div>
    </div>

    <div class="container">
        <h4>
            Pengaturan Sekapur Sirih
        </h4>

        @if ($sekaps != null)
            <div class="app-bg-base mb-3 p-3"
                style="height: 400px; object-fit: cover; object-position: center; position: relative; overflow: auto;">
                {{-- <img src="{{ asset('img/card.png') }}" class="w-100 h-100" alt="..."
        style="object-fit: contain; object-position: center"> --}}
                <h5 class="text-center">Sekapur Sirih</h5>

                {{ $sekaps->isi }}
            </div>
        @else
            <h6 class="text-center">
                Tidak ada Sekapur Sirih
            </h6>
        @endif

        {{-- form --}}
        <form action="{{ route('redaksi.sekapur-sirih.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- <div class="col-md-6 mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="judul" required>
                </div> --}}
                {{-- file gambar --}}
                <div class="col-md-12 mb-3">
                    <label for="gambar">Isi Sekapur Sirih</label>
                    <textarea name="isi" id="isi" cols="30" rows="10" class="form-control" required></textarea>
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
