<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\JurnalisController;
use App\Http\Controllers\RedaksiController;
use App\Http\Controllers\ReporterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [Controller::class, 'index'])->middleware('guest')->name('landing');
Route::get('/auth', [Controller::class, 'auth'])->middleware('guest')->name('auth');
Route::post('/login', [Controller::class, 'login'])->middleware('guest')->name('login');
Route::get('/register', [Controller::class, 'register'])->middleware('guest')->name('register');
Route::post('/register', [Controller::class, 'registerProses'])->middleware('guest')->name('register.proses');
Route::get('/logout', [Controller::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/about', [Controller::class, 'about'])->name('about');

// guest
Route::middleware(['guest'])->name('guest')->prefix('/guest')->group(function () {
    Route::get('/berita', [Controller::class, 'berita'])->name('.berita');
    Route::get('/berita/{slug}', [Controller::class, 'beritaDetail'])->name('.berita.detail');
    Route::get('/berita/tag/{slug}', [Controller::class, 'beritaTag'])->name('.berita.tag');
    Route::get('/berita/kabupaten/{slug}', [Controller::class, 'beritaKabupaten'])->name('.berita.kabupaten');
    Route::get('/berita/kecamatan/{slug}', [Controller::class, 'beritaKecamatan'])->name('.berita.kecamatan');
});

// user
Route::middleware(['auth'])->group(function () {
    // likeing berita
    Route::post('/like/{id}', [Controller::class, 'like'])->name('like');
    // get like
    Route::get('/get-like/{id}', [Controller::class, 'getLike'])->name('get.like');
    // comment berita
    Route::post('/comment/{id}', [Controller::class, 'comment'])->name('comment');
    // get comment
    Route::get('/get-comment/{id}', [Controller::class, 'getComment'])->name('get.comment');
    // reply comment
    Route::post('/reply/{id}', [Controller::class, 'reply'])->name('reply');
});


Route::middleware(['auth', 'role'])->name('admin')->prefix('/admin')->group(function () {
    Route::get('/home', [AdminController::class, 'home'])->name('.home');

    // Kabupaten
    Route::prefix('/kabupaten')->name('.kabupaten')->group(function () {
        Route::get('/', [AdminController::class, 'kabupaten'])->name('.index');
        Route::get('/create', [AdminController::class, 'kabupatenCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'kabupatenStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'kabupatenEdit'])->name('.edit');
        Route::post('/{id}/edit', [AdminController::class, 'kabupatenUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'kabupatenDelete'])->name('.delete');
    });

    // Kecamatan
    Route::prefix('/kecamatan')->name('.kecamatan')->group(function () {
        Route::get('/', [AdminController::class, 'kecamatan'])->name('.index');
        Route::get('/create', [AdminController::class, 'kecamatanCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'kecamatanStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'kecamatanEdit'])->name('.edit');
        Route::post('/{id}/edit', [AdminController::class, 'kecamatanUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'kecamatanDelete'])->name('.delete');
    });

    // Berita
    Route::prefix('/berita')->name('.berita')->group(function () {
        Route::get('/', [AdminController::class, 'berita'])->name('.index');
        Route::get('/create', [AdminController::class, 'beritaCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'beritaStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'beritaEdit'])->name('.edit');
        Route::get('/{berita:slug}/detail', [AdminController::class, 'beritaDetailAdmin'])->name('.detail');
        Route::post('/{berita:slug}/tolak', [AdminController::class, 'beritaTolak'])->name('.tolak');
        Route::post('/{id}/edit', [AdminController::class, 'beritaUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'beritaDelete'])->name('.delete');
        Route::put('/{id}/publish', [AdminController::class, 'beritaPublish'])->name('.publish');

        // upload image ckeditor
        Route::post('/upload', [AdminController::class, 'upload'])->name('.upload');
    });

    // sistem informasi
    Route::prefix('/sistem-informasi')->name('.sistem-informasi')->group(function () {
        Route::get('/', [AdminController::class, 'sistemInformasi'])->name('.index');
        Route::get('/create', [AdminController::class, 'sistemInformasiCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'sistemInformasiStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'sistemInformasiEdit'])->name('.edit');
        Route::get('/{id}/change-status', [AdminController::class, 'sistemInformasiChangeStatus'])->name('.change-status');
        Route::post('/{id}/edit', [AdminController::class, 'sistemInformasiUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'sistemInformasiDelete'])->name('.delete');
    });

    // Program
    Route::prefix('/program')->name('.program')->group(function () {
        Route::get('/', [AdminController::class, 'program'])->name('.index');
        Route::get('/create', [AdminController::class, 'programCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'programStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'programEdit'])->name('.edit');
        Route::post('/{id}/edit', [AdminController::class, 'programUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'programDelete'])->name('.delete');
    });

    // Management Sponsor
    Route::prefix('/management-sponsor')->name('.management-sponsor')->group(function () {
        Route::get('/', [AdminController::class, 'managementSponsor'])->name('.index');
        Route::get('/create', [AdminController::class, 'managementSponsorCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'managementSponsorStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'managementSponsorEdit'])->name('.edit');
        Route::post('/{id}/edit', [AdminController::class, 'managementSponsorUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'managementSponsorDelete'])->name('.delete');
    });

    // User management
    Route::prefix('/user-management')->name('.user-management')->group(function () {
        Route::get('/', [AdminController::class, 'userManagement'])->name('.index');
        Route::get('/create', [AdminController::class, 'userManagementCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'userManagementStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'userManagementEdit'])->name('.edit');
        Route::post('/{id}/edit', [AdminController::class, 'userManagementUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'userManagementDelete'])->name('.delete');
    });

    // Buku
    Route::prefix('/buku')->name('.buku')->group(function () {
        Route::get('/', [AdminController::class, 'buku'])->name('.index');
        Route::get('/create', [AdminController::class, 'bukuCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'bukuStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'bukuEdit'])->name('.edit');
        Route::post('/{id}/edit', [AdminController::class, 'bukuUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'bukuDelete'])->name('.delete');
    });

    // Event
    Route::prefix('/event')->name('.event')->group(function () {
        Route::get('/', [AdminController::class, 'event'])->name('.index');
        Route::get('/create', [AdminController::class, 'eventCreate'])->name('.create');
        Route::post('/create', [AdminController::class, 'eventStore'])->name('.store');
        Route::get('/{id}/edit', [AdminController::class, 'eventEdit'])->name('.edit');
        Route::post('/{id}/edit', [AdminController::class, 'eventUpdate'])->name('.update');
        Route::delete('/{id}/delete', [AdminController::class, 'eventDelete'])->name('.delete');
    });

    // jurnal-artikel
    Route::prefix('/jurnal-artikel')->name('.jurnal-artikel')->group(function () {
        Route::get('/', [AdminController::class, 'jurnalArtikel'])->name('.index');
        // ubah status artikel
        Route::put('/{id}/ubah-status', [AdminController::class, 'artikelUpdateStatus'])->name('.artikel.update-status');
        // Show artikel
        Route::get('/{id}/show', [AdminController::class, 'artikelShow'])->name('.artikel.show');
        // ubah status jurnal
        Route::put('/{id}/ubah-status-jurnal', [AdminController::class, 'jurnalUpdateStatus'])->name('.jurnal.update-status');
        // Show jurnal
        Route::get('/{id}/show-jurnal', [AdminController::class, 'jurnalShow'])->name('.jurnal.show');
    });
});

Route::middleware(['auth', 'role'])->name('redaksi')->prefix('redaksi')->group(function () {
    Route::get('/home', [RedaksiController::class, 'home'])->name('.home');

    // Upload Redaksi ke admin
    Route::prefix('/berita-unpublish')->name('.berita-unpublish')->group(function () {
        Route::get('/', [RedaksiController::class, 'unpublishedBerita'])->name('.index');
        Route::get('/create', [RedaksiController::class, 'Create'])->name('.create');
        Route::post('/create', [RedaksiController::class, 'Store'])->name('.store');
        Route::get('/{id}/create-from-liputan', [RedaksiController::class, 'CreateFromLiputan'])->name('.create-from-liputan');
        Route::post('/{id}/create-from-liputan', [RedaksiController::class, 'StoreFromLiputan'])->name('.store-from-liputan');
        Route::get('/{id}/edit', [RedaksiController::class, 'edit'])->name('.edit');
        Route::post('/{id}/edit', [RedaksiController::class, 'Update'])->name('.update');
        Route::delete('/{id}/delete', [RedaksiController::class, 'Delete'])->name('.delete');
    });

    // Upload Hari Peringatan
    Route::prefix('/hari-peringatan')->name('.hari-peringatan')->group(function () {
        Route::get('/', [RedaksiController::class, 'hariPeringatan'])->name('.index');
        Route::post('/create', [RedaksiController::class, 'storeAndUpdate'])->name('.store');
    });

    // Upload Sekapur Sirih
    Route::prefix('/sekapur-sirih')->name('.sekapur-sirih')->group(function () {
        Route::get('/', [RedaksiController::class, 'sekapurSirih'])->name('.index');
        Route::post('/create', [RedaksiController::class, 'storeAndUpdateSekapurSirih'])->name('.store');
    });
});

Route::middleware(['auth', 'role'])->name('reporter')->prefix('reporter')->group(function () {
    Route::get('/home', [ReporterController::class, 'home'])->name('.home');

    // Upload Hasil Liputan
    Route::prefix('/liputan')->name('.liputan')->group(function () {
        Route::get('/', [ReporterController::class, 'liputan'])->name('.index');
        Route::get('/create', [ReporterController::class, 'liputanCreate'])->name('.create');
        Route::post('/create', [ReporterController::class, 'liputanStore'])->name('.store');
        Route::get('/{id}/show', [ReporterController::class, 'liputanShow'])->name('.show');
        Route::get('/{id}/edit', [ReporterController::class, 'liputanEdit'])->name('.edit');
        Route::post('/{id}/edit', [ReporterController::class, 'liputanUpdate'])->name('.update');
        Route::delete('/{id}/delete', [ReporterController::class, 'liputanDelete'])->name('.delete');

        // upload image ckeditor
        Route::post('/upload', [ReporterController::class, 'upload'])->name('.upload');
    });
});

// jurnalis
Route::middleware(['auth', 'role'])->name('jurnalis')->prefix('jurnalis')->group(function () {
    Route::get('/home', [JurnalisController::class, 'home'])->name('.home');

    // jurnal
    Route::prefix('/jurnal')->name('.jurnal')->group(function () {
        Route::get('/', [JurnalisController::class, 'jurnal'])->name('.index');
        Route::get('/create', [JurnalisController::class, 'jurnalCreate'])->name('.create');
        Route::post('/create', [JurnalisController::class, 'jurnalStore'])->name('.store');
        Route::get('/{id}/edit', [JurnalisController::class, 'jurnalEdit'])->name('.edit');
        Route::post('/{id}/edit', [JurnalisController::class, 'jurnalUpdate'])->name('.update');
        Route::delete('/{id}/delete', [JurnalisController::class, 'jurnalDestroy'])->name('.delete');
    });

    // artikel
    Route::prefix('/artikel')->name('.artikel')->group(function () {
        Route::get('/', [JurnalisController::class, 'artikel'])->name('.index');
        Route::get('/create', [JurnalisController::class, 'artikelCreate'])->name('.create');
        Route::post('/create', [JurnalisController::class, 'artikelStore'])->name('.store');
        Route::get('/{id}/edit', [JurnalisController::class, 'artikelEdit'])->name('.edit');
        Route::post('/{id}/edit', [JurnalisController::class, 'artikelUpdate'])->name('.update');
        Route::delete('/{id}/delete', [JurnalisController::class, 'artikelDelete'])->name('.delete');
    });
});

// user
Route::middleware(['auth', 'role'])->name('user')->prefix('user')->group(function () {
    Route::get('/home', [Controller::class, 'index'])->name('.home');
    Route::get('/berita/{slug}', [Controller::class, 'beritaDetail'])->name('.berita.detail');
});
