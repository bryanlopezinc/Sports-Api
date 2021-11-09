<?php

use Illuminate\Support\Facades\Route;
use App\Exceptions\Http\RouteNotFoundHttpException;

Route::fallback(fn () => throw new RouteNotFoundHttpException);
