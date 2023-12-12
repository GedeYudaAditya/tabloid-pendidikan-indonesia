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
                    <a href="#" class="text-decoration-none hover-kab bg-primary p-2 col-3 text-white"
                        style="font-weight: bolder; border-top-right-radius: 80px">
                        {{ $berita->kecamatan->kabupaten->nama_kabupaten }}
                    </a>
                    <a href="#" class="text-decoration-none hover-kec bg-info p-2 col-3 text-white"
                        style="font-weight: bolder; border-top-right-radius: 80px">
                        {{ $berita->kecamatan->nama_kecamatan }}
                    </a>
                </div>
            </div>
            <div class="border p-3">
                <h1>
                    {{ $berita->judul }}
                </h1>
                <p class="text-muted">
                    {{-- convert to day_name, dd MM YY Hour:Minute --}}
                    {{ $berita->created_at->isoFormat('dddd, D MMMM Y HH:mm') }}
                </p>
                <b>
                    @if ($berita->volume == 'V1')
                        <span class="badge bg-primary text-light">Volume 1</span>
                    @else
                        <span class="badge bg-warning text-light">Volume 2</span>
                    @endif
                </b>
                <hr>

                <div class="mt-3">
                    {{-- <img src="{{ asset('img/berita/' . $berita->gambar) }}" style="width: 50%; float: left" class="me-3"
                        alt="{{ $berita->gambar }}"> --}}
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
                            <img src="{{ asset('img/berita/' . json_decode($berita->gambar)) }}"
                                style="width: 50%; height:200px; float: left; object-fit: cover; object-position: center;"
                                class="me-3" alt="{{ $berita->gambar }}">
                        @endif
                    @else
                        <img src="" alt="" id="preview" class="img-fluid">
                    @endif

                    {{-- author --}}
                    <p class="mt-3">
                        <span class="text-muted">Dibuat Oleh Redaksi :</span> {{ $berita->user->name }} | <span
                            class="text-muted">Diliput Oleh :</span> {{ $berita->liputan->reporter->name }}
                        <i class="me-3"></i>
                        {{-- icon comment --}}
                        <i class="fas fa-comment text-muted"></i>
                        {{ count($berita->komentar) }}
                        <i class="me-3"></i>
                        {{-- love icon and can be clicked if user already login --}}
                        @if (Auth::check())
                            @if (Auth::user()->likes->where('berita_id', $berita->id)->first())
                                <button type="button" style="background: transparent; border: none;" id="like-button"><i
                                        class="fas fa-heart text-danger"></i></button>
                            @else
                                <button type="button" style="background: transparent; border: none;" id="like-button"><i
                                        class="far fa-heart text-muted"></i></button>
                            @endif
                        @else
                            <a href="{{ route('auth') }}"><i class="far fa-heart text-muted"></i></a>
                        @endif
                        <span id="like-text">{{ $berita->like }}</span>
                        <i class="me-3"></i>
                        {{-- shere icon --}}
                        <button type="button" class="shere-button" data-bs-toggle="modal"
                            url="{{ route('guest.berita.detail', $berita->slug) }}" data-bs-target="#exampleModal">
                            <i class="fas fa-share-alt shere-icon text-muted"></i>
                            <span>Share</span>
                        </button>
                    </p>

                    <hr>

                    <div class="mt-3" style="text-align: justify">
                        {!! $berita->isi !!}
                    </div>
                </div>
            </div>
            {{-- comment sesction --}}
            <div class="p-3 border mt-3 gradient-custom">
                <h5 class="text-white">Kolom Komentar</h5>
                <hr>
                {{-- comment form --}}
                <form action="{{ route('comment', $berita->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="komentar" class="form-label text-white">Komentar</label>
                        <textarea class="form-control" id="komentar" rows="3" name="komentar"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
                <hr>
                {{-- comment list --}}
                <div class="card">
                    <div class="card-body p-4">

                        <div class="row">
                            <div class="col">
                                @forelse ($comments as $comment)
                                    <div class="d-flex flex-start mb-3">
                                        {{-- https://ui-avatars.com/api/?name= --}}
                                        @if (isset($comment->user->avatar))
                                            <img class="rounded-circle shadow-1-strong me-3"
                                                src="{{ asset('img/avatar/' . Auth::user()->avatar) }}" alt="avatar"
                                                width="65" height="65" />
                                        @else
                                            <img class="rounded-circle shadow-1-strong me-3"
                                                src="https://ui-avatars.com/api/?background=random&name={{ $comment->user->name }}"
                                                alt="avatar" width="65" height="65" />
                                        @endif
                                        <div class="flex-grow-1 flex-shrink-1">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-1">
                                                        {{ $comment->user->name }} <span class="small">-
                                                            {{ $comment->created_at->diffForHumans() }}
                                                        </span>
                                                    </p>
                                                    <button class="text-link reply-button" type="button"
                                                        value="{{ $comment->id }}"
                                                        style="background: transparent; border: none;"><i
                                                            class="fas fa-reply fa-xs"></i><span class="small">
                                                            reply</span></button>
                                                </div>
                                                <p class="small mb-0">
                                                    {{ $comment->isi }}
                                                </p>
                                            </div>

                                            {{-- jika ada replay --}}
                                            @foreach ($comment->replayKomentar as $item)
                                                <div class="d-flex flex-start mt-4">
                                                    <a class="me-3" href="#">
                                                        @if ($item->user->avatar)
                                                            <img class="rounded-circle shadow-1-strong me-3"
                                                                src="{{ asset('img/avatar/' . $item->user->avatar) }}"
                                                                alt="avatar" width="65" height="65" />
                                                        @else
                                                            <img class="rounded-circle shadow-1-strong me-3"
                                                                src="https://ui-avatars.com/api/?background=random&name={{ $item->user->name }}"
                                                                alt="avatar" width="65" height="65" />
                                                        @endif
                                                    </a>
                                                    <div class="flex-grow-1 flex-shrink-1">
                                                        <div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <p class="mb-1">
                                                                    {{ $item->user->name }} <span class="small">-
                                                                        {{ $item->created_at->diffForHumans() }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            <p class="small mb-0">
                                                                {{ $item->isi }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            {{-- <div class="d-flex flex-start mt-4">
                                                <a class="me-3" href="#">
                                                    <img class="rounded-circle shadow-1-strong"
                                                        src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(32).webp"
                                                        alt="avatar" width="65" height="65" />
                                                </a>
                                                <div class="flex-grow-1 flex-shrink-1">
                                                    <div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-1">
                                                                John Smith <span class="small">- 4 hours
                                                                    ago</span>
                                                            </p>
                                                        </div>
                                                        <p class="small mb-0">
                                                            the majority have suffered alteration in some form,
                                                            by
                                                            injected humour, or randomised words.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                @empty
                                    <div class="d-flex flex-start">
                                        <div class="flex-grow-1 flex-shrink-1">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-1">
                                                        <span class="small">Belum ada komentar</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
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
                    <a href="{{ route('guest.berita.detail', $item->slug) }}"
                        class="row mb-3 text-decoration-none hover-a">
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
                                <img src="{{ asset('img/berita/' . $item->gambar) }}"
                                    class="w-100 img-fluid img-thumbnail"
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
                    @php
                        $route_kec = route('guest.berita.kecamatan', $item->slug);
                        if (auth()->check()) {
                            $route_kec = route('user.berita.kecamatan', $item->slug);
                        }
                    @endphp
                    <a href="{{ $route_kec }}" class="mb-3 text-decoration-none">
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
