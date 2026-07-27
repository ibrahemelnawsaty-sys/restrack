<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Ambassador;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Instructor;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// ---------- Public ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricing', [HomeController::class, 'index'])->name('pricing');
Route::get('/certificates/verify/{uuid}', [CertificateController::class, 'verify'])->name('certificates.verify');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/r/{code}', [ReferralController::class, 'capture'])->name('referral.capture');

// ---------- Guest auth ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Authenticated ----------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Checkout / subscription
    Route::get('/checkout/{plan}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{plan}', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/{plan}/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');

    // Certificates (own)
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');

    // Subscription-gated content
    Route::middleware('subscribed')->group(function () {
        Route::get('/program', [ProgramController::class, 'index'])->name('program.index');
        Route::get('/levels/{level:slug}', [ProgramController::class, 'show'])->name('levels.show');

        Route::get('/lectures/{lecture}', [LectureController::class, 'show'])->name('lectures.show');
        Route::post('/lectures/{lecture}/progress', [LectureController::class, 'progress'])->name('lectures.progress');
        Route::get('/lectures/{lecture}/stream', [VideoController::class, 'stream'])->name('lectures.stream')->middleware('signed');

        Route::get('/levels/{level:slug}/exam', [ExamController::class, 'start'])->name('exam.start');
        Route::post('/levels/{level:slug}/exam', [ExamController::class, 'submit'])->name('exam.submit');
        Route::get('/exam-attempts/{attempt}', [ExamController::class, 'result'])->name('exam.result');
    });
});

// ---------- Payment webhook (CSRF-exempt in bootstrap/app.php; verified by signature) ----------
Route::post('/webhooks/moyasar', [WebhookController::class, 'moyasar'])->name('webhooks.moyasar');

// ---------- Admin ----------
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('content', [Admin\PageSectionController::class, 'index'])->name('content.index');
    Route::put('content', [Admin\PageSectionController::class, 'update'])->name('content.update');

    Route::resource('levels', Admin\LevelController::class)->except('show');
    Route::resource('lectures', Admin\LectureController::class)->except('show');
    Route::post('lectures/{lecture}/move/{dir}', [Admin\LectureController::class, 'move'])->name('lectures.move');
    Route::resource('plans', Admin\PlanController::class)->except('show');
    Route::resource('faqs', Admin\FaqController::class)->except('show');

    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');

    Route::get('subscriptions', [Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::patch('subscriptions/{subscription}/activate', [Admin\SubscriptionController::class, 'activate'])->name('subscriptions.activate');

    Route::get('referrers', [Admin\ReferrerController::class, 'index'])->name('referrers.index');
    Route::post('referrers', [Admin\ReferrerController::class, 'store'])->name('referrers.store');
    Route::put('referrers/{referrer}', [Admin\ReferrerController::class, 'update'])->name('referrers.update');
    Route::delete('referrers/{referrer}', [Admin\ReferrerController::class, 'destroy'])->name('referrers.destroy');
});

// ---------- Instructor portal (scoped to own content) ----------
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/', [Instructor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('lectures', [Instructor\LectureController::class, 'index'])->name('lectures.index');
    Route::get('lectures/create', [Instructor\LectureController::class, 'create'])->name('lectures.create');
    Route::post('lectures', [Instructor\LectureController::class, 'store'])->name('lectures.store');
    Route::get('lectures/{lecture}/edit', [Instructor\LectureController::class, 'edit'])->name('lectures.edit');
    Route::put('lectures/{lecture}', [Instructor\LectureController::class, 'update'])->name('lectures.update');
});

// ---------- Ambassador portal (invite-only doctors — no teaching) ----------
Route::middleware(['auth', 'role:ambassador'])->prefix('ambassador')->name('ambassador.')->group(function () {
    Route::get('/', [Ambassador\DashboardController::class, 'index'])->name('dashboard');
});
