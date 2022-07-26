<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Home;

date_default_timezone_set("Asia/Bangkok");
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
Route::get('/', Home::class)->name('home')->middleware('auth');
Route::get('login', App\Http\Livewire\Login::class)->name('login');
// All login
Route::group(['middleware' => ['auth']], function(){    
    Route::get('profile',App\Http\Livewire\Profile::class)->name('profile');
    Route::get('back-to-admin',[App\Http\Controllers\IndexController::class,'backtoadmin'])->name('back-to-admin');
    Route::get('get-premium-receivable',[App\Http\Controllers\PremiumReceivableController::class,'data'])->name('ajax.get-premium-receivable');
    Route::get('get-reinsurance',[App\Http\Controllers\ReinsuranceController::class,'data'])->name('ajax.get-reinsurance');
    Route::get('get-voucher-payable',[App\Http\Controllers\VoucherController::class,'data'])->name('ajax.get-voucher-payable');
    Route::get('get-recovery-claim',[App\Http\Controllers\RecoveryController::class,'claim'])->name('ajax.get-recovery-claim');
    Route::get('get-recovery-refund',[App\Http\Controllers\RecoveryController::class,'refund'])->name('ajax.get-recovery-refund');
    Route::get('get-claim-payable',[App\Http\Controllers\ClaimController::class,'payable'])->name('ajax.get-claim-payable');
    Route::get('get-reinsurance-premium',[App\Http\Controllers\ReinsuranceController::class,'premium'])->name('ajax.get-reinsurance-premium');
    Route::get('get-ap-others',[App\Http\Controllers\ApController::class,'others'])->name('ajax.get-ap-others');
    Route::get('get-ap-commision',[App\Http\Controllers\ApController::class,'commision'])->name('ajax.get-ap-commision');
    Route::get('get-ap-cancelation',[App\Http\Controllers\ApController::class,'cancelation'])->name('ajax.get-ap-cancelation');
    Route::get('get-ap-refund',[App\Http\Controllers\ApController::class,'refund'])->name('ajax.get-ap-refund');
    Route::get('get-ap-handling-fee',[App\Http\Controllers\ApController::class,'handling-fee'])->name('ajax.get-ap-handling-fee');
    Route::get('polis',App\Http\Livewire\Polis\Index::class)->name('polis.index');
    Route::get('polis/insert',App\Http\Livewire\Polis\Insert::class)->name('polis.insert');
    Route::get('polis/edit/{id}',App\Http\Livewire\Polis\Edit::class)->name('polis.edit');
    Route::get('produk',App\Http\Livewire\Produk\Index::class)->name('produk.index');
    Route::get('rate',App\Http\Livewire\Rate\Index::class)->name('rate.index');
    Route::get('reasuradur',App\Http\Livewire\Reasuradur\Index::class)->name('reasuradur.index');
    Route::get('pengajuan',App\Http\Livewire\Pengajuan\Index::class)->name('pengajuan.index');
    Route::get('pengajuan/insert',App\Http\Livewire\Pengajuan\Insert::class)->name('pengajuan.insert');
    Route::get('pengajuan/edit/{data}',App\Http\Livewire\Pengajuan\Edit::class)->name('pengajuan.edit');
    Route::get('uw-limit',App\Http\Livewire\UwLimit\Index::class)->name('uw-limit.index');
    Route::get('extra-mortalita',App\Http\Livewire\ExtraMortalita\Index::class)->name('extra-mortalita.index');
    Route::get('peserta/print-em/{id}',[App\Http\Controllers\PesertaController::class,'printEm'])->name('peserta.print-em');
    Route::get('peserta/print-ek/{id}',[App\Http\Controllers\PesertaController::class,'printEk'])->name('peserta.print-ek');
    Route::get('pengajuan/print-dn/{id}',[App\Http\Controllers\PengajuanController::class,'printDN'])->name('pengajuan.print-dn');
    Route::get('pengajuan/download-report/{id}',[App\Http\Controllers\PengajuanController::class,'downloadReport'])->name('pengajuan.download-report');
});

// Administrator
Route::group(['middleware' => ['auth','access:1']], function(){    
    Route::get('setting',App\Http\Livewire\Setting::class)->name('setting');
    Route::get('users/insert',App\Http\Livewire\User\Insert::class)->name('users.insert');
    Route::get('user-access', App\Http\Livewire\UserAccess\Index::class)->name('user-access.index');
    Route::get('user-access/insert', App\Http\Livewire\UserAccess\Insert::class)->name('user-access.insert');
    Route::get('users',App\Http\Livewire\User\Index::class)->name('users.index');
    Route::get('users/edit/{id}',App\Http\Livewire\User\Edit::class)->name('users.edit');
    Route::post('users/autologin/{id}',[App\Http\Livewire\User\Index::class,'autologin'])->name('users.autologin');
    Route::get('log-activity',App\Http\Livewire\LogActivity\Index::class)->name('log-activity');
    Route::get('peserta',App\Http\Livewire\Peserta\Index::class)->name('peserta.index');
    Route::get('klaim',App\Http\Livewire\Klaim\Index::class)->name('klaim.index');
    Route::get('reas',App\Http\Livewire\Reas\Index::class)->name('reas.index');
});
