<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerViewController;

Route::get('customers', [CustomerViewController::class, 'index']); 

Route::get('customers/{id}', [CustomerViewController::class, 'showDetail'])->name('customers.showDetail');
