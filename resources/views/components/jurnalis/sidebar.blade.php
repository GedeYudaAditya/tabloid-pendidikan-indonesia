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
                <a class="nav-link {{ Route::is('jurnalis.jurnal*') ? 'active' : '' }}"
                    href="{{ route('jurnalis.jurnal.index') }}">
                    <span data-feather="file"></span>
                    Manajemen Jurnal
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('jurnalis.artikel*') ? 'active' : '' }}"
                    href="{{ route('jurnalis.artikel.index') }}">
                    <span data-feather="file"></span>
                    Manajemen Artikel
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
