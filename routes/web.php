<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\SubmissionController;
use App\Http\Controllers\Client\OnboardingController;
use App\Http\Controllers\Client\AppReviewController;
use App\Http\Controllers\Admin\AdminController;

// ── Auth ─────────────────────────────────────────────────────────────────────

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);
    if (\Illuminate\Support\Facades\Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        $request->session()->regenerate();
        return auth()->user()->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.dashboard');
    }
    return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
})->name('login.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// ── Client Portal ─────────────────────────────────────────────────────────────

Route::middleware(['auth'])->prefix('portal')->name('client.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Submit a request
    Route::get('/photos',          [SubmissionController::class, 'photosIndex'])->name('photos');
    Route::post('/photos',         [SubmissionController::class, 'photosStore'])->name('photos.store');
    Route::get('/add-page',        [SubmissionController::class, 'pagesIndex'])->name('pages');
    Route::post('/add-page',       [SubmissionController::class, 'pagesStore'])->name('pages.store');
    Route::get('/request-service', [SubmissionController::class, 'servicesIndex'])->name('services');
    Route::post('/request-service',[SubmissionController::class, 'servicesStore'])->name('services.store');
    Route::get('/tickets',         [SubmissionController::class, 'ticketsIndex'])->name('tickets');
    Route::post('/tickets',        [SubmissionController::class, 'ticketsStore'])->name('tickets.store');

    // Onboarding
    Route::get('/onboarding',                              [OnboardingController::class, 'index'])->name('onboarding');
    Route::post('/onboarding/{item}/complete',             [OnboardingController::class, 'complete'])->name('onboarding.complete');
    Route::delete('/onboarding/{item}/complete',           [OnboardingController::class, 'undo'])->name('onboarding.undo');

    // App Review
    Route::get('/app-review',                              [AppReviewController::class, 'index'])->name('app-review');
    Route::post('/app-review/{review}/feedback',           [AppReviewController::class, 'storeFeedback'])->name('app-review.feedback');
    Route::post('/app-review/{review}/approve',            [AppReviewController::class, 'approve'])->name('app-review.approve');
});

// ── Admin Panel ────────────────────────────────────────────────────────────────

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/',                                       [AdminController::class, 'dashboard'])->name('dashboard');

    // Clients
    Route::get('/clients',                                [AdminController::class, 'clients'])->name('clients');
    Route::get('/clients/create',                         [AdminController::class, 'clientCreate'])->name('clients.create');
    Route::post('/clients',                               [AdminController::class, 'clientStore'])->name('clients.store');
    Route::get('/clients/{client}/edit',                  [AdminController::class, 'clientEdit'])->name('clients.edit');
    Route::put('/clients/{client}',                       [AdminController::class, 'clientUpdate'])->name('clients.update');

    // Submissions
    Route::get('/submissions',                            [AdminController::class, 'submissions'])->name('submissions');
    Route::patch('/submissions/{type}/{id}/status',       [AdminController::class, 'updateSubmissionStatus'])->name('submissions.status');

    // Tickets
    Route::get('/tickets',                                [AdminController::class, 'tickets'])->name('tickets');
    Route::patch('/tickets/{ticket}/respond',             [AdminController::class, 'respondTicket'])->name('tickets.respond');

    // App Reviews
    Route::get('/app-reviews',                            [AdminController::class, 'appReviews'])->name('app-reviews');
    Route::get('/app-reviews/create',                     [AdminController::class, 'appReviewCreate'])->name('app-reviews.create');
    Route::post('/app-reviews',                           [AdminController::class, 'appReviewStore'])->name('app-reviews.store');
    Route::patch('/app-reviews/feedback/{feedback}',      [AdminController::class, 'updateFeedbackStatus'])->name('app-reviews.feedback.update');

    // Project stages
    Route::get('/project-stages',                         [AdminController::class, 'projectStages'])->name('project-stages');
    Route::post('/project-stages',                        [AdminController::class, 'projectStageStore'])->name('project-stages.store');

    // Onboarding items
    Route::get('/onboarding-items',                       [AdminController::class, 'onboardingItems'])->name('onboarding-items');
    Route::post('/onboarding-items',                      [AdminController::class, 'onboardingItemStore'])->name('onboarding-items.store');
    Route::delete('/onboarding-items/{item}',             [AdminController::class, 'onboardingItemDestroy'])->name('onboarding-items.destroy');
});
