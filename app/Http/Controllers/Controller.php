<?php

namespace App\Http\Controllers;

use App\Models\Artikle;
use App\Models\Berita;
use App\Models\Buku;
use App\Models\Event;
use App\Models\HariPeringatan;
use App\Models\Jurnal;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Program;
use App\Models\SekapurSirih;
use App\Models\SistemInformasi;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Jorenvh\Share\Share;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $kecamatanPopulerId = Berita::select('kecamatan_id', DB::raw('count(*) as total'))
            ->groupBy('kecamatan_id')
            ->orderBy('total', 'desc')
            ->limit(1)
            ->get()
            ->first();

        $shareButtons = new Share();

        $shareButtons = $shareButtons->page(
            url()->current()
        )->facebook()
            ->twitter()
            // ->linkedin()
            ->whatsapp()
            ->telegram();
        // ->reddit()
        // ->pinterest();

        $berita = Berita::where('status', 'publish')->orderBy('created_at', 'asc')->paginate(6);

        $tahun = [];
        foreach ($berita as $key => $value) {
            $tahun[] = date('Y', strtotime($value->created_at));
        }

        $tahun = array_unique($tahun);

        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $volume = [
            'V1' => 'Volume 1',
            'V2' => 'Volume 2',
        ];

        $data = [
            'title' => 'Landing Page',
            'kabupaten' => Kabupaten::all(),
            'berita' => $berita,
            'hari_peringatan' => HariPeringatan::get()->first(),
            'sekaps' => SekapurSirih::get()->first(),
            'kecamatanPopulerId' => $kecamatanPopulerId ? $kecamatanPopulerId->kecamatan->id : null,
            'kecamatanPopularName' => $kecamatanPopulerId ? $kecamatanPopulerId->kecamatan->nama_kecamatan : null,
            'sponsors' => Sponsor::all(),
            'shareButtons' => $shareButtons,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'volume' => $volume,
        ];


        return view('index', $data);
    }

    public function beritaDetail($slug)
    {
        $data = [
            'title' => 'Detail Berita',
            'kabupaten' => Kabupaten::all(),
            'berita' => Berita::where('slug', $slug)->firstOrFail(),
            'comments' => Berita::where('slug', $slug)->firstOrFail()
                ->komentar()
                ->orderBy('created_at', 'desc')
                ->where('parent_id', null)
                ->get(),
        ];
        return view('detail-berita', $data);
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string',
        ]);

        try {
            $berita = Berita::findOrFail($id);
            DB::beginTransaction();
            $berita->komentar()->create([
                'user_id' => auth()->user()->id,
                'isi' => $request->komentar,
                'berita_id' => $berita->id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil komentar berita.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal komentar berita. ' . $th->getMessage());
        }
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string',
            'parent_id' => 'required|integer',
        ]);

        try {
            $berita = Berita::findOrFail($id);
            DB::beginTransaction();
            $berita->komentar()->create([
                'user_id' => auth()->user()->id,
                'isi' => $request->komentar,
                'berita_id' => $berita->id,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil reply komentar berita.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal reply komentar berita. ' . $th->getMessage());
        }
    }

    public function beritaTag($slug)
    {
        $data = [
            'title' => 'Berita Tag',
            'kabupaten' => Kabupaten::all(),
            'berita' => Berita::where('tag', 'like', '%' . $slug . '%')->paginate(6),
        ];
        return view('berita-tag', $data);
    }

    public function beritaKabupaten($slug)
    {
        $data = [
            'title' => 'Berita Kabupaten',
            'kabupaten' => Kabupaten::all(),
            'berita' => Berita::where('kabupaten_id', Kabupaten::where('slug', $slug)->firstOrFail()->id)->paginate(6),
        ];
        return view('berita-kabupaten', $data);
    }

    public function beritaKecamatan($slug)
    {
        $berita = Berita::where('kecamatan_id', Kecamatan::where('slug', $slug)->firstOrFail()->id)->paginate(6);

        // ambil rentang tahun berita yang ada
        $tahun = [];
        foreach ($berita as $key => $value) {
            $tahun[] = date('Y', strtotime($value->created_at));
        }

        $tahun = array_unique($tahun);

        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $volume = [
            'V1' => 'Volume 1',
            'V2' => 'Volume 2',
        ];

        $data = [
            'title' => 'Berita Kecamatan',
            'kabupaten' => Kabupaten::all(),
            'berita' => $berita,
            'kecamatan_now' => Kecamatan::where('slug', $slug)->firstOrFail(),
            'sponsors' => Sponsor::all(),
            'tahun' => $tahun,
            'bulan' => $bulan,
            'volume' => $volume,
        ];
        return view('berita-kecamatan', $data);
    }

    public function auth()
    {
        $data = [
            'title' => 'Login',
            'kabupaten' => Kabupaten::all(),
        ];
        return view('auth.login', $data);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            // return redirect()->route('admin.home');
            if (auth()->user()->level == 'admin') {
                return redirect()->route('admin.home');
            } else if (auth()->user()->level == 'reporter') {
                return redirect()->route('reporter.home');
            } else if (auth()->user()->level == 'redaksi') {
                return redirect()->route('redaksi.home');
            } else if (auth()->user()->level == 'jurnalis') {
                return redirect()->route('jurnalis.home');
            } else if (auth()->user()->level == 'user') {
                return redirect()->route('user.home');
            }
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function register()
    {
        $data = [
            'title' => 'Register',
            'kabupaten' => Kabupaten::all(),
        ];
        return view('auth.register', $data);
    }

    public function registerProses(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        auth()->loginUsingId($user->id);

        return redirect()->route('user.home');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('landing');
    }

    public function about()
    {
        $data = [
            'title' => 'About',
            'kabupaten' => Kabupaten::all(),
            'sistem_informasi' => SistemInformasi::all(),
            'program' => Program::all(),
        ];
        return view('about', $data);
    }

    public function like($id)
    {
        try {
            $berita = Berita::findOrFail($id);
            DB::beginTransaction();
            // check if user already like this berita
            $check = $berita->likes()->where('user_id', auth()->user()->id)->first();

            if ($check) {
                // unlike
                $berita->likes()->where('user_id', auth()->user()->id)->delete();
                $berita->like -= 1;
                $berita->save();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil unlike berita.',
                    'like' => false,
                ], 200);
            } else {
                // like
                $berita->likes()->create([
                    'user_id' => auth()->user()->id,
                ]);
                $berita->like += 1;
                $berita->save();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil like berita.',
                    'like' => true,
                ], 200);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal like berita. ' . $th->getMessage(),
            ], 500);
        }
    }

    public function getLike($id)
    {
        try {
            $berita = Berita::findOrFail($id);
            $check = $berita->likes()->where('user_id', auth()->user()->id)->first();

            if ($check) {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil get like berita.',
                    'data' => [
                        'like' => true,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil get like berita.',
                    'data' => [
                        'like' => false,
                    ],
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal get like berita. ' . $th->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $data = [
            'title' => 'Search',
            'kabupaten' => Kabupaten::all(),
            'berita' => Berita::where('judul', 'like', '%' . $request->search . '%')->where('status', 'publish')->orderBy('created_at', 'desc')->paginate(6),
        ];
        return view('search', $data);
    }

    public function jurnalArtikel()
    {
        $shareButtons = new Share();

        $shareButtons = $shareButtons->page(
            url()->current()
        )->facebook()
            ->twitter()
            // ->linkedin()
            ->whatsapp()
            ->telegram();
        // ->reddit()
        // ->pinterest();

        $data = [
            'title' => 'Jurnal Artikel',
            'kabupaten' => Kabupaten::all(),
            'jurnal' => Jurnal::where('status', 'publish')->orderBy('created_at', 'desc')->paginate(6),
            'artikel' => Artikle::where('status', 'publish')->orderBy('created_at', 'desc')->paginate(6),
            'sponsors' => Sponsor::all(),
            'shareButtons' => $shareButtons
        ];
        return view('jurnal-artikel', $data);
    }

    public function jurnalArtikelShow($type, $slug)
    {
        $shareButtons = new Share();

        $shareButtons = $shareButtons->page(
            url()->current()
        )->facebook()
            ->twitter()
            // ->linkedin()
            ->whatsapp()
            ->telegram();
        // ->reddit()
        // ->pinterest();

        if ($type == 'jurnal') {
            $data = [
                'title' => 'Jurnal Artikel',
                'kabupaten' => Kabupaten::all(),
                'jurnalArtikel' => Jurnal::where('slug', $slug)->firstOrFail(),
                'berita' => Berita::first(),
                'sponsors' => Sponsor::all(),
                'shareButtons' => $shareButtons
            ];
            return view('jurnal-artikel-show', $data);
        } else if ($type == 'artikel') {
            $data = [
                'title' => 'Jurnal Artikel',
                'kabupaten' => Kabupaten::all(),
                'jurnalArtikel' => Artikle::where('slug', $slug)->firstOrFail(),
                'berita' => Berita::first(),
                'sponsors' => Sponsor::all(),
                'shareButtons' => $shareButtons
            ];
            return view('jurnal-artikel-show', $data);
        } else {
            return redirect()->back()->with('error', 'Tipe tidak ditemukan.');
        }
    }

    // buku
    public function buku()
    {
        $shareButtons = new Share();

        $shareButtons = $shareButtons->page(
            url()->current()
        )->facebook()
            ->twitter()
            // ->linkedin()
            ->whatsapp()
            ->telegram();
        // ->reddit()
        // ->pinterest();

        $data = [
            'title' => 'Buku',
            'kabupaten' => Kabupaten::all(),
            'buku' => Buku::orderBy('created_at', 'desc')->paginate(6),
            'sponsors' => Sponsor::all(),
            'shareButtons' => $shareButtons
        ];
        return view('buku', $data);
    }

    public function bukuShow($slug)
    {
        $shareButtons = new Share();

        $shareButtons = $shareButtons->page(
            url()->current()
        )->facebook()
            ->twitter()
            // ->linkedin()
            ->whatsapp()
            ->telegram();
        // ->reddit()
        // ->pinterest();

        $data = [
            'title' => 'Buku',
            'kabupaten' => Kabupaten::all(),
            'berita' => Berita::first(),
            'buku' => Buku::where('slug', $slug)->firstOrFail(),
            'sponsors' => Sponsor::all(),
            'shareButtons' => $shareButtons
        ];
        return view('buku-detail', $data);
    }

    public function event()
    {
        $shareButtons = new Share();

        $shareButtons = $shareButtons->page(
            url()->current()
        )->facebook()
            ->twitter()
            // ->linkedin()
            ->whatsapp()
            ->telegram();
        // ->reddit()
        // ->pinterest();

        $data = [
            'title' => 'Event',
            'kabupaten' => Kabupaten::all(),
            'event' => Event::orderBy('created_at', 'desc')->paginate(6),
            'sponsors' => Sponsor::all(),
            'shareButtons' => $shareButtons
        ];
        return view('event', $data);
    }

    public function eventShow($slug)
    {
        $shareButtons = new Share();

        $shareButtons = $shareButtons->page(
            url()->current()
        )->facebook()
            ->twitter()
            // ->linkedin()
            ->whatsapp()
            ->telegram();
        // ->reddit()
        // ->pinterest();

        $data = [
            'title' => 'Event',
            'kabupaten' => Kabupaten::all(),
            'event' => Event::where('slug', $slug)->firstOrFail(),
            'sponsors' => Sponsor::all(),
            'shareButtons' => $shareButtons,
            'berita' => Berita::first(),
        ];
        return view('event-detail', $data);
    }

    // porfile
    public function profile()
    {
        $data = [
            'title' => 'Profile',
            'kabupaten' => Kabupaten::all(),
        ];
        return view('profile', $data);
    }

    public function profileOperator()
    {
        $data = [
            'title' => 'Profile',
        ];
        return view('components.other.akun', $data);
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
            'password' => 'nullable|string|confirmed',
        ]);

        try {
            $user = User::findOrFail(auth()->user()->id);
            DB::beginTransaction();

            if ($request->avatar) {
                // delete old avatar
                if ($user->avatar) {
                    if (file_exists(public_path('img/avatar/' . $user->avatar))) {
                        unlink(public_path('img/avatar/' . $user->avatar));
                    }
                }

                $avatarName = time() . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('img/avatar'), $avatarName);
                $user->avatar = $avatarName;
                $user->save();
            }

            // check if password not null
            if ($request->password) {
                $password = bcrypt($request->password);
            } else {
                $password = $user->password;
            }

            $user->update(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'avatar' => $user->avatar ? $user->avatar : null,
                    'password' => $password,
                ]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil update profile.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->back()->with('error', 'Gagal update profile. ' . $th->getMessage());
        }
    }
}
