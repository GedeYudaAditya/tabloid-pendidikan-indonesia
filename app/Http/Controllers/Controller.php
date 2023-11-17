<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\HariPeringatan;
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

        $data = [
            'title' => 'Landing Page',
            'kabupaten' => Kabupaten::all(),
            'berita' => Berita::where('status', 'publish')->orderBy('created_at', 'desc')->paginate(6),
            'hari_peringatan' => HariPeringatan::get()->first(),
            'sekaps' => SekapurSirih::get()->first(),
            'kecamatanPopulerId' => $kecamatanPopulerId ? $kecamatanPopulerId->kecamatan->id : null,
            'kecamatanPopularName' => $kecamatanPopulerId ? $kecamatanPopulerId->kecamatan->nama_kecamatan : null,
            'sponsors' => Sponsor::all(),
            'shareButtons' => $shareButtons,
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
        $data = [
            'title' => 'Berita Kecamatan',
            'kabupaten' => Kabupaten::all(),
            'berita' => Berita::where('kecamatan_id', Kecamatan::where('slug', $slug)->firstOrFail()->id)->get(),
            'kecamatan_now' => Kecamatan::where('slug', $slug)->firstOrFail(),
            'sponsors' => Sponsor::all(),
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
}
