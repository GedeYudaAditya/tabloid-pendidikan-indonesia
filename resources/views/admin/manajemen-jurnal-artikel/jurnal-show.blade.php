@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Artikel dan Jurnal | Lihat Artikel dan Jurnal</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container mb-3">
        {{-- judul --}}
        <h3 class="mb-3">
            {{ $jurnal->judul }}
            {{-- status --}}
            <div class="mt-3">
                Status:
                @if ($jurnal->status == 'draft')
                    <span class="badge bg-secondary text-light">{{ Str::upper($jurnal->status) }}</span>
                @else
                    <span class="badge bg-success text-light">{{ Str::upper($jurnal->status) }}</span>
                @endif
            </div>
        </h3>

        {{-- gambar --}}
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @php
                // array string "[\"1.jpg\",\"2.jpg\"]" diubah menjadi array
                $gambars = json_decode($jurnal->gambar);
            @endphp
            @foreach ($gambars as $item)
                <div class="col">
                    <div class="card">
                        <img src="{{ asset('/img/liputan/' . $item) }}" class="card-img-top" alt="...">
                    </div>
                </div>
            @endforeach
        </div>

        {{-- isi --}}
        <div class="mt-3">
            {!! $jurnal->isi !!}
        </div>

        {{-- tombol kembali --}}
        <div class="mt-3">
            <a href="{{ route('reporter.liputan.index') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
