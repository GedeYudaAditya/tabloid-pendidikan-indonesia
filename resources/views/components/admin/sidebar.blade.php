<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.home') ? 'active' : '' }}" aria-current="page"
                    href="{{ route('admin.home') }}">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.kabupaten*') ? 'active' : '' }}"
                    href="{{ route('admin.kabupaten.index') }}">
                    <span data-feather="file"></span>
                    Manajemen Kabupaten
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.kecamatan*') ? 'active' : '' }}"
                    href="{{ route('admin.kecamatan.index') }}">
                    <span data-feather="file"></span>
                    Manajemen Kecamatan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.berita*') ? 'active' : '' }}"
                    href="{{ route('admin.berita.index') }}">
                    <span data-feather="file-text"></span>
                    Manajemen Berita
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.sistem-informasi*') ? 'active' : '' }}"
                    href="{{ route('admin.sistem-informasi.index') }}">
                    <span data-feather="file-text"></span>
                    Manajemen Informasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.program*') ? 'active' : '' }}"
                    href="{{ route('admin.program.index') }}">
                    <span data-feather="file-text"></span>
                    Manajemen Program
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.management-sponsor*') ? 'active' : '' }}"
                    href="{{ route('admin.management-sponsor.index') }}">
                    <span data-feather="file-text"></span>
                    Manajemen Sponsor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.buku*') ? 'active' : '' }}"
                    href="{{ route('admin.buku.index') }}">
                    <span data-feather="file-text"></span>
                    Manajemen Buku
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.event*') ? 'active' : '' }}"
                    href="{{ route('admin.event.index') }}">
                    <span data-feather="file-text"></span>
                    Manajemen Event
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.jurnal-artikel*') ? 'active' : '' }}"
                    href="{{ route('admin.jurnal-artikel.index') }}">
                    <span data-feather="file-text"></span>
                    Manajemen Artikel dan Jurnal
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Pengaturan</span>
            {{-- <a class="link-secondary" href="#" aria-label="Add a new report">
                <span data-feather="plus-circle"></span>
            </a> --}}
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="users"></span>
                    Manajemen Akun
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}">
                    <span data-feather="log-out"></span>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
