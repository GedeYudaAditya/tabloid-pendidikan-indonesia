<style type="text/css">
    .sidebar li .submenu {
        list-style-type: none;
        margin: 0;
        padding: 0;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .sidebar .nav-link {
        font-weight: 500;
        color: var(--bs-dark);
    }

    .sidebar .nav-link:hover {
        color: var(--bs-primary);
    }

    .border-mb {
        border-bottom: 1px solid var(--bs-secondary);
    }
</style>

<!-- Sidebar-->
<div class="border-end app-bg-secondary" id="sidebar-wrapper" style="height: 100vh">
    <div class="sidebar-heading border-bottom app-bg-secondary text-white" style="font-weight: bolder">
        TPI
    </div>
    <div class="list-group sidebar list-group-flush app-bg-secondary">
        <div style="height: 80vh; overflow-y: auto">
            <ul class="nav flex-column p-1 bg-light" id="nav_accordion">
                @forelse ($kabupaten as $item)
                    <li class="nav-item border-mb">
                        <a class="nav-link text-dark" data-bs-toggle="collapse" style="font-weight: bolder"
                            data-bs-target="#menu_item-{{ $item->id }}-nav" href="#">
                            {{ $item->nama_kabupaten }}
                            <i class="fas fa-chevron-down float-end"></i>
                        </a>
                        <ul id="menu_item-{{ $item->id }}-nav" class="submenu collapse"
                            data-bs-parent="#nav_accordion">
                            @forelse ($item->kecamatan as $kecamatan)
                                <li class="nav-item">
                                    <a class="nav-link text-dark"
                                        href="{{ Auth::check() ? route('user.berita.kecamatan', $kecamatan->slug) : route('guest.berita.kecamatan', $kecamatan->slug) }}">
                                        {{-- {{ $kecamatan->nama_kecamatan }} --}}
                                        {{ $kecamatan->nama_kecamatan }}
                                    </a>
                                </li>
                            @empty
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="#">
                                        Belum ada kecamatan
                                    </a>
                                </li>
                            @endforelse
                        </ul>
                    </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>
</div>
