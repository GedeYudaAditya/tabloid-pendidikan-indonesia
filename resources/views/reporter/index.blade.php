@extends('layouts.admin.app')

{{-- @dd($liputan) --}}

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Liputan</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <a href="{{ route('reporter.liputan.create') }}" class="btn btn-primary mb-3">Tambah Liputan</a>

        {{-- datatable --}}
        <table id="table" class="table table-striped table-bordered">
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
                @foreach ($liputan as $item)
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
                            @if ($item->status == 'mengantri')
                                <a href="{{ route('reporter.liputan.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('reporter.liputan.delete', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger"
                                        onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            @else
                                <a href="{{ route('reporter.liputan.show', $item->id) }}"
                                    class="btn btn-primary btn-sm">Lihat</a>
                            @endif
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
