@extends('layouts.other.app')

@section('other-css')
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
    @if ($artikel->count() > 0 || $jurnal->count() > 0)
        {{-- sekapur sirih --}}
        {{-- sekapur sirih --}}
        <h4 class="mt-5">Arikel Terbaru</h4>
        <div class="p-3 row">
            {{-- 2 card in 1 row --}}
            <div class="col-md-6">
                @if ($artikel->count() > 0)
                    {{-- get latest berita --}}
                    @php
                        $latest = $artikel->sortByDesc('created_at')->first();
                        $pertama = $latest;
                    @endphp

                    {{-- <h5>Berita Terkini</h5> --}}
                    <div class="card mb-3" style="position: relative">
                        @if (is_array(json_decode($latest->gambar)))
                            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach (json_decode($latest->gambar) as $item)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                            <img src="{{ asset('img/jurnal-arikel/' . $item) }}" class="d-block w-100"
                                                style="height: 400px; object-fit: cover; object-position: center"
                                                alt="{{ asset('img/jurnal-arikel/' . $item) }}">
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
                            <img src="{{ asset('img/jurnal-arikel/' . json_decode($latest->gambar)) }}" class="card-img-top"
                                style="height: 400px; object-fit: cover; object-position: center"
                                alt="{{ asset('img/jurnal-arikel/' . json_decode($latest->gambar)) }}">
                        @endif
                        <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                            <h5 class="card-title mb-1">
                                @if (Auth::check())
                                    @if (Auth::user()->role == 'admin')
                                        <a href="{{ route('admin.artikel.edit', $latest->slug) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $latest->judul }}
                                        </a>
                                    @else
                                        <a href="{{ route('user.jurnal-artikel.show', ['artikel', $latest->slug]) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $latest->judul }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('guest.jurnal-artikel.show', ['artikel', $latest->slug]) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $latest->judul }}
                                    </a>
                                @endif
                            </h5>
                            {{-- like button --}}
                            <div class="d-flex justify-content-between">
                                <form action="{{ route('like', $latest) }}" method="POST"
                                    id="like-{{ $latest->id }}-yes">
                                    @csrf
                                    {{-- time created --}}
                                    <small class="text-dark">
                                        {{ $latest->created_at->diffForHumans() }}
                                    </small>
                                    <button type="button" class="app-color-primary shere-button" data-bs-toggle="modal"
                                        url="{{ route('guest.berita.detail', $latest->slug) }}"
                                        data-bs-target="#exampleModal">
                                        <i class="fas fa-share-alt shere-icon"></i>
                                        <span class="like-number">Share</span>
                                    </button>
                                    <br>
                                    <small class="text-muted">
                                        Created by {{ $latest->user->name }}
                                    </small>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                @if ($artikel->count() > 1)
                    {{-- get latest berita --}}
                    @php
                        $latest = $artikel
                            ->sortByDesc('created_at')
                            ->skip(1)
                            ->first();
                        $kedua = $latest;
                    @endphp

                    {{-- <h5>Berita Terkini</h5> --}}
                    <div class="card mb-3" style="position: relative">
                        @if (is_array(json_decode($latest->gambar)))
                            <div id="carouselExampleControls1" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach (json_decode($latest->gambar) as $item)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                            <img src="{{ asset('img/jurnal-arikel/' . $item) }}" class="d-block w-100"
                                                style="height: 400px; object-fit: cover; object-position: center"
                                                alt="{{ asset('img/jurnal-arikel/' . $item) }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls1" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden"></span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls1" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden"></span>
                                </button>
                            </div>
                        @else
                            <img src="{{ asset('img/jurnal-arikel/' . json_decode($latest->gambar)) }}"
                                class="card-img-top" style="height: 400px; object-fit: cover; object-position: center"
                                alt="{{ asset('img/jurnal-arikel/' . json_decode($latest->gambar)) }}">
                        @endif
                        <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                            <h5 class="card-title mb-1">
                                @if (Auth::check())
                                    @if (Auth::user()->role == 'admin')
                                        <a href="{{ route('admin.artikel.edit', $latest->slug) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $latest->judul }}
                                        </a>
                                    @else
                                        <a href="{{ route('user.jurnal-artikel.show', ['artikel', $latest->slug]) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $latest->judul }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('guest.jurnal-artikel.show', ['artikel', $latest->slug]) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $latest->judul }}
                                    </a>
                                @endif
                            </h5>
                            {{-- like button --}}
                            <div class="d-flex justify-content-between">
                                <form action="{{ route('like', $latest) }}" method="POST"
                                    id="like-{{ $latest->id }}-yes">
                                    @csrf
                                    {{-- time created --}}
                                    <small class="text-dark">
                                        {{ $latest->created_at->diffForHumans() }}
                                    </small>
                                    <button type="button" class="app-color-primary shere-button" data-bs-toggle="modal"
                                        url="{{ route('guest.berita.detail', $latest->slug) }}"
                                        data-bs-target="#exampleModal2">
                                        <i class="fas fa-share-alt shere-icon"></i>
                                        <span class="like-number">Share</span>
                                    </button>
                                    <br>
                                    <small class="text-muted">
                                        Created by {{ $latest->user->name }}
                                    </small>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ($artikel->count() > 2)
                @php
                    $data = $artikel->sortByDesc('created_at')->skip(2);
                @endphp
                @foreach ($data as $item)
                    <div class="col-md-3">

                        {{-- <h5>Berita Terkini</h5> --}}
                        <div class="card mb-3" style="position: relative">
                            @if (is_array(json_decode($item->gambar)))
                                <img src="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)[0]) }}"
                                    class="card-img-top" style="height: 200px; object-fit: cover; object-position: center"
                                    alt="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)[0]) }}">
                            @else
                                <img src="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)) }}"
                                    class="card-img-top" style="height: 200px; object-fit: cover; object-position: center"
                                    alt="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)) }}">
                            @endif
                            <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                                @php
                                    $judul = strip_tags($item->judul);
                                    $judul = substr($judul, 0, 20);

                                    if (strlen($item->judul) > 20) {
                                        $judul .= '...';
                                    }
                                @endphp
                                <h6 class="card-title mb-1">
                                    @if (Auth::check())
                                        @if (Auth::user()->role == 'admin')
                                            <a href="{{ route('admin.berita.edit', $item->slug) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $judul }}
                                            </a>
                                        @else
                                            <a href="{{ route('user.jurnal-artikel.show', ['artikel', $item->slug]) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $judul }}
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('guest.berita.detail', $item->slug) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $judul }}
                                        </a>
                                    @endif
                                </h6>
                                {{-- like button --}}
                                <form action="{{ route('like', $item) }}" method="POST"
                                    class="d-flex justify-content-between" id="like-{{ $item->id }}-yes">
                                    {{-- <div class="d-flex justify-content-between"> --}}
                                    @csrf
                                    {{-- time created --}}
                                    <small class="text-dark">
                                        {{ $item->created_at->diffForHumans() }}
                                    </small>
                                    <button type="button" class="app-color-primary like-button"
                                        {{ Auth::check() ? '' : 'disabled' }}>
                                        <i class="fas fa-heart like-icon"
                                            onload="checkers_like({{ $item->id }})"></i>
                                        <span class="like-number">{{ $item->like }}</span>
                                    </button>
                                    {{-- </div> --}}
                                </form>

                                {{-- time created --}}
                                {{-- <small class="text-dark">
                            {{ $latest->created_at->diffForHumans() }}
                        </small> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>


        <h1>Semua Artikel</h1>
        @foreach ($artikel as $item)
            <div class="row mb-2">
                @if (is_array(json_decode($item->gambar)))
                    <img src="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)[0]) }}" class="col-4"
                        style="object-fit: cover; object-position: center; height: 150px;"
                        alt="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)[0]) }}">
                @else
                    <img src="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)) }}" class="col-4"
                        style="object-fit: cover; object-position: center; height: 150px;"
                        alt="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)) }}">
                @endif
                <div class="col-8">
                    @php
                        if (strlen($item->judul) > 70) {
                            $judul = substr(strip_tags($item->judul), 0, 70);
                            $judul .= '...';
                        } else {
                            $judul = strip_tags($item->judul);
                        }
                    @endphp
                    <h6>
                        @if (Auth::check())
                            @if (Auth::user()->role == 'admin')
                                <a href="{{ route('admin.jurnal-artikrl.edit', $item->slug) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $judul }}
                                </a>
                            @else
                                <a href="{{ route('user.jurnal-artikrl.show', $item->slug) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $judul }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('guest.jurnal-artikel.show', ['artikel', $item->slug]) }}"
                                class="text-decoration-none text-dark">
                                {{ $judul }}
                            </a>
                        @endif
                    </h6>
                    <hr>
                    {{-- created at --}}
                    <small>
                        <i class="fas fa-clock"></i>
                        {{ $item->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        @endforeach

        {{-- pagination --}}
        <div class="d-flex justify-content-center mb-5">
            {{ $artikel->links() }}
        </div>

        <h1>Semua Jurnal</h1>
        @foreach ($jurnal as $item)
            <div class="row mb-2">
                <div class="col-2 bg-secondary">

                </div>
                {{-- @if (is_array(json_decode($item->gambar)))
                    <img src="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)[0]) }}" class="col-4"
                        style="object-fit: cover; object-position: center; height: 150px;"
                        alt="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)[0]) }}">
                @else
                    <img src="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)) }}" class="col-4"
                        style="object-fit: cover; object-position: center; height: 150px;"
                        alt="{{ asset('img/jurnal-arikel/' . json_decode($item->gambar)) }}">
                @endif --}}
                <div class="col-10">
                    @php
                        if (strlen($item->judul) > 70) {
                            $judul = substr(strip_tags($item->judul), 0, 70);
                            $judul .= '...';
                        } else {
                            $judul = strip_tags($item->judul);
                        }
                    @endphp
                    <h6>
                        @if (Auth::check())
                            @if (Auth::user()->role == 'admin')
                                <a href="{{ asset('attachment/' . $item->attachment) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $judul }}
                                </a>
                            @else
                                <a href="{{ asset('attachment/' . $item->attachment) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $judul }}
                                </a>
                            @endif
                        @else
                            <a href="{{ asset('attachment/' . $item->attachment) }}"
                                class="text-decoration-none text-dark">
                                {{ $judul }}
                            </a>
                        @endif
                    </h6>
                    <hr>
                    {{-- created at --}}
                    <small>
                        <i class="fas fa-clock"></i>
                        {{ $item->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        @endforeach

        {{-- pagination --}}
        <div class="d-flex justify-content-center mb-5">
            {{ $artikel->links() }}
        </div>

        <div class="social-btn-sp text-center">
            {!! $shareButtons !!}
        </div>
    @else
        <div class="row justify-content-center align-items-center" style="height: 80vh;">
            {{-- gambar --}}
            <div class="text-center">
                <img src="{{ asset('img/no-data.jpeg') }}" alt="..."
                    style="width: 200px; height: 200px; object-fit: cover">
                {{-- text --}}
                <h4 class="text-center">Belum ada artikel dan jurnal</h4>
            </div>
        </div>
    @endif
@endsection

@section('sponsor')
    {{-- sponsors --}}
    {{-- small make slideing list sponsor --}}
    @if ($sponsors->count() > 0)
        <div class="container-fluid app-bg-base">
            <div class="row py-5">
                <div class="col-md-12">
                    <h2 class="text-center mb-3">Sponsor</h2>
                    <div class="p-3">
                        <marquee behavior="scroll" direction="left">
                            <div class="justify-content-center" style="flex-wrap: nowrap; display: flex;">
                                @forelse ($sponsors as $item)
                                    <div class="col-md-2 text-center mb-3">
                                        <img src="{{ asset('img/sponsor/' . $item->gambar) }}"
                                            style="width: 100px; height: 100px; object-fit: contain; object-position: center"
                                            alt="...">
                                        {{-- nama --}}
                                        <h6 class="text-center mt-2">{{ $item->nama }}</h6>
                                    </div>
                                @empty
                                    <small>
                                        <i class="fas fa-exclamation-circle"></i>
                                        Belum ada sponsor
                                    </small>
                                @endforelse
                            </div>
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- <div class="row justify-content-center align-items-center" style="height: 100%;">
            <div class="text-center">
                <img src="{{ asset('img/no-data.jpeg') }}" alt="..."
                    style="width: 200px; height: 200px; object-fit: cover">
                <h4 class="text-center">Belum ada sponsor</h4>
            </div>
        </div> --}}
    @endif
@endsection

@section('other-js')
    <script>
        // async function for get like
        async function getlike(id) {
            var like = false;
            await $.ajax({
                url: '/get-like/' + id,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    like = data.data.like;
                }
            });

            return like;
        }

        // async function for like
        async function likes(id) {
            await $.ajax({
                url: '/like/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(data) {
                    console.log(data);
                    like = data.status;
                },
                error: function(data) {
                    // redirect to login page
                    // alert
                    alert('Login terlebih dahulu');
                    window.location.href = '/login';
                }
            });
        }

        async function checkers_like(id) {
            if ($('.like-button').attr('disabled')) {
                console.log('disabled');
                $('.like-button').click(function() {
                    confirm('Login terlebih dahulu');
                    window.location.href = '/login';
                });
            } else {
                var checkers = await getlike(id);

                if (checkers) {
                    // change color to like
                    $('#like-' + id + '-yes').children('.like-button').removeClass(
                        'app-color-primary');
                    // change color to like
                    $('#like-' + id + '-yes').children('.like-button').addClass(
                        'text-pink');
                    // change color to like
                    $('#like-' + id + '-yes').children('.like-button').children(
                        '.like-number').text(like);
                } else {
                    // change color to unlike
                    $('#like-' + id + '-yes').children('.like-button').addClass(
                        'app-color-primary');
                    // change color to unlike
                    $('#like-' + id + '-yes').children('.like-button').removeClass(
                        'text-pink');
                    // change color to like
                    $('#like-' + id + '-yes').children('.like-button').children(
                        '.like-number').text(like);
                }
            }
        }


        $(document).ready(async function() {
            // if like-button is disabled
            if ($('.like-button').attr('disabled')) {
                console.log('disabled');
                $('.like-button').click(function() {
                    confirm('Login terlebih dahulu');
                    window.location.href = '/login';
                });
            } else {
                $('.like-button').click(async function() {
                    // $(this).toggleClass('app-color-primary');
                    // $(this).toggleClass('text-white');
                    // $(this).children('.like-icon').toggleClass('text-white');

                    // get id
                    var id = $(this).parent().attr('id');
                    id = id.split('-')[1];

                    // get like
                    var like = $('#like-' + id + '-yes').children('.like-button').children(
                        '.like-number').text();
                    like = parseInt(like);

                    var checkers = await getlike(id);

                    // update like
                    $('#like-' + id + '-yes').children('.like-button').children(
                        '.like-number').text(like);

                    console.log(like);

                    // send request
                    await likes(id);

                    // get like
                    var checkers = await getlike(id);

                    if (checkers) {
                        // ganti nomor ribuan, jutaan


                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').removeClass(
                            'app-color-primary');
                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').addClass(
                            'text-pink');
                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').children(
                            '.like-number').text(like);
                    } else {
                        // change color to unlike
                        $('#like-' + id + '-yes').children('.like-button').addClass(
                            'app-color-primary');
                        // change color to unlike
                        $('#like-' + id + '-yes').children('.like-button').removeClass(
                            'text-pink');
                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').children(
                            '.like-number').text(like);
                    }

                    // check if like or not
                    if (checkers) {
                        // like
                        like += 1;
                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').removeClass(
                            'app-color-primary');
                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').addClass(
                            'text-pink');
                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').children(
                            '.like-number').text(like);
                    } else {
                        // unlike
                        like -= 1;
                        // change color to unlike
                        $('#like-' + id + '-yes').children('.like-button').addClass(
                            'app-color-primary');
                        // change color to unlike
                        $('#like-' + id + '-yes').children('.like-button').removeClass(
                            'text-pink');
                        // change color to like
                        $('#like-' + id + '-yes').children('.like-button').children(
                            '.like-number').text(like);
                    }

                    // update like
                    $('#like-' + id + '-yes').children('.like-button').children(
                        '.like-number').text(like);
                });
            }
        });
    </script>

    @if (isset($pertama))
        {{-- modal share --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">
                            Share to social media
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        {!! Share::page(url(route('guest.berita.detail', $pertama->slug)))->facebook()->twitter()->whatsapp()->telegram() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (isset($kedua))
        {{-- modal share --}}
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">
                            Share to social media
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        {!! Share::page(url(route('guest.berita.detail', $kedua->slug)))->facebook()->twitter()->whatsapp()->telegram() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (isset($mostPopular))
        {{-- modal share --}}
        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">
                            Share to social media
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        {!! Share::page(url(route('guest.berita.detail', $mostPopular->slug)))->facebook()->twitter()->whatsapp()->telegram() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
