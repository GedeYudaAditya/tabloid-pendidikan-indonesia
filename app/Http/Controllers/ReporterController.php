<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Liputan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReporterController extends Controller
{
    //
    public function home()
    {
        $data = [
            'title' => 'Reporter || Dashboard',
            'berita' => Berita::where('status', 'publish')->get(),
            'kecamatan' => Kecamatan::all(),
            'kabupaten' => Kabupaten::all(),
            'user' => User::where('level', '!=', 'user')->where('level', '!=', 'admin')->get(),
        ];
        return view('admin.index', $data);
    }

    public function liputan()
    {
        $data = [
            'title' => 'Reporter || Liputan',
            'liputan' => Liputan::all()
        ];
        return view('reporter.index', $data);
    }

    public function liputanShow($id)
    {
        $data = [
            'title' => 'Reporter || Liputan || Detail',
            'liputan' => Liputan::where('id', $id)->firstOrFail()
        ];
        return view('reporter.show', $data);
    }

    public function liputanCreate()
    {
        $data = [
            'title' => 'Reporter || Liputan || Create',
            'kabupaten' => Kabupaten::all(),
        ];
        return view('reporter.create', $data);
    }

    public function liputanStore(Request $request)
    {
        // dd(auth()->user()->id);

        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'kecamatan_id' => 'required',
            // 'slug' => 'required',
        ]);

        try {
            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                if (count($foto) > 1) {
                    foreach ($foto as $f) {
                        $filename = time() . '-' . $f->getClientOriginalName();
                        $f->move(public_path('img/liputan'), $filename);

                        // data name file for save to database
                        $data[] = $filename;
                    }
                } else {
                    $filename = time() . '-' . $foto[0]->getClientOriginalName();
                    $foto[0]->move(public_path('img/liputan'), $filename);

                    // data name file for save to database
                    $data = $filename;
                }
            }

            $slug = Str::slug($request->judul . '-' . time());

            $encoded = json_encode($data);

            DB::beginTransaction();

            Liputan::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'gambar' => $encoded,
                'kecamatan_id' => $request->kecamatan_id,
                'slug' =>   $slug,
                'reporter_id' => auth()->user()->id
            ]);

            DB::commit();

            return redirect()->route('reporter.liputan.index')->with('success', 'Liputan berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->back()->with('error', 'Liputan gagal ditambahkan');
        }
    }

    public function liputanEdit($id)
    {
        $data = [
            'title' => 'Reporter || Liputan || Edit',
            'liputan' => Liputan::findOrFail($id),
            'kabupaten' => Kabupaten::all(),
        ];
        return view('reporter.edit', $data);
    }

    public function liputanUpdate(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'nullable',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'kecamatan_id' => 'required',
            // 'slug' => 'required',
        ]);

        try {
            $slug = Str::slug($request->judul . '-' . time());

            $liputan = Liputan::findOrFail($id);

            // remove old image
            if ($liputan->gambar) {
                // string json to array
                $old_image = json_decode($liputan->gambar);

                foreach ($old_image as $old) {
                    // remove image
                    // check if image exists in folder
                    if (file_exists(public_path('img/liputan/' . $old))) {
                        unlink(public_path('img/liputan/' . $old));
                    }
                }
            }

            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');

                // multiple file
                if (count($foto) > 1) {
                    foreach ($foto as $f) {
                        $filename = time() . '-' . $f->getClientOriginalName();
                        $f->move(public_path('img/liputan'), $filename);

                        // data name file for save to database
                        $data[] = $filename;
                    }
                } else {
                    $filename = time() . '-' . $foto[0]->getClientOriginalName();
                    $foto[0]->move(public_path('img/liputan'), $filename);

                    // data name file for save to database
                    $data = $filename;
                }
            } else {
                $data = json_decode($liputan->gambar);
            }

            DB::beginTransaction();

            $liputan->update([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'gambar' => json_encode($data),
                'kecamatan_id' => $request->kecamatan_id,
                'slug' =>   $slug,
                'reporter_id' => auth()->user()->id
            ]);

            DB::commit();
            return redirect()->route('reporter.liputan.index')->with('success', 'Liputan berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->back()->with('error', 'Liputan gagal diupdate');
        }
    }

    public function liputanDelete($id)
    {
        try {
            $liputan = Liputan::findOrFail($id);

            // remove old image
            if ($liputan->gambar) {
                // string json to array
                $old_image = json_decode($liputan->gambar);
                if (is_array($old_image)) {
                    foreach ($old_image as $old) {
                        // remove image
                        // check if image exists in folder
                        if (file_exists(public_path('img/liputan/' . $old))) {
                            unlink(public_path('img/liputan/' . $old));
                        }
                    }
                } else {
                    // check if image exists in folder
                    if (file_exists(public_path('img/liputan/' . $old_image))) {
                        unlink(public_path('img/liputan/' . $old_image));
                    }
                }
            }

            $liputan->delete();

            return redirect()->route('reporter.liputan.index')->with('success', 'Liputan berhasil dihapus');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with('error', 'Liputan gagal dihapus');
        }
    }
}
