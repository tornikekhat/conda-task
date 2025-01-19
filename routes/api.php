<?php

use App\Http\Controllers\Api\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('/contacts', [ContactController::class, 'store'])->name('contact.store');;
