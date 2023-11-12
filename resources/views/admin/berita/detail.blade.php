@extends('layouts.admin.app')

@section('other-css')
    {{-- select2 --}}
    <style>
        /* Difference Highlighting and Strike-through
                                                                                    ------------------------------------------------ */
        ins {
            color: #01a817;
            font-weight: bold;
            background-color: #86ff86;
            text-decoration: none;
        }

        del {
            color: #AA3333;
            font-weight: bold;
            background-color: #ffeaea;
            text-decoration: line-through;
        }

        /* Image Diffing
                                                                                    ------------------------------------------------ */
        del.diffimg.diffsrc {
            display: inline-block;
            position: relative;
        }

        del.diffimg.diffsrc:before {
            position: absolute;
            content: "";
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(to left top,
                    rgba(255, 0, 0, 0),
                    rgba(255, 0, 0, 0) 49.5%,
                    rgba(255, 0, 0, 1) 49.5%,
                    rgba(255, 0, 0, 1) 50.5%), repeating-linear-gradient(to left bottom,
                    rgba(255, 0, 0, 0),
                    rgba(255, 0, 0, 0) 49.5%,
                    rgba(255, 0, 0, 1) 49.5%,
                    rgba(255, 0, 0, 1) 50.5%);
        }

        /* List Diffing
                                                                                    ------------------------------------------------ */
        /* List Styles */
        .diff-list {
            list-style: none;
            counter-reset: section;
            display: table;
        }

        .diff-list>li.normal,
        .diff-list>li.removed,
        .diff-list>li.replacement {
            display: table-row;
        }

        .diff-list>li>div {
            display: inline;
        }

        .diff-list>li.replacement:before,
        .diff-list>li.new:before {
            color: #333333;
            background-color: #eaffea;
            text-decoration: none;
        }

        .diff-list>li.removed:before {
            counter-increment: section;
            color: #AA3333;
            background-color: #ffeaea;
            text-decoration: line-through;
        }

        /* List Counters / Numbering */
        .diff-list>li.normal:before,
        .diff-list>li.removed:before,
        .diff-list>li.replacement:before {
            width: 15px;
            overflow: hidden;
            content: counters(section, ".") ". ";
            display: table-cell;
            text-indent: -1em;
            padding-left: 1em;
        }

        .diff-list>li.normal:before,
        li.replacement+li.replacement:before,
        .diff-list>li.replacement:first-child:before {
            counter-increment: section;
        }

        ol.diff-list li.removed+li.replacement {
            counter-increment: none;
        }

        ol.diff-list li.removed+li.removed+li.replacement {
            counter-increment: section -1;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -2;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -3;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -4;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -5;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -6;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -7;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -8;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -9;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -10;
        }

        ol.diff-list li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.removed+li.replacement {
            counter-increment: section -11;
        }

        /* Exception Lists */
        ul.exception,
        ul.exception li:before {
            list-style: none;
            content: none;
        }

        .diff-list ul.exception ol {
            list-style: none;
            counter-reset: exception-section;
            /* Creates a new instance of the section counter with each ol element */
        }

        .diff-list ul.exception ol>li:before {
            counter-increment: exception-section;
            content: counters(exception-section, ".") ".";
        }

        .text-justify {
            text-align: justify;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Berita | Detail Berita</h1>
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
        <h1>{{ $berita->judul }}
            {{-- check apakah ada revisi --}}
            @if ($berita->saranRevisi->count() > 0)
                <small>
                    {{-- badge --}}
                    <span class="badge bg-info">Revisi</span>
                    {{-- Banyak revisi dilalui --}}
                    @if ($berita->saranRevisi->count() > 1)
                        <span class="badge bg-warning">{{ $berita->saranRevisi->count() }} kali revisi</span>
                    @endif
                </small>
            @endif
        </h1>
        <p class="text-muted">Dibuat pada {{ $berita->created_at->format('d M Y') }}</p>
        <p class="text-muted">Diperbarui pada {{ $berita->updated_at->format('d M Y') }}</p>
        <span class="text-muted">Dibuat Oleh Redaksi :</span> {{ $berita->user->name }} | <span class="text-muted">Diliput
            Oleh :</span> {{ $berita->liputan->reporter->name }}
        <hr>

        {{-- thumbnail --}}
        <div class="mb-3">
            @if ($berita->gambar != null)
                {{-- slider --}}
                @if (is_array(json_decode($berita->gambar)))
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach (json_decode($berita->gambar) as $item)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ asset('img/berita/' . $item) }}" class="d-block w-100"
                                        style="height: 400px; object-fit: cover; object-position: center"
                                        alt="{{ asset('img/berita/' . $item) }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                    </div>
                @else
                    <img src="{{ asset('/img/berita/' . json_decode($berita->gambar)) }}" alt="{{ $berita->gambar }}"
                        id="preview" class="img-fluid">
                @endif
            @else
                <img src="" alt="" id="preview" class="img-fluid">
            @endif
        </div>

        {{-- isi berita --}}
        {{-- check jika berita memiliki revisi --}}
        {{-- @dd($berita->saranRevisi) --}}
        @if ($berita->saranRevisi->count() > 0)
            @php
                $htmlDiff = new Caxy\HtmlDiff\HtmlDiff($berita->old_isi, $berita->isi);
            @endphp
            <div class="mb-3 p-5 text-justify">
                {!! $htmlDiff->build() !!}
            </div>
        @else
            <div class="mb-3 p-5 text-justify">
                {!! $berita->isi !!}
            </div>
        @endif
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
