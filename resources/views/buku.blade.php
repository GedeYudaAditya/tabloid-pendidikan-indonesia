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
    <h1 class="text-center mt-5">Daftar Buku</h1>
    <hr>
    @if ($buku->count() > 0)
        <div class="row">
            @foreach ($buku as $item)
                <div class="col-md-4">
                    <div class="card mb-3" style="position: relative">
                        @if (is_array($item->gambar))
                            <img src="{{ asset('img/buku/' . $item->gambar)[0] }}" class="card-img-top"
                                style="height: 250px; object-fit: cover; object-position: center"
                                alt="{{ asset('img/buku/' . $item->gambar)[0] }}">
                        @else
                            <img src="{{ asset('img/buku/' . $item->gambar) }}" class="card-img-top"
                                style="height: 250px; object-fit: cover; object-position: center"
                                alt="{{ asset('img/buku/' . $item->gambar) }}">
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
                                {{ $judul }}
                            </h6>
                            {{-- like button --}}
                            <div class="d-flex justify-content-between" id="like-{{ $item->id }}-yes">
                                {{-- <div class="d-flex justify-content-between"> --}}
                                @csrf
                                {{-- time created --}}
                                <small class="text-dark">
                                    {{ $item->created_at->diffForHumans() }} | Penulis
                                    {{ $item->penulis }}
                                </small>
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- pagination --}}
            <div class="d-flex justify-content-center">
                {{ $buku->links() }}
            </div>
        </div>
    @else
        <div class="row justify-content-center align-items-center" style="height: 80vh;">
            {{-- gambar --}}
            <div class="text-center">
                <img src="{{ asset('img/no-data.jpeg') }}" alt="..."
                    style="width: 200px; height: 200px; object-fit: cover">
                {{-- text --}}
                <h4 class="text-center">Belum ada buku</h4>
            </div>
        </div>
    @endif
@endsection
