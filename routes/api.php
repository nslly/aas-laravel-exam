<?php

use App\Http\Controllers\PositionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::apiResource('/positions', PositionController::class)->except('create', 'edit');
