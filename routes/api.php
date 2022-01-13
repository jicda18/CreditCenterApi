<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LogTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SecurityLogController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhitelistController;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Todo: define a login route

// Authentications
Route::post('tokens', [AccessTokenController::class, 'issueToken'])->name('tokens.store')->middleware('token.response');

// Myself
// Todo: set myself controller and resource
Route::get('users/me', [UserController::class, 'usersMeShow'])->name('users.me.show');

// Special
// Todo: set specials route
Route::post('users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.changePassword'); // Todo: create endpoint
Route::get('users/request-change-password', [UserController::class, 'requestChangePassword'])->name('users.requestChangePassword'); // Todo: create endpoint
Route::get('users/{user}/change-password-confirm/{token}', [UserController::class, 'changePasswordConfirm'])->name('users.changePasswordConfirm'); // Todo: create endpoint

// For Select List
Route::get('currencies/select', [CurrencyController::class, 'forSelectList']);
Route::get('debtors/select', [DebtorController::class, 'forSelectList']);
Route::get('debtors/loan/select', [DebtorController::class, 'loanForSelectList']);
Route::get('provinces/select', [ProvinceController::class, 'forSelectList']);
Route::get('frequencies/select', [FrequencyController::class, 'forSelectList']);
Route::get('banks/select', [BankController::class, 'forSelectList']);
Route::get('permissions/select', [PermissionController::class, 'forSelectList']);
Route::get('roles/select', [RolesController::class, 'forSelectList']);

// Resource
Route::apiResource('banks', BankController::class);
Route::apiResource('blacklists', BlacklistController::class);
Route::apiResource('countries', CountryController::class)->only(['index', 'show']);
Route::apiResource('currencies', CurrencyController::class);
Route::apiResource('debtors', DebtorController::class);
Route::apiResource('fees', FeeController::class)->only(['index', 'show']);
Route::apiResource('frequencies', FrequencyController::class)->only(['index', 'show']);
Route::apiResource('loans', LoanController::class)->only(['index', 'show', 'update', 'store']);
Route::apiResource('logs', LogController::class)->only(['index', 'show']);
Route::apiResource('types', LogTypeController::class)->only(['index', 'show']);
Route::apiResource('notifications', NotificationController::class)->only(['index', 'show']);
Route::apiResource('provinces', ProvinceController::class)->only(['index', 'show']);
Route::apiResource('securities', SecurityLogController::class)->only(['index', 'show']);
Route::apiResource('transactions', TransactionController::class)->only(['index', 'store', 'show']);
Route::apiResource('users', UserController::class);
Route::apiResource('permissions', PermissionController::class)->only(['index', 'show']);
Route::apiResource('roles', RolesController::class)->only(['index', 'show']);
Route::apiResource('whitelists', WhitelistController::class);

// Relations
// Route::get('types/{type}/logs', [LogController::class, 'typesLogsIndex'])->name('types.logs.index');
Route::get('banks/{bank}/transactions', [TransactionController::class, 'banksTransactionsIndex'])->name('banks.transactions.index');
Route::get('countries/{country}/provinces', [ProvinceController::class, 'countriesProvincesIndex'])->name('countries.provinces.index');
Route::get('debtors/{debtor}/loans', [LoanController::class, 'debtorsLoansIndex'])->name('debtors.loans.index');
Route::get('loans/{loan}/transactions', [TransactionController::class, 'loansTransactionsIndex'])->name('loans.transactions.index');
Route::get('loans/{loan}/fees', [FeeController::class, 'loansFeesIndex'])->name('loans.fees.index');
Route::get('transactions/{transaction}/fees', [FeeController::class, 'transactionsFeesIndex'])->name('transactions.fees.index');
Route::get('users/{user}/transactions', [TransactionController::class, 'usersTransactionsIndex'])->name('users.transactions.index');
Route::get('users/{user}/notifications', [NotificationController::class, 'usersNotificationsIndex'])->name('users.notifications.index');
Route::get('users/{user}/roles', [RolesController::class, 'usersRoleIndex'])->name('users.roles.index');
Route::get('users/{user}/permissions', [PermissionController::class, 'usersPermisionIndex'])->name('users.permissions.index');
Route::get('roles/{role}/permissions', [PermissionController::class, 'rolesPermisionIndex'])->name('roles.permissions.index');

Route::get('users/{user}/avatar', [UserController::class, 'avatar'])->name('users.avatar.show');
