@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen User | Edit User</h1>
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

    <div class="container mb-3">

        {{-- error input --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li class="mb-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('admin.user-management.update', $user->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_berita1" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" id="nama_berita1" aria-describedby="beritaHelp"
                    value="{{ $user->name }}">
                <div id="beritaHelp" class="form-text">
                    Masukkan Nama User.
                </div>
            </div>

            {{-- email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" id="email" aria-describedby="beritaHelp"
                    value="{{ $user->email }}">
                <div id="beritaHelp" class="form-text">
                    Masukkan Email User.
                </div>
            </div>

            {{-- password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" aria-describedby="beritaHelp">
                <div id="beritaHelp" class="form-text">
                    Masukkan Password User.
                </div>
            </div>

            {{-- konvirmasi password --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                    aria-describedby="beritaHelp">
                <div id="beritaHelp" class="form-text">
                    Masukkan Konfirmasi Password User.
                </div>
            </div>

            {{-- level --}}
            <div class="mb-3">
                <label for="level" class="form-label">Level</label>
                <select class="form-select" name="level" id="level" aria-label="Default select example"
                    aria-describedby="help">
                    {{-- <option selected disabled>Pilih Level</option> --}}
                    <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="reporter" {{ $user->level == 'reporter' ? 'selected' : '' }}>Reporter</option>
                    <option value="redaksi" {{ $user->level == 'redaksi' ? 'selected' : '' }}>Redaksi</option>
                    <option value="editor" {{ $user->level == 'jurnalis' ? 'selected' : '' }}>Jurnalis</option>
                </select>
                <div id="help" class="form-text">
                    Kategori level untuk user.
                </div>
            </div>

            {{-- Thumbnail berita --}}
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Avatar</label>
                <input class="form-control" name="avatar" type="file" id="thumbnail" accept=".jpg,.png,.jpeg">
            </div>

            {{-- preview image --}}
            <div class="mb-3">
                <label for="preview" class="form-label">Preview</label>
                <img src="{{ asset('img/avatar/' . $user->avatar) }}" alt="" id="preview" class="img-fluid">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection

@section('other-js')
    {{-- select2 --}}
    <script>
        $(document).ready(function() {
            $('#level').select2();
        });
    </script>

    {{-- preview image --}}
    <script>
        function previewImage() {
            const image = document.querySelector('#thumbnail');
            const imgPreview = document.querySelector('#preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>

    <script>
        CKEDITOR.replace('isi', {
            filebrowserUploadUrl: "{{ route('admin.berita.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            // buat ckeditor melihat gambar yang di masukkan melewati folder asset app
            // filebrowserBrowseUrl: "{{ asset('/img/berita/') }}",

        });
    </script>
@endsection
