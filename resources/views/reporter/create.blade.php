@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Liputan | Tambah Liputan</h1>
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

        <form action="{{ route('reporter.liputan.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_berita1" class="form-label">Judul Liputan</label>
                <input type="text" name="judul" class="form-control" id="nama_berita1" aria-describedby="beritaHelp">
                <div id="beritaHelp" class="form-text">
                    Masukkan judul berita.
                </div>
            </div>

            {{-- Option Kab --}}
            <div class="mb-3">
                <label for="kabupaten" class="form-label">Pilih Kabupaten</label>
                <select class="form-select" name="kabupaten_id" id="kabupaten" aria-label="Default select example"
                    aria-describedby="help">
                    <option selected disabled>Pilih kabupaten</option>
                    @foreach ($kabupaten as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kabupaten }}</option>
                    @endforeach
                </select>
                <div id="help" class="form-text">
                    Kategori kabupaten untuk berita di publikasikan.
                </div>
            </div>

            {{-- option untuk pilih kecamatan --}}
            <div class="mb-3">
                <label for="kecamatan" class="form-label">Pilih Kecamatan</label>
                <select class="form-select" name="kecamatan_id" id="kecamatan" aria-label="Default select example"
                    aria-describedby="help">
                    <option selected disabled>Pilih Kecamatan</option>
                </select>
                <div id="help" class="form-text">
                    Kategori kecamatan untuk berita di publikasikan.
                </div>
            </div>

            {{-- Thumbnail berita --}}
            <div class="mb-3">
                <label for="thumbnail" class="form-label">File Foto Liputan</label>
                <input class="form-control" multiple name="gambar[]" type="file" id="thumbnail" accept=".jpg,.png,.jpeg"
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
                <label for="isi" class="form-label">Isi Berita</label>
                <textarea class="form-control" name="isi" id="isi" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection

@section('other-js')
    {{-- select2 --}}

    <script>
        $(document).ready(function() {
            $('#kabupaten').select2();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#kecamatan').select2();
        });
    </script>

    <script>
        $(document).ready(function() {
            // check if kabupaten_id is selected
            $('#kabupaten').change(function() {
                // get kabupaten_id
                var kabupaten_id = $(this).val();
                // empty kecamatan_id
                $('#kecamatan').empty();
                // ajax call
                $.ajax({
                    url: "{{ route('api.get.kecamatan') }}",
                    type: 'GET',
                    data: {
                        kabupaten_id: kabupaten_id
                    },
                    success: function(response) {
                        // console.log(response);
                        // check if response is empty
                        if (response.length == 0) {
                            // append kecamatan_id
                            $('#kecamatan').append(
                                '<option selected disabled>Pilih Kecamatan</option>'
                            );
                        } else {
                            // append kecamatan_id
                            $('#kecamatan').append(
                                '<option selected disabled>Pilih Kecamatan</option>'
                            );
                            // loop through response
                            $.each(response, function(key, value) {
                                // append kecamatan_id
                                $('#kecamatan').append(
                                    '<option class="' + value.kabupaten_id +
                                    '" value="' +
                                    value.id + '">' + value.nama_kecamatan +
                                    '</option>'
                                );
                            });
                        }
                    }
                });
            });
        });
    </script>

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
