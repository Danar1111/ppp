<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/berita', [PublicController::class, 'beritaIndex'])->name('public.berita.index');
Route::get('/berita/{slug}', [PublicController::class, 'beritaShow'])->name('public.berita.show');
Route::get('/struktur', [PublicController::class, 'struktur'])->name('public.struktur');
Route::post('/cek-nik', [PublicController::class, 'cekNik'])->name('public.cek-nik');

// Member Self-Scan Routes (Protected by member session)
Route::middleware(['auth:web'])->group(function () {
    // Scenario B: Event Self-Scan
    Route::get('/portal/absen/event/{event}', [AttendanceController::class, 'showEventForm'])->name('absen.event');
    Route::post('/portal/absen/event/{event}/submit', [AttendanceController::class, 'submitEventAttendance'])->name('absen.event.submit');

    // Scenario C: Daily Office Self-Scan
    Route::get('/portal/absen-harian/{office}', [AttendanceController::class, 'showHarianForm'])->name('absen.harian');
    Route::post('/portal/absen-harian/{office}/submit', [AttendanceController::class, 'submitHarianAttendance'])->name('absen.harian.submit');
});

// Admin AJAX scanning endpoints (two-step live scanner)
Route::post('/absen/event/{event}/scan-details', [AttendanceController::class, 'scanDetails'])->name('absen.event.scan-details');
Route::post('/absen/event/{event}/scan-confirm', [AttendanceController::class, 'scanConfirm'])->name('absen.event.scan-confirm');

// Admin Document Download with Friendly Filename
Route::get('/admin/dokumen/{document}/download', function (\App\Models\Document $document) {
    if (!auth('web')->check() && !auth('member')->check()) {
        abort(403);
    }
    
    $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
    $sluggedTitle = \Illuminate\Support\Str::slug($document->title);
    $fileName = "{$sluggedTitle}.{$extension}";

    return \Illuminate\Support\Facades\Storage::disk('public')->response($document->file_path, $fileName);
})->name('document.download')->middleware('auth:web,member');

