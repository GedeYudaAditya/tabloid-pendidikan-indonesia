<!-- Sidebar-->
<div class="border-end app-bg-secondary" id="sidebar-wrapper" style="height: 100vh">
    <div class="sidebar-heading border-bottom app-bg-secondary text-white" style="font-weight: bolder">
        Tabloid Pendi.Indo
    </div>
    <div class="list-group list-group-flush app-bg-secondary">
        @forelse ($kabupaten as $item)
            {{-- <a class="list-group-item list-group-item-action list-group-item-light p-3 app-bg-secondary" href="#!">
                {{ $item->nama_kabupaten }}
            </a> --}}
            {{-- dropdown --}}
            <div class="dropend">
                <button
                    class="list-group-item list-group-item-action list-group-item-light p-2 app-bg-secondary dropdown-toggle"
                    type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $item->nama_kabupaten }}
                </button>
                <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="dropdownMenuButton1"
                    style="border-radius: 0px">
                    @forelse ($item->kecamatan as $kecamatan)
                        <li><a class="dropdown-item"
                                href="{{ route('guest.berita.kecamatan', $kecamatan->slug) }}">{{ $kecamatan->nama_kecamatan }}</a>
                        </li>
                    @empty
                        <li><a class="dropdown-item" href="#">Belum ada kecamatan</a></li>
                    @endforelse
                </ul>
            </div>
        @empty
        @endforelse
    </div>
</div>
