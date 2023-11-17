@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Berita | Edit Berita</h1>
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
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>
            </div>
        @endif

        <form action="{{ route('admin.berita.update', $berita->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_berita1" class="form-label">Judul Berita</label>
                <input type="text" name="judul" class="form-control" id="nama_berita1" aria-describedby="beritaHelp"
                    value="{{ $berita->judul }}">
                <div id="beritaHelp" class="form-text">
                    Masukkan judul berita.
                </div>
            </div>

            {{-- option untuk pilih kecamatan --}}
            <div class="mb-3">
                <label for="kecamatan" class="form-label">Pilih Kecamatan</label>
                <select class="form-select" name="kecamatan_id" id="kecamatan" aria-label="Default select example"
                    aria-describedby="help">
                    <option selected value="{{ $berita->kecamatan->id }}">
                        {{ $berita->kecamatan->nama_kecamatan }} </option>

                    @php
                        // kecuali kabupaten yang sudah dipilih
                        $kecamatan = App\Models\Kecamatan::where('id', '!=', $berita->kecamatan->id)->get();
                    @endphp

                    @foreach ($kecamatan as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kecamatan }}</option>
                    @endforeach
                </select>
                <div id="help" class="form-text">
                    Kategori kecamatan untuk berita di publikasikan.
                </div>
            </div>

            {{-- Thumbnail berita --}}
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail Berita</label>
                <input class="form-control" name="gambar" type="file" id="thumbnail" accept=".jpg,.png,.jpeg">
            </div>

            {{-- preview image --}}
            <div class="mb-3">
                <label for="preview" class="form-label">Preview</label>
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
            </div>

            {{-- isi --}}
            <div class="mb-3">
                <label for="isi" class="form-label">Isi Berita</label>
                <textarea class="form-control" name="isi" id="isi" rows="3">{!! $berita->isi !!}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection

@section('other-js')
    {{-- select2 --}}
    <script>
        $(document).ready(function() {
            $('#kecamatan').select2();
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
            // cloudServices_uploadUrl: 'https://your-organization-id.cke-cs.com/easyimage/upload/'
        });
    </script>
@endsection
