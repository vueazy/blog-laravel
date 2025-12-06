<?php

use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return responseJson(
        message: 'OK!'
    );
});
