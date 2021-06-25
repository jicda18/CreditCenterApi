<?php

use App\Http\Controllers\AccesslistController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\GuarantorController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LogTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SecurityLogController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhitelistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Authentications
Route::post('tokens', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token')->middleware('token.response');

// Myself
// Todo: set myself controller and resource
Route::get('users/me', [UserController::class, 'usersMeShow'])->name('users.me.show');

// Special
// Todo: set specials route
// companies.logo.show
// companies.ico.show

// Resource
Route::apiResource('accesslists', AccesslistController::class);
Route::apiResource('banks', BankController::class);
Route::apiResource('blacklists', BlacklistController::class);
Route::apiResource('businesses', BusinessController::class);
Route::apiResource('companies', CompanyController::class);
Route::apiResource('countries', CountryController::class);
Route::apiResource('currencies', CurrencyController::class);
Route::apiResource('debtors', DebtorController::class);
Route::apiResource('fees', FeeController::class);
Route::apiResource('frequencies', FrequencyController::class);
Route::apiResource('accesslists', AccesslistController::class);
Route::apiResource('guarantors', GuarantorController::class);
Route::apiResource('loans', LoanController::class);
Route::apiResource('logs', LogController::class);
Route::apiResource('types', LogTypeController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('resets', PasswordResetController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('provinces', ProvinceController::class);
Route::apiResource('securities', SecurityLogController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('whitelists', WhitelistController::class);

// Relations
Route::get('types/{type}/logs', [LogController::class, 'typesLogsIndex'])->name('types.logs.index');
Route::get('companies/{company}/users', [UserController::class, 'companiesUsersIndex'])->name('companies.users.index');
Route::get('companies/{company}/banks', [BankController::class, 'companiesBanksIndex'])->name('companies.banks.index');
Route::get('companies/{company}/guarantors', [GuarantorController::class, 'companiesGuarantorsIndex'])->name('companies.guarantors.index');
Route::get('companies/{company}/debtors', [DebtorController::class, 'companiesDebtorsIndex'])->name('companies.debtors.index');
Route::get('banks/{bank}/transactions', [TransactionController::class, 'banksTransactionsIndex'])->name('banks.transactions.index');
Route::get('countries/{country}/provinces', [ProvinceController::class, 'countriesProvincesIndex'])->name('countries.provinces.index');
Route::get('guarantors/{guarantor}/debtors', [DebtorController::class, 'guarantorsDebtorsIndex'])->name('guarantors.debtors.index');
Route::get('debtors/{debtor}/loans', [LoanController::class, 'debtorsLoansIndex'])->name('debtors.loans.index');
Route::get('loans/{loan}/transactions', [TransactionController::class, 'loansTransactionsIndex'])->name('loans.transactions.index');
Route::get('loans/{loan}/fees', [FeeController::class, 'loansFeesIndex'])->name('loans.fees.index');
Route::get('transactions/{transaction}/fees', [FeeController::class, 'transactionsFeesIndex'])->name('transactions.fees.index');
Route::get('users/{user}/companies', [CompanyController::class, 'usersCompaniesIndex'])->name('users.companies.index');
Route::get('users/{user}/permissions', [PermissionController::class, 'usersPermissionsIndex'])->name('users.permissions.index');
Route::get('users/{user}/transactions', [TransactionController::class, 'usersTransactionsIndex'])->name('users.transactions.index');
Route::get('users/{user}/notifications', [NotificationController::class, 'usersNotificationsIndex'])->name('users.notifications.index');
