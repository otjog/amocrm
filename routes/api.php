<?php

use App\Http\Controllers\Hooks\AmoCrmController;
use Illuminate\Support\Facades\Route;

Route::post('/hooks/amocrm/entity/create', [AmoCrmController::class, 'create']);

Route::post('/hooks/amocrm/entity/update', [AmoCrmController::class, 'update']);
