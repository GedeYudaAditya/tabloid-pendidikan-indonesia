@extends('layouts.admin.app')

{{-- @dd($liputan) --}}

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Liputan dan Berita</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <h4>
            Berita yang belum dipublikasikan
        </h4>
        <a href="{{ route('redaksi.berita-unpublish.create') }}" class="btn btn-primary mb-3">Tambah Berita</a>

        {{-- datatable --}}
        <table id="table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Berita</th>
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
                @foreach ($beritas as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->kecamatan->nama_kecamatan }}</td>
                        <td>{{ $item->kecamatan->kabupaten->nama_kabupaten }}</td>
                        <td class="text-center">
                            @if ($item->status == 'mengantri')
                                <span class="badge bg-secondary text-light">{{ Str::upper($item->status) }}</span>
                            @elseif($item->status == 'ditolak')
                                <span class="badge bg-danger text-light">{{ Str::upper($item->status) }}</span>
                            @elseif($item->status == 'revisi')
                                <span class="badge bg-warning text-light">{{ Str::upper($item->status) }}</span>
                            @else
                                <span class="badge bg-success text-light">{{ Str::upper($item->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('redaksi.berita-unpublish.edit', $item->id) }}"
                                class="btn btn-warning">Edit</a>
                            <form action="{{ route('redaksi.berita-unpublish.delete', $item->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"
                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>
            Liputan masuk
        </h4>

        {{-- datatable --}}
        <table id="table2" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Liputan</th>
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
                @foreach ($liputans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->kecamatan->nama_kecamatan }}</td>
                        <td>{{ $item->kecamatan->kabupaten->nama_kabupaten }}</td>
                        <td class="text-center">
                            @if ($item->status == 'mengantri')
                                <span class="badge bg-secondary text-light">{{ Str::upper($item->status) }}</span>
                            @else
                                <span class="badge bg-success text-light">{{ Str::upper($item->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('redaksi.berita-unpublish.create-from-liputan', $item->id) }}"
                                class="btn btn-warning">Buat
                                Berita
                                ini</a>
                            {{-- <form action="{{ route('redaksi.liputan.delete', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"
                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                            </form> --}}
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

        $(document).ready(function() {
            $('#table2').DataTable();
        });
    </script>
@endsection
