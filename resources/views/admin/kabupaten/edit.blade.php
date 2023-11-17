@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Kabupaten | Update Kabupaten</h1>
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

        <form action="{{ route('admin.kabupaten.update', $kabupaten->id) }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="nama_kabupaten1" class="form-label">Nama Kabupaten</label>
                <input type="text" name="nama_kabupaten" class="form-control" id="nama_kabupaten1"
                    value="{{ $kabupaten->nama_kabupaten }}" aria-describedby="kabupatenHelp">
                <div id="kabupatenHelp" class="form-text">
                    Masukkan nama kabupaten.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection
