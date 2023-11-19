@extends('layouts.admin.app')

{{-- @dd($liputan) --}}

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Manajemen Event</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <h4>
            List Event
        </h4>
        <a href="{{ route('admin.event.create') }}" class="btn btn-primary mb-3">Tambah Event</a>

        {{-- datatable --}}
        <table id="table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Event</th>
                    <th>
                        Jenis
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td class="text-center">
                            {{-- draft and publish --}}
                            @if ($item->jenis == 'seminar')
                                <span class="badge bg-secondary text-light">{{ Str::upper($item->jenis) }}</span>
                            @else
                                <span class="badge bg-success text-light">{{ Str::upper($item->jenis) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.event.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.event.delete', $item->id) }}" method="POST" class="d-inline">
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
