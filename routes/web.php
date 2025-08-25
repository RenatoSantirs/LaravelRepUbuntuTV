<?php

use App\Http\Controllers\ArticuloController;
use App\Http\Livewire\ArticuloCategorias;
use App\Http\Livewire\Ayuda;
use App\Http\Livewire\Categorias;
use App\Http\Livewire\Contenido1;
use App\Http\Livewire\Contenido2;
use App\Http\Livewire\Contenido3;
use App\Http\Livewire\Estadisticas;
use App\Http\Livewire\EstadisticasPrediccion;
use App\Http\Livewire\Hombre;
use App\Http\Livewire\Inicio;
use App\Http\Livewire\Mujer;
use App\Models\Articulo;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Livewire\ArticuloVistaPrev;
use App\Http\Livewire\CrearAdminUser;
use App\Http\Livewire\CrearCategoriaYMarca;
use App\Http\Livewire\CrearRolUser;
use App\Http\Livewire\Venta;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', Categorias::class)->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/inicio', Inicio::class)
        ->name('inicio');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/contenido1', Contenido1::class)
        ->name('contenido1');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/contenido2', Contenido2::class)
        ->name('contenido2');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/contenido3', Contenido3::class)
        ->name('contenido3');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/hombre', Hombre::class)
        ->name('hombre');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/mujer', Mujer::class)
        ->name('mujer');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/estadisticas', Estadisticas::class)
        ->name('estadisticas');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/estadisticas-prediccion', EstadisticasPrediccion::class)
        ->name('estadisticas-prediccion');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/categorias', Categorias::class)
        ->name('categorias');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/articulovistaprev', ArticuloVistaPrev::class)
        ->name('articulovistaprev');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/crearadmuser', CrearAdminUser::class)
        ->name('crearadmuser');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/crearuserrol', CrearRolUser::class)
        ->name('crearuserrol');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/crearcategoriaymarca', CrearCategoriaYMarca::class)
        ->name('crearcategoriaymarca');
});


Route::get('/venta', Venta::class)
    ->name('venta');

Route::get('/articulo-categorias/{categoria}', ArticuloCategorias::class)
    ->name('articulo-categorias');

Route::get('articulos/{categoria}/{nombre}/{id_articulo}', [ArticuloController::class, 'render'])    
    ->name('articulo.render');


Route::post('/create-preference', [MercadoPagoController::class, 'createPaymentPreference']);

Route::get('/mercadopago/success', [MercadoPagoController::class, 'paymentSuccess'])->name('mercadopago.success');
Route::get('/mercadopago/failed', [MercadoPagoController::class, 'paymentFailed'])->name('mercadopago.failed');
