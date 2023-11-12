<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Liputan;
use App\Models\Program;
use App\Models\SistemInformasi;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    //
    public function home()
    {
        $data = [
            'title' => 'Admin || Dashboard',
            'berita' => Berita::where('status', 'publish')->get(),
            'kecamatan' => Kecamatan::all(),
            'kabupaten' => Kabupaten::all(),
            'user' => User::where('level', '!=', 'user')->where('level', '!=', 'admin')->get(),
        ];
        return view('admin.index', $data);
    }

    public function kabupaten()
    {
        $data = [
            'title' => 'Admin || Kabupaten',
            'kabupaten' => Kabupaten::all()
        ];
        return view('admin.kabupaten.index', $data);
    }

    public function kabupatenCreate()
    {
        $data = [
            'title' => 'Admin || Kabupaten || Create'
        ];
        return view('admin.kabupaten.create', $data);
    }

    public function kabupatenStore(Request $request)
    {
        $request->validate([
            'nama_kabupaten' => 'required|unique:kabupatens,nama_kabupaten'
        ]);

        // make slug
        $slug = Str::slug($request->nama_kabupaten);

        Kabupaten::create([
            'nama_kabupaten' => $request->nama_kabupaten,
            'slug' => $slug
        ]);

        return redirect()->route('admin.kabupaten.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function kabupatenEdit($id)
    {
        $data = [
            'title' => 'Admin || Kabupaten || Edit',
            'kabupaten' => Kabupaten::findOrFail($id)
        ];
        return view('admin.kabupaten.edit', $data);
    }

    public function kabupatenUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kabupaten' => 'required|unique:kabupatens,nama_kabupaten,' . $id
        ]);

        // make slug
        $slug = Str::slug($request->nama_kabupaten);

        Kabupaten::findOrFail($id)->update([
            'nama_kabupaten' => $request->nama_kabupaten,
            'slug' => $slug
        ]);

        return redirect()->route('admin.kabupaten.index')->with('success', 'Data berhasil diubah');
    }

    public function kabupatenDelete($id)
    {
        $kabupaten = Kabupaten::findOrFail($id);
        $kabupaten->delete();

        return redirect()->route('admin.kabupaten.index')->with('success', 'Data berhasil dihapus');
    }

    public function kecamatan()
    {
        $data = [
            'title' => 'Admin || Kecamatan',
            'kecamatan' => Kecamatan::all()
        ];
        return view('admin.kecamatan.index', $data);
    }

    public function kecamatanCreate()
    {
        $data = [
            'title' => 'Admin || Kecamatan || Create',
            'kabupaten' => Kabupaten::all()
        ];
        return view('admin.kecamatan.create', $data);
    }

    public function kecamatanStore(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required|unique:kecamatans,nama_kecamatan',
            'kabupaten_id' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->gambar) {
            // upload image
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('img/kecamatan'), $imageName);
        } else {
            $imageName = null;
        }

        // make slug
        $slug = Str::slug($request->nama_kecamatan);

        Kecamatan::create([
            'nama_kecamatan' => $request->nama_kecamatan,
            'slug' => $slug,
            'kabupaten_id' => $request->kabupaten_id,
            'gambar' => $imageName
        ]);

        return redirect()->route('admin.kecamatan.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function kecamatanEdit($id)
    {
        $data = [
            'title' => 'Admin || Kecamatan || Edit',
            'kecamatan' => Kecamatan::findOrFail($id),
            'kabupaten' => Kabupaten::all()
        ];
        return view('admin.kecamatan.edit', $data);
    }

    public function kecamatanUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kecamatan' => 'required|unique:kecamatans,nama_kecamatan,' . $id,
            'kabupaten_id' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->gambar) {
            // upload image
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('img/kecamatan'), $imageName);

            // delete image
            if (Kecamatan::findOrFail($id)->gambar) {
                unlink(public_path('img/kecamatan/' . Kecamatan::findOrFail($id)->gambar));
            }
        } else {
            $imageName = Kecamatan::findOrFail($id)->gambar;
        }

        // make slug
        $slug = Str::slug($request->nama_kecamatan);

        Kecamatan::findOrFail($id)->update([
            'nama_kecamatan' => $request->nama_kecamatan,
            'slug' => $slug,
            'kabupaten_id' => $request->kabupaten_id,
            'gambar' => $imageName
        ]);

        return redirect()->route('admin.kecamatan.index')->with('success', 'Data berhasil diubah');
    }

    public function kecamatanDelete($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        // delete image
        if ($kecamatan->gambar) {
            unlink(public_path('img/kecamatan/' . $kecamatan->gambar));
        }
        $kecamatan->delete();

        return redirect()->route('admin.kecamatan.index')->with('success', 'Data berhasil dihapus');
    }

    public function berita()
    {
        $data = [
            'title' => 'Admin || Berita',
            'berita' => Berita::all()
        ];
        return view('admin.berita.index', $data);
    }

    public function beritaCreate()
    {
        $data = [
            'title' => 'Admin || Berita || Create',
            'kecamatan' => Kecamatan::all()
        ];
        return view('admin.berita.create', $data);
    }

    public function beritaStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|unique:beritas,judul',
            'isi' => 'required',
            'kecamatan_id' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // make slug
        $slug = Str::slug($request->judul . '-' . time());

        // upload image
        $imageName = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('img/berita'), $imageName);

        Berita::create([
            'judul' => $request->judul,
            'slug' => $slug,
            'isi' => $request->isi,
            'gambar' => $imageName,
            'kecamatan_id' => $request->kecamatan_id,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function beritaEdit($id)
    {
        $data = [
            'title' => 'Admin || Berita || Edit',
            'berita' => Berita::findOrFail($id),
            'kecamatan' => Kecamatan::all()
        ];
        return view('admin.berita.edit', $data);
    }

    public function beritaUpdate(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|unique:beritas,judul,' . $id,
            'isi' => 'required',
            'kecamatan_id' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // make slug
        $slug = Str::slug($request->judul . '-' . time());

        // upload image
        if ($request->gambar) {
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('img/berita'), $imageName);
        } else {
            $imageName = Berita::findOrFail($id)->gambar;
        }

        Berita::findOrFail($id)->update([
            'judul' => $request->judul,
            'slug' => $slug,
            'isi' => $request->isi,
            'gambar' => $imageName,
            'kecamatan_id' => $request->kecamatan_id
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Data berhasil diubah');
    }

    public function beritaDetailAdmin(Berita $berita)
    {
        $data = [
            'title' => 'Admin || Berita || Detail',
            'berita' => $berita
        ];
        return view('admin.berita.detail', $data);
    }

    public function beritaTolak(Request $request, Berita $berita)
    {
        $request->validate([
            'saran_revisi' => 'required'
        ]);

        $berita->update([
            'status' => 'ditolak'
        ]);

        $slug = Str::slug($berita->judul . '-' . time());

        $berita->saranRevisi()->create([
            'isi' => $request->saran_revisi,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Data berhasil ditolak');
    }

    public function beritaDelete($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Data berhasil dihapus');
    }

    public function beritaPublish(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $berita = Berita::findOrFail($id);
        $berita->update([
            'status' => $request->status
        ]);

        if ($request->status == 'publish') {
            // delete saran revisi
            $berita->saranRevisi()->delete();
        }

        return redirect()->route('admin.berita.index')->with('success', 'Data berhasil dipublish');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // upload image
        $imageName = time() . '.' . $request->upload->extension();
        $request->upload->move(public_path('img/berita'), $imageName);

        return response()->json([
            'uploaded' => true,
            'url' => asset('img/berita/' . $imageName)
        ]);
    }

    // sistem informasi
    public function sistemInformasi()
    {
        $data = [
            'title' => 'Admin || Sistem Informasi',
            'sistem_informasi' => SistemInformasi::all()
        ];
        return view('admin.sistem-informasi.index', $data);
    }

    public function sistemInformasiCreate()
    {
        $data = [
            'title' => 'Admin || Sistem Informasi || Create'
        ];
        return view('admin.sistem-informasi.create', $data);
    }

    public function sistemInformasiStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:sistem_informasis,nama',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jabatan' => 'required',
        ]);

        // make slug
        $slug = Str::slug($request->nama_sistem_informasi . '-' . time());

        // upload image
        $imageName = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('img/sistem-informasi'), $imageName);

        SistemInformasi::create([
            'nama' => $request->nama,
            'slug' => $slug,
            'foto' => $imageName,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.sistem-informasi.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function sistemInformasiEdit($id)
    {
        $data = [
            'title' => 'Admin || Sistem Informasi || Edit',
            'sistem_informasi' => SistemInformasi::findOrFail($id)
        ];
        return view('admin.sistem-informasi.edit', $data);
    }

    public function sistemInformasiUpdate($id, Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:sistem_informasis,nama',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jabatan' => 'required',
        ]);

        // make slug
        $slug = Str::slug($request->nama_sistem_informasi . '-' . time());

        // upload image
        $imageName = time() . '.' . $request->struktur_organisasi->extension();
        $request->struktur_organisasi->move(public_path('img/sistem-informasi'), $imageName);

        SistemInformasi::findOrFail($id)->update([
            'nama' => $request->nama,
            'slug' => $slug, // 'slug' => $slug,
            'foto' => $imageName,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.sistem-informasi.index')->with('success', 'Data berhasil diubah');
    }

    // public function sistemInformasiChangeStatus($id)
    // {
    //     $sistem_informasi = SistemInformasi::findOrFail($id);
    //     if ($sistem_informasi->status == 'aktif') {
    //         $sistem_informasi->update([
    //             'status' => 'nonaktif'
    //         ]);
    //     } else {
    //         $sistem_informasi->update([
    //             'status' => 'aktif'
    //         ]);
    //     }

    //     return redirect()->route('admin.sistem-informasi.index')->with('success', 'Data berhasil diubah');
    // }

    public function sistemInformasiDelete($id)
    {
        $sistem_informasi = SistemInformasi::findOrFail($id);
        $sistem_informasi->delete();

        return redirect()->route('admin.sistem-informasi.index')->with('success', 'Data berhasil dihapus');
    }

    // program
    public function program()
    {
        $data = [
            'title' => 'Admin || Program',
            'program' => Program::all()
        ];
        return view('admin.program.index', $data);
    }

    public function programCreate()
    {
        $data = [
            'title' => 'Admin || Program || Create'
        ];
        return view('admin.program.create', $data);
    }

    public function programStore(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|unique:programs,nama_program',
            'deskripsi' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // make slug
        $slug = Str::slug($request->nama_program . '-' . time());

        // upload image
        $imageName = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('img/program'), $imageName);

        Program::create([
            'nama_program' => $request->nama_program,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'foto' => $imageName
        ]);

        return redirect()->route('admin.program.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function programEdit($id)
    {
        $data = [
            'title' => 'Admin || Program || Edit',
            'program' => Program::findOrFail($id)
        ];
        return view('admin.program.edit', $data);
    }

    public function programUpdate($id, Request $request)
    {
        $request->validate([
            'nama_program' => 'required|unique:programs,nama_program,' . $id,
            'deskripsi' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // make slug
        $slug = Str::slug($request->nama_program . '-' . time());

        // upload image
        if ($request->foto) {
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('img/program'), $imageName);
        } else {
            $imageName = Program::findOrFail($id)->foto;
        }

        Program::findOrFail($id)->update([
            'nama_program' => $request->nama_program,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'foto' => $imageName
        ]);

        return redirect()->route('admin.program.index')->with('success', 'Data berhasil diubah');
    }

    public function programDelete($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('admin.program.index')->with('success', 'Data berhasil dihapus');
    }

    // managemen sponsor
    public function managementSponsor()
    {
        $data = [
            'title' => 'Admin || Management Sponsor',
            'sponsor' => Sponsor::all()
        ];
        return view('admin.management-sponsor.index', $data);
    }

    public function managementSponsorCreate()
    {
        $data = [
            'title' => 'Admin || Management Sponsor || Create'
        ];
        return view('admin.management-sponsor.create', $data);
    }

    public function managementSponsorStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:sponsors,nama',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            // make slug
            $slug = Str::slug($request->nama . '-' . time());

            // upload image
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('img/sponsor'), $imageName);

            Sponsor::create([
                'nama' => $request->nama,
                'gambar' => $imageName,
                'link' => $request->link,
            ]);

            DB::commit();
            return redirect()->route('admin.management-sponsor.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.management-sponsor.index')->with('error', 'Data gagal ditambahkan');
        }
    }

    public function managementSponsorEdit($id)
    {
        $data = [
            'title' => 'Admin || Management Sponsor || Edit',
            'sponsor' => Sponsor::findOrFail($id)
        ];
        return view('admin.management-sponsor.edit', $data);
    }

    public function managementSponsorUpdate($id, Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:sponsors,nama,' . $id,
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            // make slug
            $slug = Str::slug($request->nama . '-' . time());

            // upload image
            if ($request->gambar) {
                $imageName = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('img/sponsor'), $imageName);

                // delete image
                if (Sponsor::findOrFail($id)->gambar) {
                    unlink(public_path('img/sponsor/' . Sponsor::findOrFail($id)->gambar));
                }
            } else {
                $imageName = Sponsor::findOrFail($id)->gambar;
            }

            Sponsor::findOrFail($id)->update([
                'nama' => $request->nama,
                'gambar' => $imageName,
                'link' => $request->link,
            ]);

            DB::commit();
            return redirect()->route('admin.management-sponsor.index')->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.management-sponsor.index')->with('error', 'Data gagal diubah');
        }
    }

    public function managementSponsorDelete($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        // delete image
        if ($sponsor->gambar) {
            unlink(public_path('img/sponsor/' . $sponsor->gambar));
        }
        $sponsor->delete();

        return redirect()->route('admin.management-sponsor.index')->with('success', 'Data berhasil dihapus');
    }

    public function userManagementCreate()
    {
        $data = [
            'title' => 'Admin || User Management || Create'
        ];
        return view('admin.create', $data);
    }

    public function userManagementStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'level' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // dd($request->level);

        // check avatar
        if ($request->avatar) {
            // upload image
            $imageName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('img/avatar'), $imageName);
        } else {
            $imageName = null;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => bcrypt($request->password),
            'avatar' => $imageName
        ]);

        return redirect()->route('admin.home')->with('success', 'Data berhasil ditambahkan');
    }

    public function userManagementEdit($id)
    {
        $data = [
            'title' => 'Admin || User Management || Edit',
            'user' => User::findOrFail($id)
        ];
        return view('admin.edit', $data);
    }

    public function userManagementUpdate($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'level' => 'required',
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // check avatar
        if ($request->avatar) {
            // upload image
            $imageName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('img/avatar'), $imageName);

            // delete image
            if (User::findOrFail($id)->avatar) {
                unlink(public_path('img/avatar/' . User::findOrFail($id)->avatar));
            }
        } else {
            $imageName = User::findOrFail($id)->avatar;
        }

        if ($request->password) {
            User::findOrFail($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'level' => $request->level,
                'password' => bcrypt($request->password),
                'avatar' => $imageName
            ]);
        } else {
            User::findOrFail($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'level' => $request->level,
                'avatar' => $imageName
            ]);
        }

        return redirect()->route('admin.home')->with('success', 'Data berhasil diubah');
    }

    public function userManagementDelete($id)
    {
        $user = User::findOrFail($id);
        // delete image
        if ($user->avatar) {
            unlink(public_path('img/avatar/' . $user->avatar));
        }
        $user->delete();

        return redirect()->route('admin.home')->with('success', 'Data berhasil dihapus');
    }
}
