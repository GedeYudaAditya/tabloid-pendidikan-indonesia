@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Kecamatan</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <a href="{{ route('admin.kecamatan.create') }}" class="btn btn-primary mb-3">Tambah Kecamatan</a>

        {{-- datatable --}}
        <table id="table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kecamatan</th>
                    <th>
                        Nama Kabupaten
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kecamatan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_kecamatan }}</td>
                        <td>{{ $item->kabupaten->nama_kabupaten }}</td>
                        <td>
                            <a href="{{ route('admin.kecamatan.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.kecamatan.delete', $item->id) }}" method="POST" class="d-inline">
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
