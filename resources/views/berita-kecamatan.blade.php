@extends('layouts.other.app')

@section('hero')
    <div class="row mb-3">
        {{-- gambar kec with overlay text --}}
        <div class="col-12" style="position: relative">
            <img src="{{ asset('img/kecamatan/' . $kecamatan_now->gambar) }}" class="img-fluid"
                style="height: 400px; width: 100%; object-fit: cover; object-position: center"
                alt="{{ $kecamatan_now->gambar }}">
            <div class="app-overlay-dark-bg row justify-content-center align-items-center"
                style="position: absolute; bottom: 0; width: 100%; height: 100%;">
                {{-- nama_kecamatan_now --}}
                <h1 class="text-white text-center">{{ $kecamatan_now->nama_kecamatan }}</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')

    {{-- sekapur sirih --}}
    <h4>Berita Terbaru</h4>
    <div class="p-3 row">
        {{-- 2 card in 1 row --}}
        <div class="col-md-6">
            @if ($berita->count() > 0)
                {{-- get latest berita --}}
                @php
                    $latest = $berita->sortByDesc('created_at')->first();
                @endphp

                {{-- <h5>Berita Terkini</h5> --}}
                <div class="card mb-3" style="position: relative">
                    @if (is_array(json_decode($latest->gambar)))
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach (json_decode($latest->gambar) as $item)
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
                        <img src="{{ asset('img/berita/' . json_decode($latest->gambar)) }}" class="card-img-top"
                            style="height: 400px; object-fit: cover; object-position: center"
                            alt="{{ asset('img/berita/' . json_decode($latest->gambar)) }}">
                    @endif
                    <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                        <h5 class="card-title mb-1">
                            @if (Auth::check())
                                @if (Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.berita.edit', $latest->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $latest->judul }}
                                    </a>
                                @else
                                    <a href="{{ route('user.berita.detail', $latest->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $latest->judul }}
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('guest.berita.detail', $latest->slug) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $latest->judul }}
                                </a>
                            @endif
                        </h5>
                        {{-- like button --}}
                        <div class="d-flex justify-content-between">
                            <form action="{{ route('like', $latest) }}" method="POST" id="like-{{ $latest->id }}-yes">
                                @csrf
                                {{-- time created --}}
                                <small class="text-dark">
                                    {{ $latest->created_at->diffForHumans() }}
                                </small>
                                <button type="button" class="app-color-primary like-button"
                                    {{ Auth::check() ? '' : 'disabled' }}>
                                    <i class="fas fa-heart like-icon" onload="checkers_like({{ $latest->id }})"></i>
                                    <span class="like-number">{{ $latest->like }}</span>
                                </button>
                                <br>
                                <small class="text-muted">
                                    Created by {{ $latest->user->name }} |
                                    Reported by
                                    {{ $latest->liputan->reporter->name }}
                                </small>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            @if ($berita->count() > 1)
                {{-- get latest berita --}}
                @php
                    $latest = $berita
                        ->sortByDesc('created_at')
                        ->skip(1)
                        ->first();
                @endphp

                {{-- <h5>Berita Terkini</h5> --}}
                <div class="card mb-3" style="position: relative">
                    @if (is_array(json_decode($latest->gambar)))
                        <div id="carouselExampleControls1" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach (json_decode($latest->gambar) as $item)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ asset('img/berita/' . $item) }}" class="d-block w-100"
                                            style="height: 400px; object-fit: cover; object-position: center"
                                            alt="{{ asset('img/berita/' . $item) }}">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls1"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls1"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"></span>
                            </button>
                        </div>
                    @else
                        <img src="{{ asset('img/berita/' . json_decode($latest->gambar)) }}" class="card-img-top"
                            style="height: 400px; object-fit: cover; object-position: center"
                            alt="{{ asset('img/berita/' . json_decode($latest->gambar)) }}">
                    @endif
                    <div class="card-body app-overlay-bg" style="position: absolute; bottom: 0; width: 100%;">
                        <h5 class="card-title mb-1">
                            @if (Auth::check())
                                @if (Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.berita.edit', $latest->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $latest->judul }}
                                    </a>
                                @else
                                    <a href="{{ route('user.berita.detail', $latest->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $latest->judul }}
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('guest.berita.detail', $latest->slug) }}"
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
                                <button type="button" class="app-color-primary like-button"
                                    {{ Auth::check() ? '' : 'disabled' }}>
                                    <i class="fas fa-heart like-icon" onload="checkers_like({{ $latest->id }})"></i>
                                    <span class="like-number">{{ $latest->like }}</span>
                                </button>
                                <br>
                                <small class="text-muted">
                                    Created by {{ $latest->user->name }} |
                                    Reported by
                                    {{ $latest->liputan->reporter->name }}
                                </small>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if ($berita->count() > 2)
            @php
                $data = $berita->sortByDesc('created_at')->skip(2);
            @endphp
            @foreach ($data as $item)
                <div class="col-md-3">

                    {{-- <h5>Berita Terkini</h5> --}}
                    <div class="card mb-3" style="position: relative">
                        @if (is_array(json_decode($item->gambar)))
                            <img src="{{ asset('img/berita/' . json_decode($item->gambar)[0]) }}" class="card-img-top"
                                style="height: 200px; object-fit: cover; object-position: center"
                                alt="{{ asset('img/berita/' . json_decode($item->gambar)[0]) }}">
                        @else
                            <img src="{{ asset('img/berita/' . json_decode($item->gambar)) }}" class="card-img-top"
                                style="height: 200px; object-fit: cover; object-position: center"
                                alt="{{ asset('img/berita/' . json_decode($item->gambar)) }}">
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
                                        <a href="{{ route('user.berita.detail', $item->slug) }}"
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
                                    <i class="fas fa-heart like-icon" onload="checkers_like({{ $item->id }})"></i>
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

    <h4>Berita Terpopuler</h4>

    <div class="row mb-5">
        @if ($berita->count() > 0)
            <div class="col-md-7 mb-5">
                @php
                    // get data that have most like in this week
                    $like = App\Models\Berita::whereBetween('created_at', [Carbon\Carbon::now()->startOfWeek(), Carbon\Carbon::now()->endOfWeek()])
                        ->where('kecamatan_id', $kecamatan_now->id)
                        ->get();

                    $like = $like->max('like');

                    // get the data with value of most like
                    $mostPopular = App\Models\Berita::where('like', $like)->first();
                @endphp
                @if (is_array(json_decode($mostPopular->gambar)))
                    <div style="margin-bottom: 15px" id="carouselExampleControls2" class="carousel slide"
                        data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach (json_decode($mostPopular->gambar) as $item)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ asset('img/berita/' . $item) }}" class="d-block w-100"
                                        style="height: 300px; object-fit: cover; object-position: center"
                                        alt="{{ asset('img/berita/' . $item) }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls2"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls2"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                    </div>
                @else
                    <img src="{{ asset('img/berita/' . json_decode($mostPopular->gambar)) }}" class="card-img-top mb-3"
                        style="height: 300px; object-fit: cover; object-position: center"
                        alt="{{ asset('img/berita/' . json_decode($mostPopular->gambar)) }}">
                @endif

                <h5>
                    {{ $mostPopular->judul }}
                </h5>
                {{-- Created at --}}
                <h6 class="text-muted mt-3">
                    {{-- icon --}}
                    <i class="fas fa-clock"></i>
                    {{ $mostPopular->created_at->diffForHumans() }}
                    <i class="me-3"></i>
                    {{-- icon comment --}}
                    <i class="fas fa-comment"></i>
                    {{ count($mostPopular->komentar) }}
                    <i class="me-3"></i>
                    {{-- love icon --}}
                    <i class="fas fa-heart"></i>
                    {{ $mostPopular->like }}
                </h6>

                <small class="text-muted">
                    Created by {{ $mostPopular->user->name }} |
                    Reported by
                    {{ $mostPopular->liputan->reporter->name }}
                </small>
                <p>
                    @php
                        $isi = strip_tags($mostPopular->isi);
                        $isi = substr($isi, 0, 500);
                    @endphp
                    {{ $isi }}...
                </p>

                {{-- button read more --}}
                @if (Auth::check())
                    @if (Auth::user()->role == 'admin')
                        <a href="{{ route('admin.berita.edit', $mostPopular->slug) }}"
                            class="btn btn-outline-primary app-color-primary">Read More</a>
                    @else
                        <a href="{{ route('user.berita.detail', $mostPopular->slug) }}"
                            class="btn btn-outline-primary app-color-primary">Read More</a>
                    @endif
                @else
                    <a href="{{ route('guest.berita.detail', $mostPopular->slug) }}"
                        class="btn btn-outline-primary app-color-primary">Read More</a>
                @endif
            </div>
        @endif

        {{-- list berita lainnya --}}
        <div class="col-md-5 mb-5">
            @php
                // ambil data tanpa data yang paling populer
                if (isset($mostPopular)) {
                    $data = $berita->where('id', '!=', $mostPopular->id);
                } else {
                    $data = $berita;
                }
            @endphp
            @forelse ($data as $item)
                <div class="row mb-2">
                    @if (is_array(json_decode($item->gambar)))
                        <img src="{{ asset('img/berita/' . json_decode($item->gambar)[0]) }}" class="col-4"
                            style="object-fit: cover; object-position: center; height: 100px;"
                            alt="{{ asset('img/berita/' . json_decode($item->gambar)[0]) }}">
                    @else
                        <img src="{{ asset('img/berita/' . json_decode($item->gambar)) }}" class="col-4"
                            style="object-fit: cover; object-position: center; height: 100px;"
                            alt="{{ asset('img/berita/' . json_decode($item->gambar)) }}">
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
                                    <a href="{{ route('admin.berita.edit', $item->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $judul }}
                                    </a>
                                @else
                                    <a href="{{ route('user.berita.detail', $item->slug) }}"
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
                        <hr>
                        {{-- created at --}}
                        <small>
                            <i class="fas fa-clock"></i>
                            {{ $item->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @empty
                <small>
                    <i class="fas fa-exclamation-circle"></i>
                    Belum ada berita
                </small>
            @endforelse
        </div>
    </div>

@endsection

@section('sponsor')
    {{-- sponsors --}}
    {{-- small make slideing list sponsor --}}
    <div class="container-fluid app-bg-base">
        <div class="row py-5">
            <div class="col-md-12">
                <h2 class="text-center mb-3">Sponsor</h2>
                <div class="p-3">
                    <marquee behavior="scroll" direction="left">
                        <div class="justify-content-center" style="flex-wrap: nowrap; display: flex; width: max-content;">
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
@endsection
