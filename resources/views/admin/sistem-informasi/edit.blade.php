@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Informasi | Edit Informasi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        {{-- error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li class="mb-0">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.sistem-informasi.update', $sistem_informasi->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nsi" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" id="nsi"
                    value="{{ $sistem_informasi->nama }}" aria-describedby="kabupatenHelp">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan nama.
                </div>
            </div>

            {{-- preview image --}}
            <div class="mb-3">
                <label for="fso" class="form-label">Preview Foto</label>
                <img src="{{ asset('img/sistem-informasi/' . $sistem_informasi->foto) }}" alt="preview image"
                    class="img-fluid">
            </div>

            <div class="mb-3">
                <label for="fso" class="form-label">File Foto</label>
                <input type="file" name="foto" class="form-control" id="fso"
                    value="{{ $sistem_informasi->foto }}" aria-describedby="kabupatenHelp">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan File Struktur Organisasi (image).
                </div>
            </div>

            {{-- kontak --}}
            <div class="mb-3">
                <label for="jabatan" class="form-label">Nama Jabatan</label>
                <input type="tel" name="jabatan" class="form-control" id="jabatan" aria-describedby="kabupatenHelp"
                    value="{{ $sistem_informasi->jabatan }}">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
