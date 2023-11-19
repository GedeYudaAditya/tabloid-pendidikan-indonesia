@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Informasi | Tambah Informasi</h1>
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
        <form action="{{ route('admin.sistem-informasi.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nsi" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" id="nsi" aria-describedby="kabupatenHelp">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan nama.
                </div>
            </div>

            <div class="mb-3">
                <label for="fso" class="form-label">File Foto</label>
                <input type="file" name="foto" class="form-control" id="fso" aria-describedby="kabupatenHelp">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan File Foto.
                </div>
            </div>

            {{-- jabatan --}}
            <div class="mb-3">
                <label for="kontak" class="form-label">Nama Jabatan</label>
                <input type="tel" name="jabatan" class="form-control" id="kontak" aria-describedby="kabupatenHelp">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
