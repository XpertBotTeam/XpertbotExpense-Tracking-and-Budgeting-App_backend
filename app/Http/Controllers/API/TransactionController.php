<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaction;
use Illuminate\Support\Carbon;
use App\Models\Expense;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page=$request->get('per_page',25);
        $Expenses=transaction::paginate($per_page);
        return response()->json($Expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function calculateTodayIncome()
{
    $today = Carbon::now()->startOfDay();

    // Calculate total income for today
    $todayIncome = transaction::where('created_at', '>=', $today)
        ->where('type', Expense::TYPE_INCOME)
        ->sum('amount');

    return $todayIncome;
}
public function calculateTodayBudget(){
$today = Carbon::now()->startOfDay();
$todayIncome = Transaction::where('created_at', '>=', $today)
    ->where('type', Expense::TYPE_INCOME)
    ->sum('amount');
$todayExpenses = Transaction::where('created_at', '>=', $today)
    ->where('type', Expense::TYPE_EXPENSE)
    ->sum('amount');
$dailyBudget = $todayIncome - $todayExpenses;

return $dailyBudget;
}
public function calculateTodayWithdrawal()
{
    $today = Carbon::now()->startOfDay();
    $todayWithdrawals = Transaction::where('created_at', '>=', $today)
        ->where('type', Expense::TYPE_WITHDRAWAL)
        ->sum('amount');

    return $todayWithdrawals;
}
    public function store(TransactionRequest $request)
    {
        $validatedData = $request->validated();
        $expense = transaction::create($validatedData);
        return response()->json($expense, 201);
    } 
    public function todayIncome()
{
    $todayIncome = $this->calculateTodayIncome();
    return response()->json(['todayIncome' => $todayIncome]);
}

public function todayBudget()
{
    $todayBudget = $this->calculateTodayBudget();
    return response()->json(['todayBudget' => $todayBudget]);
}

public function todayWithdrawal()
{
    $todayWithdrawal = $this->calculateTodayWithdrawal();
    return response()->json(['todayWithdrawal' => $todayWithdrawal]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    

        $Transaction=transaction::findOrFail($id);
        $Transaction->Update($request->all());
        return response()->json($Transaction);
}
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Transaction=transaction::findOrFail($id);
        $Transaction->delete();
        return response()->json([
            'message' => 'Expense has been deleted successfully.'
        ],204);
    }
}
