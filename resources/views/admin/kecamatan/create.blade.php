@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Kecamatan | Tambah Kecamatan</h1>
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
        <form action="{{ route('admin.kecamatan.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="nama_kecamatan1" class="form-label">Nama kecamatan</label>
                <input type="text" name="nama_kecamatan" class="form-control" id="nama_kecamatan1"
                    aria-describedby="kecamatanHelp">
                <div id="kecamatanHelp" class="form-text">
                    Masukkan nama kecamatan.
                </div>
            </div>

            {{-- option pilih kabupaten --}}
            <div class="mb-3">
                <label for="kabupaten" class="form-label">Pilih Kabupaten</label>
                <select class="form-select" name="kabupaten_id" id="kabupaten" aria-label="Default select example">
                    <option selected disabled>Pilih Kabupaten</option>
                    @foreach ($kabupaten as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kabupaten }}</option>
                    @endforeach
                </select>
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
@endsection
