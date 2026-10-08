<?php

use App\Http\Controllers\Admin\AdminAlumniController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminBusinessController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminJobVacancyController;
use App\Http\Controllers\Admin\AdminProgramController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AlumniVerificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EventRsvpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAlumniController;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\PublicBusinessController;
use App\Http\Controllers\PublicCareerController;
use App\Http\Controllers\PublicContactController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\PublicProgramController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserBusinessController;
use App\Http\Controllers\UserJobVacancyController;
use Illuminate\Support\Facades\Route;

// Public Landing, Alumni Directory, Business Catalog, Careers & Programs
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/alumni', [PublicAlumniController::class, 'index'])->name('alumni.index');
Route::get('/alumni/{slug}', [PublicAlumniController::class, 'show'])->name('alumni.show');
Route::get('/bisnis', [PublicBusinessController::class, 'index'])->name('business.index');
Route::get('/bisnis/{slug}', [PublicBusinessController::class, 'show'])->name('business.show');
Route::get('/karier', [PublicCareerController::class, 'index'])->name('career.index');
Route::get('/karier/{slug}', [PublicCareerController::class, 'show'])->name('career.show');
Route::get('/program', [PublicProgramController::class, 'index'])->name('program.index');
Route::get('/program/{slug}', [PublicProgramController::class, 'show'])->name('program.show');
Route::get('/artikel', [PublicArticleController::class, 'index'])->name('article.index');
Route::get('/artikel/{slug}', [PublicArticleController::class, 'show'])->name('article.show');
Route::get('/event', [PublicEventController::class, 'index'])->name('event.index');
Route::get('/event/{slug}', [PublicEventController::class, 'show'])->name('event.show');
Route::get('/galeri', [PublicGalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{slug}', [PublicGalleryController::class, 'show'])->name('gallery.show');
Route::get('/kontak', [PublicContactController::class, 'show'])->name('contact.show');
Route::post('/kontak', [PublicContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');
Route::post('/daftar-alumni', [RegistrationController::class, 'store'])->name('alumni.register');

// Authentication (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated User Routes (Alumni / Admin)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Alumni Business & Career Self-Submission & Management (Requires approved membership)
    Route::middleware('alumni.approved')->prefix('profil')->name('profile.')->group(function () {
        Route::get('/bisnis', [UserBusinessController::class, 'index'])->name('business.index');
        Route::get('/bisnis/create', [UserBusinessController::class, 'create'])->name('business.create');
        Route::post('/bisnis', [UserBusinessController::class, 'store'])->name('business.store');
        Route::get('/bisnis/{business}/edit', [UserBusinessController::class, 'edit'])->name('business.edit');
        Route::put('/bisnis/{business}', [UserBusinessController::class, 'update'])->name('business.update');
        Route::delete('/bisnis/{business}', [UserBusinessController::class, 'destroy'])->name('business.destroy');

        Route::get('/lowongan', [UserJobVacancyController::class, 'index'])->name('job.index');
        Route::get('/lowongan/create', [UserJobVacancyController::class, 'create'])->name('job.create');
        Route::post('/lowongan', [UserJobVacancyController::class, 'store'])->name('job.store');
        Route::get('/lowongan/{jobVacancy}/edit', [UserJobVacancyController::class, 'edit'])->name('job.edit');
        Route::put('/lowongan/{jobVacancy}', [UserJobVacancyController::class, 'update'])->name('job.update');
        Route::delete('/lowongan/{jobVacancy}', [UserJobVacancyController::class, 'destroy'])->name('job.destroy');
        Route::patch('/lowongan/{jobVacancy}/toggle-status', [UserJobVacancyController::class, 'toggleStatus'])->name('job.toggle-status');
    });

    // Event RSVP
    Route::post('/event/{slug}/rsvp', [EventRsvpController::class, 'store'])->name('event.rsvp');
    Route::delete('/event/{slug}/rsvp', [EventRsvpController::class, 'destroy'])->name('event.rsvp.cancel');
});

// Admin & Pengurus Protected Area
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('alumni', AdminAlumniController::class);
    Route::patch('alumni/{alumnus}/toggle-verification', [AdminAlumniController::class, 'toggleVerification'])->name('alumni.toggle-verification');
    Route::post('alumni/{id}/restore', [AdminAlumniController::class, 'restore'])->name('alumni.restore');

    // Alumni Membership Verification Workflow
    Route::get('/verifikasi', [AlumniVerificationController::class, 'index'])->name('verification.index');
    Route::get('/verifikasi/{alumnus}', [AlumniVerificationController::class, 'show'])->name('verification.show');
    Route::post('/verifikasi/{alumnus}/approve', [AlumniVerificationController::class, 'approve'])->name('verification.approve');
    Route::post('/verifikasi/{alumnus}/reject', [AlumniVerificationController::class, 'reject'])->name('verification.reject');

    // Alumni Business Management
    Route::resource('businesses', AdminBusinessController::class);
    Route::patch('businesses/{business}/publish', [AdminBusinessController::class, 'publish'])->name('businesses.publish');

    // Job Vacancies Management
    Route::resource('job-vacancies', AdminJobVacancyController::class);
    Route::patch('job-vacancies/{jobVacancy}/toggle-status', [AdminJobVacancyController::class, 'toggleStatus'])->name('job-vacancies.toggle-status');

    // Work Programs Management
    Route::resource('programs', AdminProgramController::class);

    // Articles Management
    Route::resource('articles', AdminArticleController::class);
    Route::patch('articles/{article}/toggle-status', [AdminArticleController::class, 'toggleStatus'])->name('articles.toggle-status');

    // Events & Agenda Management
    Route::resource('events', AdminEventController::class);
    Route::patch('events/{event}/toggle-status', [AdminEventController::class, 'toggleStatus'])->name('events.toggle-status');
    Route::get('events/{event}/participants', [AdminEventController::class, 'participants'])->name('events.participants');

    // Photo Gallery Management
    Route::resource('galleries', AdminGalleryController::class);
    Route::patch('galleries/{gallery}/toggle-status', [AdminGalleryController::class, 'toggleStatus'])->name('galleries.toggle-status');
    Route::post('galleries/{gallery}/photos', [AdminGalleryController::class, 'uploadPhotos'])->name('galleries.photos.upload');
    Route::delete('gallery-photos/{photo}', [AdminGalleryController::class, 'deletePhoto'])->name('galleries.photos.delete');

    // Contact & Inbox Management
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}/read', [AdminContactController::class, 'markRead'])->name('contacts.read');
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    // Website General Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
