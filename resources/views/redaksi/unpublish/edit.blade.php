@extends('layouts.admin.app')

{{-- @dd($liputans) --}}

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Liputan dan Berita | Tambah Berita</h1>
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

        <form action="{{ route('redaksi.berita-unpublish.update', $berita->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_berita1" class="form-label">Judul Berita</label>
                <input type="text" name="judul" class="form-control" id="nama_berita1" aria-describedby="beritaHelp"
                    value="{{ $berita->judul }}">
                <div id="beritaHelp" class="form-text">
                    Masukkan judul berita.
                </div>
            </div>

            {{-- option for liputan --}}
            <div class="mb-3">
                <label for="liputan" class="form-label">Pilih Liputan</label>
                <select class="form-select" name="liputan_id" id="liputan" aria-label="Default select example"
                    aria-describedby="help">
                    {{-- <option selected disabled>Pilih Liputan</option> --}}
                    @foreach ($liputans as $item)
                        <option value="{{ $item->id }}" {{ $berita->liputan->id == $item->id ? 'selected' : '' }}>
                            {{ $item->judul }}</option>
                    @endforeach
                </select>
                <div id="help" class="form-text">
                    Kategori liputan untuk berita di publikasikan.
                </div>
            </div>

            {{-- volume berita --}}
            <div class="mb-3">
                <label for="volume" class="form-label">Volume Berita</label>
                <select class="form-select" name="volume" id="volume" aria-label="Default select example"
                    aria-describedby="volumeHelp">
                    {{-- <option selected disabled>Pilih Volume Berita</option> --}}
                    <option value="V1" {{ $berita->volume == 'V1' ? 'selected' : '' }}>Volume 1</option>
                    <option value="V2" {{ $berita->volume == 'V2' ? 'selected' : '' }}>Volume 2</option>
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
                @if ($berita->gambar != null)
                    @if (is_array(json_decode($berita->gambar)))
                        <div class="multiple row row-cols-1 row-cols-md-3 g-4" style="flex-wrap: nowrap; overflow: auto">
                            @foreach (json_decode($berita->gambar) as $item)
                                <img src="{{ asset('/img/berita/' . $item) }}" alt="" class="img-fluid">
                            @endforeach
                        </div>
                    @else
                        <img src="{{ asset('/img/berita/' . $berita->gambar) }}" alt="" id="preview"
                            class="img-fluid">
                    @endif
                @else
                    <img src="" alt="" id="preview" class="img-fluid">
                @endif
                <div class="multiple row row-cols-1 row-cols-md-3 g-4" style="flex-wrap: nowrap; overflow: auto">

                </div>
            </div>

            {{-- download image from liputans --}}
            {{-- preview image --}}
            <div class="mb-3">
                <label class="form-label mt-3">Download Image From Reporter</label>
                <div id="images-download" class="row row-cols-1 row-cols-md-6 g-4"
                    style="flex-wrap: nowrap; overflow: auto"></div>
            </div>

            {{-- isi --}}
            <div class="mb-3">
                <label for="isi" class="form-label">Isi Berita</label>
                <div class="row">
                    {{-- jika ada saran maka perluhatkan saran --}}
                    @if ($berita->saranRevisi)
                        @foreach ($berita->saranRevisi as $item)
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Admin</strong> memberikan saran revisi pada
                                {{ $item->created_at->format('d M Y') }}.
                                <hr style="margin-top: 0;">
                                <p>{{ $item->isi }}</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif
                    <div class="col-md-5">
                        {{-- <p>Isi Liputan</p> --}}
                        <div name="liputan" disabled style="width: 100%; height: 300px; overflow-y: auto" id="liputan-text"
                            readonly>
                            {!! $berita->liputan->isi !!}</div>
                    </div>
                    <div class="col-md-7">
                        {{-- <p>Isi yang di telah edit</p> --}}
                        <textarea class="form-control" name="isi" id="isi" rows="3">{!! $berita->isi !!}</textarea>
                    </div>
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
            $('#liputan').select2();
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
                        for (let i = 0; i < liputan.gambar.length; i++) {
                            // make element for download image in #images-download
                            $('#images-download').append(
                                `<a href="{{ asset('/img/liputan/') }}/${liputan.gambar[i]}" target="_blank">
                                    <img src="{{ asset('/img/liputan/') }}/${liputan.gambar[i]}" alt="" class="img-fluid img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
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
