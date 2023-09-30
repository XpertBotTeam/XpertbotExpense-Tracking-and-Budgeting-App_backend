<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\TransactionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::resource('tag',TagController::class);
Route::resource('Category',CategoryController::class);
Route::resource('Expenses',Expensecontroller::class);
Route::get('today/income', [TransactionController::class, 'todayIncome']);
Route::get('today/budget', [TransactionController::class, 'todayBudget']);
Route::get('today/withdrawal', [TransactionController::class, 'todayWithdrawal']);
Route::get('expenses/last-30-days', [ExpenseController::class, 'getExpensesLast30Days']);
Route::get('expenses/this-week', [ExpenseController::class, 'getExpensesofthisweek']);
Route::get('expenses/today', [ExpenseController::class, 'todayExpenses']);
Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
