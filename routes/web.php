<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Public\Landing::class);
Route::get('/talents/{user}', \App\Livewire\Public\TalentProfile::class)->name('talents.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/ktm/{user}', [\App\Http\Controllers\KtmController::class, 'show'])->name('ktm.show');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'student') return redirect('/student');
        if ($user->role === 'umkm') return redirect('/umkm');
        if ($user->role === 'admin') return redirect('/admin');
        abort(403);
    })->name('dashboard');

    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('/', \App\Livewire\Student\Dashboard::class)->name('student.dashboard');
        Route::get('/profile', \App\Livewire\Student\Profile::class)->name('student.profile');
        Route::get('/opportunities', \App\Livewire\Student\Opportunities::class)->name('student.opportunities');
        Route::get('/project/{project}', \App\Livewire\Student\ProjectDetail::class)->name('student.project-detail');
        Route::get('/applications', \App\Livewire\Student\MyApplications::class)->name('student.my-applications');
        Route::get('/submit-work/{project}', \App\Livewire\Student\SubmitWork::class)->name('student.submit-work');
        Route::get('/wallet', \App\Livewire\Student\Wallet::class)->name('student.wallet');
    });

    Route::middleware('role:umkm')->prefix('umkm')->group(function () {
        Route::get('/', \App\Livewire\Umkm\Dashboard::class)->name('umkm.dashboard');
        Route::get('/profile', \App\Livewire\Umkm\Profile::class)->name('umkm.profile');
        Route::get('/projects', \App\Livewire\Umkm\MyProjects::class)->name('umkm.my-projects');
        Route::get('/create-project', \App\Livewire\Umkm\CreateProject::class)->name('umkm.create-project');
        Route::get('/projects/{project}/edit', \App\Livewire\Umkm\EditProject::class)->name('umkm.edit-project');
        Route::get('/manage-applicants/{project}', \App\Livewire\Umkm\ManageApplicants::class)->name('umkm.manage-applicants');
        Route::get('/review-submission/{project}', \App\Livewire\Umkm\ReviewSubmission::class)->name('umkm.review-submission');
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('/withdrawals', \App\Livewire\Admin\Withdrawals::class)->name('admin.withdrawals');
        Route::get('/users', \App\Livewire\Admin\Users::class)->name('admin.users');
        Route::get('/projects', \App\Livewire\Admin\Projects::class)->name('admin.projects');
        Route::get('/transactions', \App\Livewire\Admin\Transactions::class)->name('admin.transactions');
        Route::get('/disputes', \App\Livewire\Admin\Disputes::class)->name('admin.disputes');
    });
});
