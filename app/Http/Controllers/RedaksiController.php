<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\HariPeringatan;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Liputan;
use App\Models\SekapurSirih;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RedaksiController extends Controller
{
    //

    public function home()
    {
        $data = [
            'title' => 'Redaksi || Dashboard',
            'berita' => Berita::where('status', 'publish')->get(),
            'kecamatan' => Kecamatan::all(),
            'kabupaten' => Kabupaten::all(),
            'user' => User::where('level', '!=', 'user')->where('level', '!=', 'admin')->get(),
        ];
        return view('admin.index', $data);
    }

    public function unpublishedBerita()
    {
        $data = [
            'title' => 'Redaksi || Manajemen Liputan dan Berita',
            'liputans' => Liputan::where('status', 'mengantri')->get(),
            // berita where status != publish
            'beritas' => Berita::get()->where('status', '!=', 'publish'),
        ];
        return view('redaksi.unpublish.index', $data);
    }

    public function Create()
    {
        $data = [
            'title' => 'Redaksi || Manajemen Liputan dan Berita || Create',
            'liputans' => Liputan::where('status', 'mengantri')->get(),
            // berita where status != publish
            // 'beritas' => Berita::get()->where('status', '!=', 'publish'),
        ];
        return view('redaksi.unpublish.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'liputan_id' => 'required',
            'volume' => 'required',
            // 'slug' => 'required',
        ]);

        // get liputan id
        $liputan = Liputan::findOrFail($request->liputan_id);

        try {
            DB::beginTransaction();
            $slug = Str::slug($request->judul . '-' . time());

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                foreach ($foto as $f) {
                    $filename = time() . '-' . $f->getClientOriginalName();
                    $f->move(public_path('img/berita'), $filename);

                    $data[] = $filename;
                }
            } else {
                if ($liputan->gambar) {
                    $data = json_decode($liputan->gambar);

                    // copy file from liputan to berita
                    if (is_array($data)) {
                        foreach ($data as $d) {
                            copy(public_path('img/liputan/' . $d), public_path('img/berita/' . $d));
                        }
                    } else {
                        copy(public_path('img/liputan/' . $data), public_path('img/berita/' . $data));
                    }
                } else {
                    $data = null;
                }
            }

            $encoded = json_encode($data);

            $data = [
                'judul' => $request->judul,
                'isi' => $request->isi,
                'gambar' => $encoded,
                'kecamatan_id' => $liputan->kecamatan_id,
                'liputan_id' => $liputan->id,
                'slug' => $slug,
                'user_id' => auth()->user()->id,
                'status' => 'draft',
                'volume' => $request->volume,
            ];

            Berita::create($data);


            // update status liputan
            $liputan->update([
                'status' => 'dibuat'
            ]);

            DB::commit();
            return redirect()->route('redaksi.berita-unpublish.index')->with('success', 'Berhasil menambahkan Berita');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('redaksi.berita-unpublish.index')->with('error', 'Gagal menambahkan Berita');
        }
    }

    public function CreateFromLiputan($id)
    {
        $data = [
            'title' => 'Redaksi || Manajemen Liputan dan Berita || Create',
            'liputan' => Liputan::findOrFail($id),
            'liputans' => Liputan::where('status', 'mengantri')->get(),
            // berita where status != publish
            // 'beritas' => Berita::get()->where('status', '!=', 'publish'),
        ];
        return view('redaksi.unpublish.create-from-liputan', $data);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Redaksi || Manajemen Liputan dan Berita || Edit',
            'berita' => Berita::findOrFail($id),
            'liputans' => Liputan::get(),
            // berita where status != publish
            // 'beritas' => Berita::get()->where('status', '!=', 'publish'),
        ];
        return view('redaksi.unpublish.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'volume' => 'required',
            // 'slug' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $berita = Berita::findOrFail($id);

            $slug = Str::slug($request->judul . '-' . time());

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                foreach ($foto as $f) {
                    $filename = time() . '-' . $f->getClientOriginalName();
                    $f->move(public_path('img/berita'), $filename);

                    $data[] = $filename;
                }
                $encoded = json_encode($data);
            } else {
                $encoded = $berita->gambar;
            }

            // check if its revisi
            if ($berita->status == 'ditolak') {
                $data = [
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'old_isi' => $berita->isi,
                    'gambar' => $encoded,
                    'slug' => $slug,
                    'user_id' => auth()->user()->id,
                    'status' => 'revisi',
                    'volume' => $request->volume,
                ];
            } elseif ($berita->status == 'revisi') {
                $data = [
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    // 'old_isi' => $berita->isi,
                    'gambar' => $encoded,
                    'slug' => $slug,
                    'user_id' => auth()->user()->id,
                    'status' => 'revisi',
                    'volume' => $request->volume,
                ];
            } else {
                $data = [
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'gambar' => $encoded,
                    'slug' => $slug,
                    'user_id' => auth()->user()->id,
                    'status' => 'draft',
                    'volume' => $request->volume,
                ];
            }

            $berita->update($data);

            DB::commit();
            return redirect()->route('redaksi.berita-unpublish.index')->with('success', 'Berhasil mengubah Berita');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('redaksi.berita-unpublish.index')->with('error', 'Gagal mengubah Berita');
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $berita = Berita::findOrFail($id);

            if ($berita->gambar) {
                $gambar = json_decode($berita->gambar);

                if (is_array($gambar)) {
                    foreach ($gambar as $g) {
                        // check if image exists in folder
                        if (file_exists(public_path('img/berita/' . $g))) {
                            unlink(public_path('img/berita/' . $g));
                        }
                    }
                } else {
                    // check if image exists in folder
                    if (file_exists(public_path('img/berita/' . $gambar))) {
                        unlink(public_path('img/berita/' . $gambar));
                    }
                }
            }
            // change status liputan
            $liputan = Liputan::findOrFail($berita->liputan_id);
            $liputan->update([
                'status' => 'mengantri'
            ]);

            $berita->delete();

            DB::commit();
            return redirect()->route('redaksi.berita-unpublish.index')->with('success', 'Berhasil menghapus Berita');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('redaksi.berita-unpublish.index')->with('error', 'Gagal menghapus Berita');
        }
    }

    public function hariPeringatan()
    {
        $hari_peringatan = HariPeringatan::get()->first();
        $data = [
            'title' => 'Redaksi || Manajemen Hari Peringatan',
            'hari_peringatan' => $hari_peringatan,
        ];
        return view('redaksi.peringatan.index', $data);
    }

    public function storeAndUpdate(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $hari_peringatan = HariPeringatan::get()->first();

            $slug = Str::slug($request->judul . '-' . time());

            $filename = null;

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                if (!is_array($foto)) {
                    // single file
                    $filename = time() . '-' . $foto->getClientOriginalName();
                    $foto->move(public_path('img/hariraya'), $filename);
                } else {
                    // multiple file
                    foreach ($foto as $f) {
                        $filename = time() . '-' . $f->getClientOriginalName();
                        $f->move(public_path('img/hariraya'), $filename);
                    }
                }
            } else {
                $filename = $hari_peringatan->gambar;
            }

            $data = [
                'judul' => $request->judul,
                'gambar' => $filename,
                'slug' => $slug,
            ];

            if ($hari_peringatan) {
                $hari_peringatan->update($data);
            } else {
                HariPeringatan::create($data);
            }

            DB::commit();
            return redirect()->route('redaksi.hari-peringatan.index')->with('success', 'Berhasil mengubah Hari Peringatan');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('redaksi.hari-peringatan.index')->with('error', 'Gagal mengubah Hari Peringatan');
        }
    }

    public function sekapurSirih()
    {
        $sekaps = SekapurSirih::get()->first();

        $data = [
            'title' => 'Redaksi || Manajemen Sekapur Sirih',
            'sekaps' => $sekaps,
        ];

        return view('redaksi.sekapur-sirih.index', $data);
    }

    public function storeAndUpdateSekapurSirih(Request $request)
    {
        $request->validate([
            // 'judul' => 'required',
            'isi' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $sekaps = SekapurSirih::get()->first();

            $slug = Str::slug('sekapur' . '-' . time());

            $data = [
                'judul' => 'sekapur',
                'slug' => $slug,
                'isi' => $request->isi,
            ];

            if ($sekaps) {
                $sekaps->update($data);
            } else {
                SekapurSirih::create($data);
            }

            DB::commit();
            return redirect()->route('redaksi.sekapur-sirih.index')->with('success', 'Berhasil mengubah Sekapur Sirih');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('redaksi.sekapur-sirih.index')->with('error', 'Gagal mengubah Sekapur Sirih');
        }
    }
}
