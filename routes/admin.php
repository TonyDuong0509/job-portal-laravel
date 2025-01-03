<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\IndustryTypeController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\OrganizationTypeController;
use App\Http\Controllers\Admin\StateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProfessionController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\CityController;

Route::group(
    [
        'middleware' => ['guest:admin'],
        'prefix' => 'admin',
        'as' => 'admin.',
    ],
    function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    }
);

Route::group(
    [
        'middleware' => ['auth:admin'],
        'prefix' => 'admin',
        'as' => 'admin.',
    ],
    function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // Dashboard Route
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Industry Route
        Route::resource('industry-types', IndustryTypeController::class);

        // Organization Route
        Route::resource('organization-types', OrganizationTypeController::class);

        // Country Route
        Route::resource('countries', CountryController::class);

        // State Route
        Route::resource('states', StateController::class);

        // City Route
        Route::resource('cities', CityController::class);
        Route::get('get-states/{country_id}', [LocationController::class, 'getStatesOfCountry'])->name('get-states');

        // Language Route
        Route::resource('languages', LanguageController::class);

        // Profession Route
        Route::resource('professions', ProfessionController::class);

        // Skill Route
        Route::resource('skills', SkillController::class);

        // Plan Route
        Route::resource('plans', PlanController::class);
    }
);
