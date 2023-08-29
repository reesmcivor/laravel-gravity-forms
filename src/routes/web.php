<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->any('api/gravity-forms/entries/store', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Log::debug($request->all());
        \Illuminate\Support\Facades\Artisan::call("gravity-forms --from=" . now()->toDateString());
        return 1;
});
