@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Berita</h1>
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
        {{-- <a href="{{ route('admin.berita.create') }}" class="btn btn-primary mb-3">Tambah Berita</a> --}}

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
                            {{-- <a href="{{ route('admin.berita.edit', $item->id) }}" class="btn btn-warning">Edit</a> --}}
                            @if ($item->status == 'draft' || $item->status == 'revisi')
                                <form action="{{ route('admin.berita.publish', $item->id) }}" method="POST"
                                    class="text-center" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="publish">
                                    <button class="btn btn-success">Publikasi</button>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">
                                        Tolak
                                    </button>
                                </form>

                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.berita.tolak', $item->slug) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Kirimkan Saran
                                                        Refisi Anda</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1" class="form-label">Saran
                                                            Revisi</label>
                                                        <textarea name="saran_revisi" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($item->status == 'publish')
                                <form action="{{ route('admin.berita.publish', $item->id) }}" class="text-center"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="draft">
                                    <button class="btn btn-secondary">Draf</button>
                                </form>
                            @else
                                {{-- <form action="{{ route('admin.berita.publish', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="draft">
                                    <button class="btn btn-secondary">Draf</button>
                                </form> --}}
                                {{-- badge --}}
                                <span class="badge bg-secondary text-light">Sedang Direvisi</span>
                            @endif
                            {{-- <form action="{{ route('admin.berita.delete', $item->id) }}" method="POST" class="d-inline">
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
    </script>
@endsection
