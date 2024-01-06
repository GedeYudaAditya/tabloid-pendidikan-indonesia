@extends('layouts.other.app')

@section('other-css')
    <style>
        .border-bottom-blue {
            border-bottom: 3px solid #0d6efd;
        }

        .hover-a:hover {
            background-color: #e9ecef;
        }

        .hover-kab:hover {
            background-color: #024bb9 !important;
        }

        .hover-kec:hover {
            background-color: #009ab9 !important;
        }

        .gradient-custom {
            /* fallback for old browsers */
            background: #4facfe;
            /* Chrome 10-25,
                                                                                                                                                                                                                                            Safari 5.1-6 */
            background: -webkit-linear-gradient(to bottom right, rgba(79, 172, 254,
                        1), rgba(0, 242, 254, 1));
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+,
                                                                                                                                                                                                                                            Safari 7+ */
            background: linear-gradient(to bottom right, rgba(79, 172, 254, 1), rgba(0,
                        242, 254, 1))
        }
    </style>
    <style>
        .social-btn-sp #social-links {
            margin: 0 auto;
            max-width: 500px;
        }

        .social-btn-sp #social-links ul li {
            display: inline-block;
        }

        .social-btn-sp #social-links ul li a {
            padding: 15px;
            border: 1px solid #ccc;
            margin: 1px;
            font-size: 30px;
        }

        #social-links {
            padding: 0px;
            display: inline-table;
        }

        #social-links ul {
            padding: 0px;
            /* margin: 0px; */
        }

        #social-links ul li {
            padding: 0px;
            display: inline;
        }

        #social-links ul li a {
            padding: 10px;
            /* border: 1px solid #ccc; */
            margin: 1px;
            font-size: 40px;
            /* background: #e3e3ea; */
            color: #0c356a;
        }
    </style>
@endsection

@section('content')
    <div class="row my-5">
        <div class="col-md-9">
            <div class="bg-light">
                <div class="d-flex">
                    {{-- <a href="#" class="text-decoration-none hover-kab bg-primary p-2 col-3 text-white"
                        style="font-weight: bolder; border-top-right-radius: 80px">
                        {{ $berita->kecamatan->kabupaten->nama_kabupaten }}
                    </a>
                    <a href="#" class="text-decoration-none hover-kec bg-info p-2 col-3 text-white"
                        style="font-weight: bolder; border-top-right-radius: 80px">
                        {{ $berita->kecamatan->nama_kecamatan }}
                    </a> --}}
                </div>
            </div>
            <div class="border p-3">
                <h1>
                    {{ $buku->judul }}
                </h1>
                <p class="text-muted">
                    {{-- convert to day_name, dd MM YY Hour:Minute --}}
                    {{ $buku->created_at->isoFormat('dddd, D MMMM Y HH:mm') }}
                </p>
                <hr>

                <div class="mt-3">
                    {{-- <img src="{{ asset('img/berita/' . $buku->gambar) }}" style="width: 50%; float: left" class="me-3"
                        alt="{{ $buku->gambar }}"> --}}
                    @if ($buku->gambar != null)
                        {{-- slider --}}
                        @if (is_array(json_decode($buku->gambar)))
                            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach (json_decode($buku->gambar) as $item)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                            <img src="{{ asset('img/buku/' . $item) }}" class="d-block w-100"
                                                style="height: 400px; object-fit: cover; object-position: center"
                                                alt="{{ asset('img/buku/' . $item) }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden"></span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden"></span>
                                </button>
                            </div>
                        @else
                            <img src="{{ asset('img/buku/' . $buku->gambar) }}" style="width: 100%;" class="me-3"
                                alt="{{ $buku->gambar }}">
                        @endif
                    @else
                        <img src="" alt="" id="preview" class="img-fluid">
                    @endif

                    {{-- author --}}
                    <p class="mt-3">
                        <span class="text-muted">Dibuat Oleh :</span> {{ $buku->penulis }}
                        <i class="me-3"></i>
                    </p>

                    <hr>

                    <div class="mt-3" style="text-align: justify">
                        {!! $buku->sinopsis !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="px-2 pt-2">
                <h5><span class="border-bottom-blue">berita</span> <span
                        class="text-danger">{{ Str::upper($berita->kecamatan->nama_kecamatan) }}</span></h5>
            </div>
            <div class="p-3">
                {{-- list berita untuk kecamatan --}}
                @php
                    $beritaKecamatan = $berita->kecamatan->berita
                        ->where('status', 'publish')
                        ->sortByDesc('created_at')
                        ->take(5);
                @endphp


                @forelse ($beritaKecamatan as $item)
                    <a href="{{ route('guest.berita.detail', $item->slug) }}" class="row mb-3 text-decoration-none hover-a">
                        <div class="col-7 my-1">
                            @php
                                if (strlen($item->judul) > 40) {
                                    $judul = substr($item->judul, 0, 20) . '...';
                                } else {
                                    $judul = $item->judul;
                                }
                            @endphp
                            <b>
                                {{ $judul }}
                            </b>
                            {{-- diff --}}
                            <p class="text-muted" style="font-size: 11px">
                                <i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="col-5 my-1">
                            {{-- gambar --}}
                            @if ($item->gambar != null)
                                {{-- slider --}}
                                @if (is_array(json_decode($item->gambar)))
                                    <img src="{{ asset('img/berita/' . json_decode($item->gambar)[0]) }}"
                                        class="w-100 img-fluid img-thumbnail"
                                        alt="{{ asset('img/berita/' . json_decode($item->gambar)[0]) }}">
                                @else
                                    <img src="{{ asset('img/berita/' . json_decode($item->gambar)) }}"
                                        class="w-100 img-fluid img-thumbnail" alt="">
                                @endif
                            @else
                                <img src="{{ asset('img/berita/' . $item->gambar) }}" class="w-100 img-fluid img-thumbnail"
                                    alt="{{ asset('img/berita/' . $item->gambar) }}">
                            @endif
                        </div>
                    </a>
                @empty
                @endforelse

            </div>

            <div class="px-2 pt-2">
                <h5><span class="text-danger">{{ Str::upper($berita->kecamatan->kabupaten->nama_kabupaten) }}</span></h5>
            </div>

            <div class="px-3 pb-3">
                {{-- list kecamatan --}}
                @php
                    $kecamatan = $berita->kecamatan->kabupaten->kecamatan->sortByDesc('created_at')->take(5);
                @endphp


                @forelse ($kecamatan as $item)
                    <a href="#" class="mb-3 text-decoration-none">
                        <div class="w-100 hover-a p-1">
                            <b>{{ $item->nama_kecamatan }}</b>
                        </div>
                    </a>
                @empty
                @endforelse

            </div>
        </div>
    </div>
@endsection

@section('other-js')
    <script>
        $(document).ready(function() {
            // like button
            $('#like-button').click(function() {
                console.log('like');
                var likeText = $('#like-text').text();
                likeText = parseInt(likeText);
                $.ajax({
                    url: "{{ route('like', $berita->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.status) {
                            // chack if user already like berita
                            if (response.like) {
                                $('#like-button').children().removeClass('text-muted').addClass(
                                    'text-danger');
                                // change text
                                $('#like-text').text(likeText + 1);
                            } else {
                                $('#like-button').children().removeClass('text-danger')
                                    .addClass(
                                        'text-muted');
                                // change text
                                $('#like-text').text(likeText - 1);
                            }
                        }
                    }
                });
            });
        });
    </script>

    {{-- reply button --}}
    <script>
        $(document).ready(function() {
            $('.reply-button').click(function() {
                var id = $(this).val();
                console.log(id);
                // $.ajax({
                //     url: "{{ route('reply', $berita->id) }}",
                //     type: "POST",
                //     data: {
                //         _token: "{{ csrf_token() }}",
                //         parent_id: id
                //     },
                //     // success: function(response) {
                //     //     if (response.status) {
                //     //         console.log(response);
                //     //     }
                //     // }
                // });
                // generate form
                var form = `
                    <form action="{{ route('reply', $berita->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="komentar" class="form-label text-white">Komentar</label>
                            <textarea class="form-control" id="komentar" rows="3" name="komentar"></textarea>
                        </div>
                        <input type="hidden" name="parent_id" value="${id}">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                `;
                // append form
                $(this).parent().parent().parent().append(form);
            });
        });
    </script>

    {{-- modal share --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">
                        Share to social media
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    {!! Share::page(url(route('guest.berita.detail', $berita->slug)))->facebook()->twitter()->whatsapp()->telegram() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
