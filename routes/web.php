<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
   $controller = new Waze\Controllers\Index();
   return $controller->process();
});

Route::get('/summary/', function() {
    $controller = new Waze\Controllers\Summary();
    return $controller->process();
});

Route::get('/settings/', function() {
    $controller = new Waze\Controllers\Settings();
    return $controller->process();
});

Route::get('/map/', function() {
    $controller = new Waze\Controllers\Map();
    return $controller->process();
});

Route::get('/boxer/', function() {
    $controller = new Waze\Controllers\Boxer(['flag' => 'boxer',]);
    return $controller->process();
});

Route::get('/boxer/{code}', function($code) {
    preg_match('~^(\w\w.\w\w)$~', $code, $m);
    $controller = new Waze\Controllers\Boxer(['flag' => 'boxer','code' => $m[1],]);
    return $controller->process();
})->where('code', '\w\w.\w\w');

Route::match(['get', 'post'], Waze\Config::get('git'), function() {
    echo "<pre>";
    system('cd ..; git pull 2>&1; composer update 2>&1;');
    header('HTTP/1.0 404 Not Found');
    die;
});

Route::get('/test_area/{code}', function($code) {
    preg_match('~^(\w\w.\w\w)$~', $code, $m);
    $controller = new Waze\Controllers\TestBetta(['flag' => 'area','area_code' => $m[1],]);
    return $controller->process();
})->where('code', '\w\w.\w\w');

Route::get('/test_area/{id}', function($id) {
    preg_match('~^(\d+)$~', $id, $m);
    $controller = new Waze\Controllers\Test(['flag' => 'area','area_id' => $m[1],]);
    return $controller->process();
})->where('code', '\d+');
