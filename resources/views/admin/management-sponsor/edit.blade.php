@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Sponsor | Edit Program</h1>
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
        <form action="{{ route('admin.management-sponsor.update', $sponsor->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nsi" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" id="nsi" aria-describedby="kabupatenHelp"
                    value="{{ $sponsor->nama }}">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan nama.
                </div>
            </div>

            {{-- preview gambar --}}
            <div class="mb-3">
                <label for="fso" class="form-label">Preview Gambar</label>
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('img/sponsor/' . $sponsor->gambar) }}" alt=""
                            class="img-fluid img-preview">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="fso" class="form-label">File Foto</label>
                <input type="file" name="gambar" class="form-control" id="fso" aria-describedby="kabupatenHelp"
                    onchange="previewImg()">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan File Foto Program.
                </div>
            </div>

            {{-- jabatan --}}
            <div class="mb-3">
                <label for="kontak" class="form-label">Link</label>
                <input type="text" name="link" class="form-control" id="kontak" aria-describedby="kabupatenHelp"
                    value="{{ $sponsor->link }}">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection

@section('other-js')
    {{-- preview gambar --}}
    <script>
        function previewImg() {
            const gambar = document.querySelector('#fso');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(gambar.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>
@endsection
