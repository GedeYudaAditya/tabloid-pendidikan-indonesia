@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Event | Edit Event</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <div class="container mb-3">
        {{-- error input --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>
            </div>
        @endif
        <form action="{{ route('admin.event.create') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_berita1" class="form-label">Judul Event</label>
                <input type="text" name="judul" class="form-control" id="nama_berita1" aria-describedby="beritaHelp">
                <div id="beritaHelp" class="form-text">
                    Masukkan judul Event.
                </div>
            </div>

            {{-- Thumbnail berita --}}
            <div class="mb-3">
                <label for="thumbnail" class="form-label">File Foto Artikel</label>
                <input class="form-control" name="gambar" type="file" id="thumbnail" accept=".jpg,.png,.jpeg"
                    onchange="previewImage()">
            </div>

            {{-- preview image --}}
            <div class="mb-3">
                <label for="preview" id="images" class="form-label">Preview</label>
                <img src="" alt="" id="preview" class="img-fluid">
                <div class="multiple row row-cols-1 row-cols-md-3 g-4" style="flex-wrap: nowrap; overflow: auto">

                </div>
            </div>

            {{-- isi --}}
            <div class="mb-3">
                <label for="isi" class="form-label">Isi</label>
                <textarea class="form-control" name="isi" id="isi" rows="3"></textarea>
            </div>

            {{-- status --}}
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <select class="form-select" name="jenis" id="jenis">
                    <option disabled selected>Pilih Jenis</option>
                    <option value="seminar">Seminar</option>
                    <option value="workshop">Workshop</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection

@section('other-js')
    {{-- preview image --}}
    <script>
        function previewImage() {
            const image = document.querySelector('#thumbnail');
            const imgPreview = document.querySelector('#preview');
            const multiple = document.querySelector('.multiple');

            imgPreview.style.display = 'block';

            console.log(image.files.length);

            // multiple image
            if (image.files.length > 1) {
                for (let i = 0; i < image.files.length; i++) {
                    const oFReader = new FileReader();
                    oFReader.readAsDataURL(image.files[i]);

                    oFReader.onload = function(oFREvent) {
                        // buat image tag setelah didalam div class multiple
                        multiple.innerHTML += '<img src="' + oFREvent.target.result +
                            '" alt="" class="img-fluid" object-fit: cover;">';
                    }
                }
            } else {
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }
        }
    </script>

    <script>
        CKEDITOR.replace('isi', {
            filebrowserUploadUrl: "{{ route('reporter.liputan.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            // buat ckeditor melihat gambar yang di masukkan melewati folder asset app
            // filebrowserBrowseUrl: "{{ asset('/img/berita/') }}",

        });
    </script>
@endsection
