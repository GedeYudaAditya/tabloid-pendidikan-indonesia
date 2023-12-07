<?php

namespace App\Http\Controllers;

use App\Models\Artikle;
use App\Models\Berita;
use App\Models\Jurnal;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JurnalisController extends Controller
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

    public function jurnal()
    {
        $data = [
            'title' => 'Manajemen Jurnal',
            'jurnal' => Jurnal::all()
        ];

        return view('jurnalis.jurnal.index', $data);
    }

    // create view
    public function jurnalCreate()
    {
        $data = [
            'title' => 'Tambah Jurnal',
            'kabupaten' => Kabupaten::all(),
        ];
        return view('jurnalis.jurnal.create', $data);
    }

    // store data
    public function jurnalStore(Request $request)
    {
        // dd(auth()->user()->id);

        $request->validate([
            'judul' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required',
            'attachment' => 'required|mimes:pdf|max:20480',
        ]);

        try {
            DB::beginTransaction();

            $data = [];

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                foreach ($foto as $f) {
                    $filename = time() . '-' . $f->getClientOriginalName();
                    $f->move(public_path('img/jurnal-arikel'), $filename);

                    $data[] = $filename;
                }
            }

            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $attachmentName = time() . '-' . $request->file('attachment')->getClientOriginalName();
                $attachment->move(public_path('attachment'), $attachmentName);
            }

            if ($data) {
                $data = json_encode($data);
            } else {
                $data = null;
            }

            Jurnal::create([
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'gambar' => $data,
                'isi' => $request->isi,
                'attachment' => $attachmentName ?? null,
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            return redirect()->route('jurnalis.jurnal.index')->with('success', 'Jurnal berhasil ditambahkan');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->route('jurnalis.jurnal.index')->with('error', 'Jurnal gagal ditambahkan');
        }
    }

    // edit view
    public function jurnalEdit($id)
    {
        $data = [
            'title' => 'Edit Jurnal',
            'jurnal' => Jurnal::findOrFail($id),
            'kabupaten' => Kabupaten::all(),
        ];
        return view('jurnalis.jurnal.edit', $data);
    }

    // update data
    public function jurnalUpdate(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required',
            'attachment' => 'required|mimes:pdf|max:20480',
        ]);

        $data = [];

        try {
            DB::beginTransaction();

            $jurnal = Jurnal::findOrFail($id);

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                foreach ($foto as $f) {
                    $filename = time() . '-' . $f->getClientOriginalName();
                    $f->move(public_path('img/jurnal-arikel'), $filename);

                    $data[] = $filename;
                }

                // delete old file
                $gambar = json_decode($jurnal->gambar);
                foreach ($gambar as $g) {
                    if (file_exists(public_path('img/jurnal-arikel/' . $g))) {
                        unlink(public_path('img/jurnal-arikel/' . $g));
                    }
                }
            }

            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $attachmentName = time() . '-' . $request->file('attachment')->getClientOriginalName();
                $attachment->move(public_path('attachment'), $attachmentName);

                // delete old file
                if ($jurnal->attachment && file_exists(public_path('attachment/' . $jurnal->attachment))) {
                    unlink(public_path('attachment/' . $jurnal->attachment));
                }
            }

            $jurnal->update([
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'gambar' => json_encode($data) ?? $jurnal->gambar,
                'isi' => $request->isi,
                'attachment' => $attachmentName ?? $jurnal->attachment,
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            return redirect()->route('jurnalis.jurnal.index')->with('success', 'Jurnal berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('jurnalis.jurnal.index')->with('error', 'Jurnal gagal diupdate');
        }
    }

    // delete data
    public function jurnalDestroy($id)
    {
        try {
            DB::beginTransaction();

            $jurnal = Jurnal::findOrFail($id);

            // delete old file
            $gambar = json_decode($jurnal->gambar);
            if (is_array($gambar)) {
                foreach ($gambar as $g) {
                    if (file_exists(public_path('img/jurnal-arikel/' . $g))) {
                        unlink(public_path('img/jurnal-arikel/' . $g));
                    }
                }
            } else {
                if (file_exists(public_path('img/jurnal-arikel/' . $gambar))) {
                    unlink(public_path('img/jurnal-arikel/' . $gambar));
                }
            }

            if ($jurnal->attachment) {
                if (file_exists(public_path('attachment/' . $jurnal->attachment))) {
                    unlink(public_path('attachment/' . $jurnal->attachment));
                }
            }

            $jurnal->delete();

            DB::commit();
            return redirect()->route('jurnalis.jurnal.index')->with('success', 'Jurnal berhasil dihapus');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->route('jurnalis.jurnal.index')->with('error', 'Jurnal gagal dihapus');
        }
    }

    // artiikel
    public function artikel()
    {
        $data = [
            'title' => 'Manajemen Artikel',
            'artikel' => Artikle::all()
        ];

        return view('jurnalis.artikel.index', $data);
    }

    // create view
    public function artikelCreate()
    {
        $data = [
            'title' => 'Tambah Artikel',
            'kabupaten' => Kabupaten::all(),
        ];
        return view('jurnalis.artikel.create', $data);
    }

    // store data
    public function artikelStore(Request $request)
    {
        // dd(auth()->user()->id);

        $request->validate([
            'judul' => 'required',
            'gambar' => 'required',
            'gambar.*' => 'mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                foreach ($foto as $f) {
                    $filename = time() . '-' . $f->getClientOriginalName();
                    $f->move(public_path('img/jurnal-arikel'), $filename);

                    $data[] = $filename;
                }
            }

            Artikle::create([
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'gambar' => json_encode($data),
                'isi' => $request->isi,
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            return redirect()->route('jurnalis.artikel.index')->with('success', 'Artikel berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('jurnalis.artikel.index')->with('error', 'Artikel gagal ditambahkan');
        }
    }

    // edit view
    public function artikelEdit($id)
    {
        $data = [
            'title' => 'Edit Artikel',
            'artikel' => Artikle::findOrFail($id),
            'kabupaten' => Kabupaten::all(),
        ];
        return view('jurnalis.artikel.edit', $data);
    }

    // update data
    public function artikelUpdate(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required',
        ]);

        $data = [];

        try {
            DB::beginTransaction();

            $artikel = Artikle::findOrFail($id);

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                foreach ($foto as $f) {
                    $filename = time() . '-' . $f->getClientOriginalName();
                    $f->move(public_path('img/jurnal-arikel'), $filename);

                    $data[] = $filename;
                }

                // delete old file
                $gambar = json_decode($artikel->gambar);
                foreach ($gambar as $g) {
                    unlink(public_path('img/jurnal-arikel/' . $g));
                }
            }

            $artikel->update([
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'gambar' => json_encode($data) ?? $artikel->gambar,
                'isi' => $request->isi,
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            return redirect()->route('jurnalis.artikel.index')->with('success', 'Artikel berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('jurnalis.artikel.index')->with('error', 'Artikel gagal diupdate');
        }
    }

    // delete data
    public function artikelDestroy($id)
    {
        try {
            DB::beginTransaction();

            $artikel = Artikle::findOrFail($id);

            // delete old file
            $gambar = json_decode($artikel->gambar);
            if (is_array($gambar)) {
                foreach ($gambar as $g) {
                    unlink(public_path('img/jurnal-arikel/' . $g));
                }
            } else {
                unlink(public_path('img/jurnal-arikel/' . $gambar));
            }

            $artikel->delete();

            DB::commit();
            return redirect()->route('jurnalis.artikel.index')->with('success', 'Artikel berhasil dihapus');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->route('jurnalis.artikel.index')->with('error', 'Artikel gagal dihapus');
        }
    }
}
