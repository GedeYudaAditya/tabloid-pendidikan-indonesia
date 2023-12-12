@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Liputan dan Berita | Tambah Berita Lama</h1>
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

        <form action="{{ route('redaksi.berita-unpublish.old-store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_berita1" class="form-label">Judul Berita</label>
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

            {{-- volume berita --}}
            <div class="mb-3">
                <label for="volume" class="form-label">Volume Berita</label>
                <select class="form-select" name="volume" id="volume" aria-label="Default select example"
                    aria-describedby="volumeHelp">
                    <option selected disabled>Pilih Volume Berita</option>
                    <option value="V1">Volume 1</option>
                    <option value="V2">Volume 2</option>
                </select>
                <div id="volumeHelp" class="form-text">
                    Masukkan volume berita.
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

            {{-- download image from liputans --}}
            {{-- preview image --}}
            <div class="mb-3">
                <label class="form-label">Download Image From Reporter</label>
                <div id="images-download" class="row row-cols-1 row-cols-md-6 g-4"
                    style="flex-wrap: nowrap; overflow: auto"></div>
            </div>

            {{-- isi --}}
            <div class="mb-3">
                <label for="isi" class="form-label">Isi Berita</label>
                <div class="row">
                    <div class="col-12">
                        {{-- <p>Isi yang di telah edit</p> --}}
                        <textarea class="form-control" name="isi" id="isi" rows="3"></textarea>
                    </div>
                </div>
            </div>

            {{-- created_at --}}
            <div class="mb-3">
                <label for="created_at" class="form-label">Tanggal Berita</label>
                <input type="date" name="created_at" class="form-control" id="created_at" aria-describedby="beritaHelp">
                <div id="beritaHelp" class="form-text">
                    Masukkan tanggal berita.
                </div>
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

    <script>
        $(document).ready(function() {
            // check if liputan is selected
            $('#liputan').change(function() {
                // get liputan id
                const liputanId = $(this).val();

                // empty #images-download
                $('#images-download').empty();

                // get base url
                const baseUrl = window.location.origin;

                // get element #liputan-text
                const liputanText = document.querySelector('#liputan-text');

                // get data from liputan
                $.ajax({
                    url: baseUrl + "/api/get-liputan-by-id/" + liputanId,
                    method: 'GET',
                    success: function(liputan) {
                        // console.log(liputan);

                        // set value for #nama_berita1
                        $('#nama_berita1').val(liputan.data.judul);

                        // set innerHTML for #isi
                        // hilangkan tag pada isi
                        const isi = liputan.data.isi.replace(/<\/?[^>]+(>|$)/g, "");
                        liputanText.innerHTML = isi;

                        // string to array convert
                        console.log(liputan)

                        // download image
                        if (liputan.gambar.length != undefined) {
                            for (let i = 0; i < liputan.gambar.length; i++) {
                                // make element for download image in #images-download
                                $('#images-download').append(
                                    `<a href="{{ asset('/img/liputan/') }}/${liputan.gambar[i]}" target="_blank">
                                        <img src="{{ asset('/img/liputan/') }}/${liputan.gambar[i]}" alt="" class="img-fluid img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                        </a>`
                                );
                            }
                        } else {
                            // make element for download image in #images-download
                            $('#images-download').append(
                                `<a href="{{ asset('/img/liputan/') }}/${liputan.gambar}" target="_blank">
                                    <img src="{{ asset('/img/liputan/') }}/${liputan.gambar}" alt="" class="img-fluid img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>`
                            );
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
            filebrowserUploadMethod: 'form',
            // buat ckeditor melihat gambar yang di masukkan melewati folder asset app
            // filebrowserBrowseUrl: "{{ asset('/img/berita/') }}",

        });
    </script>
@endsection
