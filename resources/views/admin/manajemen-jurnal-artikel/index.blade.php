@extends('layouts.admin.app')

{{-- @dd($liputan) --}}

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Jurnal dan Artikel</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container">
        <h4>
            List Artikel yang belum dipublikasikan
        </h4>

        {{-- datatable --}}
        <table id="table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Artikel</th>
                    <th>
                        Status
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($artikel as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('admin.jurnal-artikel.artikel.show', $item->id) }}">{{ $item->judul }}</a>
                        </td>
                        <td class="text-center">
                            @if ($item->status == 'draft')
                                <span class="badge bg-secondary text-light">{{ Str::upper($item->status) }}</span>
                            @else
                                <span class="badge bg-success text-light">{{ Str::upper($item->status) }}</span>
                            @endif
                        </td>
                        <td>
                            {{-- ubah status --}}
                            <form action="{{ route('admin.jurnal-artikel.artikel.update-status', $item->id) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="publish">
                                @if ($item->status == 'draft')
                                    <button class="btn btn-success"
                                        onclick="return confirm('Apakah anda yakin ingin mempublikasikan artikel ini?')">Publikasikan</button>
                                @else
                                    <button class="btn btn-secondary"
                                        onclick="return confirm('Apakah anda yakin ingin mempublikasikan artikel ini?')">
                                        Buat Draft
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>
            List Jurnal yang belum dipublikasikan
        </h4>

        {{-- datatable --}}
        <table id="table2" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Jurnal</th>
                    <th>
                        Status
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jurnal as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('admin.jurnal-artikel.jurnal.show', $item->id) }}">{{ $item->judul }}</a>
                        </td>
                        <td class="text-center">
                            @if ($item->status == 'draft')
                                <span class="badge bg-secondary text-light">{{ Str::upper($item->status) }}</span>
                            @else
                                <span class="badge bg-success text-light">{{ Str::upper($item->status) }}</span>
                            @endif
                        </td>
                        <td>
                            {{-- ubah status --}}
                            <form action="{{ route('admin.jurnal-artikel.jurnal.update-status', $item->id) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="publish">
                                @if ($item->status == 'draft')
                                    <button class="btn btn-success"
                                        onclick="return confirm('Apakah anda yakin ingin mempublikasikan jurnal ini?')">Publikasikan</button>
                                @else
                                    <button class="btn btn-secondary"
                                        onclick="return confirm('Apakah anda yakin ingin mempublikasikan jurnal ini?')">
                                        Buat Draft
                                    </button>
                                @endif
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

        $(document).ready(function() {
            $('#table2').DataTable();
        });
    </script>
@endsection
