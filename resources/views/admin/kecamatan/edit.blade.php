@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Kecamatan | Update Kecamatan</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
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

        <form action="{{ route('admin.kecamatan.update', $kecamatan->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_kecamatan1" class="form-label">Nama kecamatan</label>
                <input type="text" name="nama_kecamatan" class="form-control" id="nama_kecamatan1"
                    value="{{ $kecamatan->nama_kecamatan }}" aria-describedby="kecamatanHelp">
                <div id="kecamatanHelp" class="form-text">
                    Masukkan nama kecamatan.
                </div>
            </div>

            {{-- option pilih kabupaten --}}
            <div class="mb-3">
                <label for="kabupaten" class="form-label">Pilih Kabupaten</label>
                <select class="form-select" name="kabupaten_id" id="kabupaten" aria-label="Default select example">
                    <option selected value="{{ $kecamatan->kabupaten->id }}">
                        {{ $kecamatan->kabupaten->nama_kabupaten }}
                    </option>

                    @php
                        // kecuali kabupaten yang sudah dipilih
                        $kabupaten = App\Models\Kabupaten::where('id', '!=', $kecamatan->kabupaten->id)->get();
                    @endphp

                    @foreach ($kabupaten as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kabupaten }}</option>
                    @endforeach
                </select>
            </div>

            {{-- preview gambar --}}
            <div class="mb-3">
                <label for="fso" class="form-label">Preview Gambar</label>
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('storage/' . $kecamatan->gambar) }}" alt=""
                            class="img-fluid img-preview">
                    </div>
                </div>
            </div>

            {{-- insert gambar --}}
            <div class="mb-3">
                <label for="fso" class="form-label">File Foto</label>
                <input type="file" name="gambar" class="form-control" id="fso" aria-describedby="kabupatenHelp"
                    onchange="previewImg()">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan File Foto Program.
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection

@section('other-js')
    {{-- select2 --}}
    <script>
        $(document).ready(function() {
            $('#kabupaten').select2();
        });
    </script>

    {{-- preview gambar --}}
    <script>
        function previewImg() {
            const gambar = document.querySelector('#fso');
            const gambarLabel = document.querySelector('.img-preview');
            gambarLabel.style.display = 'block';

            const fileGambar = new FileReader();
            fileGambar.readAsDataURL(gambar.files[0]);

            fileGambar.onload = function(e) {
                gambarLabel.src = e.target.result;
            }
        }
    </script>
@endsection
