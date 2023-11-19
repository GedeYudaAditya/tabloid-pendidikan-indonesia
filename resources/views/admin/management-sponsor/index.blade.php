@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Managemen Sponsor</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <a href="{{ route('admin.management-sponsor.create') }}" class="btn btn-primary mb-3">Tambah Data</a>

        {{-- datatable --}}
        <table id="table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Link</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sponsor as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td><a href="{{ $item->link }}">{{ $item->link }}</a></td>
                        <td>
                            <a href="{{ route('admin.management-sponsor.edit', $item->id) }}"
                                class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.management-sponsor.delete', $item->id) }}" method="POST"
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
