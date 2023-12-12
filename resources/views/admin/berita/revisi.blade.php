@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Lihat Berita Revisi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <a href="{{ route('admin.berita.index') }}" class="btn btn-primary mb-3">
            <i class="fas fa-eye"></i>
            Lihat Berita
        </a>

        {{-- datatable --}}
        <table id="table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Berita</th>
                    <th>
                        Dibuat Oleh
                    </th>
                    <th>
                        Direport Oleh
                    </th>
                    <th>
                        Nama Kecamatan
                    </th>
                    <th>
                        Nama Kabupaten
                    </th>
                    <th>
                        Status
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($berita as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ route('admin.berita.detail', $item->slug) }}">{{ $item->judul }}</a></td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->liputan->reporter->name }}</td>
                        <td>{{ $item->kecamatan->nama_kecamatan }}</td>
                        <td>{{ $item->kecamatan->kabupaten->nama_kabupaten }}</td>
                        <td class="text-center">
                            @if ($item->status == 'draft')
                                <span class="badge bg-secondary text-light">{{ Str::upper($item->status) }}</span>
                            @elseif ($item->status == 'ditolak')
                                <span class="badge bg-danger text-light">{{ Str::upper($item->status) }}</span>
                            @elseif ($item->status == 'revisi')
                                <span class="badge bg-warning text-light">{{ Str::upper($item->status) }}</span>
                            @else
                                <span class="badge bg-success text-light">{{ Str::upper($item->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.berita.revisi.detail', $item->id) }}" class="btn btn-sm btn-info">
                                lihat Revisi
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('other-js')
    {{-- datatable --}}
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
