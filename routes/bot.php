<?php

use App\Bot\Controllers\BotManController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
