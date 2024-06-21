<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EstacionamentoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\VendaController;
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
    return redirect(route('login'));
});

Route::get('login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('register', function () {
    return view('register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

Route::middleware(['auth.cookie'])->group(function () {

    Route::prefix('painel')->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::resource('/vendas', VendaController::class);
        Route::get('/vendas/{id}/edit', 'VendaController@edit')->name('vendas.edit');
        Route::put('/vendas/{id}', [VendaController::class, 'update'])->name('vendas.update');

        Route::resource('/clientes', ClienteController::class);

        Route::resource('produtos', ProdutoController::class);


        Route::post('/pembayaran/print', [PembayaranController::class, 'print'])->name('pembayaran.print');
        Route::post('/pembayaran/printTicket', [PembayaranController::class, 'printTicket'])->name('pembayaran.printTicket');

        Route::post('/vendas/search', [SearchController::class, 'search'])->name('search');

        Route::get('/vendas-pdf', [PDFController::class, 'generatePDFVendas'])->name('generatePDFVendas');
    });
});
