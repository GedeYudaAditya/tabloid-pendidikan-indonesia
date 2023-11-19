@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen akun</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4">
                {{-- porfile image --}}
                @if (Auth::user()->avatar)
                    <img src="{{ asset('img/avatar/' . Auth::user()->avatar) }}" class="img-fluid w-100" alt="logo">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random&color=fff"
                        class="img-fluid w-100" alt="logo">
                @endif
                {{-- form change avatar --}}

                @csrf
                <div class="mb-3">
                    <label for="avatar" class="form-label">Ganti Foto Profil</label>
                    <input class="form-control" name="avatar" type="file" id="avatar" accept=".jpg,.png,.jpeg"
                        onchange="previewImage()">
                </div>
            </div>
            <div class="col-md-8 d-flex justify-content-center align-items-center">
                <div>
                    {{-- error input --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>
                        </div>
                    @endif
                    {{-- form update profil --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" id="nama" aria-describedby="namaHelp"
                            value="{{ Auth::user()->name }}">
                        <div id="namaHelp" class="form-text">
                            Masukkan nama.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email"
                            aria-describedby="emailHelp" value="{{ Auth::user()->email }}">
                        <div id="emailHelp" class="form-text">
                            Masukkan email.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                            aria-describedby="passwordHelp">
                        <div id="passwordHelp" class="form-text">
                            Masukkan password.
                        </div>
                    </div>

                    {{-- confirm password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                            aria-describedby="password_confirmationHelp">
                        <div id="password_confirmationHelp" class="form-text">
                            Masukkan konfirmasi password.
                        </div>
                    </div>

                    {{-- button --}}
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
