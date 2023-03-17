<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

// changin language
Route::get('language/{locale}', function ($locale) {
    session()->put('locale', $locale);

    return redirect()->back();
});
