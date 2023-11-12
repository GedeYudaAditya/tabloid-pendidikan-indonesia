@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
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
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Banyak Kabupaten</h5>
                        <p class="card-text">
                            {{ $kabupaten->count() }} Kabupaten
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Banyak Kecamatan</h5>
                        <p class="card-text">
                            {{ $kecamatan->count() }} Kecamatan
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Banyak Berita</h5>
                        <p class="card-text">
                            {{ $berita->count() }} Berita
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- table semua user --}}
        <div class="card mt-3">
            <div class="card-header">
                <div class="row justify-content-between">
                    <h5 class="card-title col-2">Semua User</h5>
                    {{-- tambah button --}}
                    @if (Auth::user()->level == 'admin')
                        <a href="{{ route('admin.user-management.create') }}" class="btn btn-primary col-2">Tambah User</a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table id="table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>
                                Jumlah Berita/Liputan/Jurnal
                            </th>
                            @if (Auth::user()->level == 'admin')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    @if ($item->level == 'admin')
                                        <span class="badge bg-primary text-light">{{ Str::upper($item->level) }}</span>
                                    @elseif ($item->level == 'user')
                                        <span class="badge bg-secondary text-light">{{ Str::upper($item->level) }}</span>
                                    @elseif ($item->level == 'reporter')
                                        <span class="badge bg-success text-light">{{ Str::upper($item->level) }}</span>
                                    @elseif ($item->level == 'redaksi')
                                        <span class="badge bg-info text-light">{{ Str::upper($item->level) }}</span>
                                    @else
                                        <span class="badge bg-warning text-light">{{ Str::upper($item->level) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->level == 'admin' || $item->level == 'redaksi')
                                        {{ $item->berita->count() }}
                                    @elseif ($item->level == 'reporter')
                                        {{ $item->liputan->count() }}
                                    @else
                                        {{ $item->jurnal->count() }}
                                    @endif
                                </td>
                                @if (Auth::user()->level == 'admin')
                                    <td>
                                        {{-- <a href="#" class="btn btn-sm btn-primary">Detail</a> --}}
                                        <a href="{{ route('admin.user-management.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.user-management.delete', $item->id) }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus data?')">Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <a href="{{ route('admin.user.create') }}" class="btn btn-primary mt-3">Tambah User</a> --}}
            </div>
        </div>

        {{-- Arsip Tahunan Berita --}}
        @if (Auth::user()->level == 'admin')
            <div class="card mt-3">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <h5 class="card-title col-2">Arsip Berita</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Berita</th>
                                <th>Tahun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($berita as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{ route('admin.berita.detail', $item->slug) }}">{{ $item->judul }}</a>
                                    </td>
                                    <td>{{ $item->created_at->format('Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('other-js')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });

        $(document).ready(function() {
            $('#table2').DataTable({
                "order": [
                    [2, "desc"]
                ]
            });
        });
    </script>
@endsection
