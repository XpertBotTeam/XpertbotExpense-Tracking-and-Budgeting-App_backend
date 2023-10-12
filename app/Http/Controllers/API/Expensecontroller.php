<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Support\Carbon;
use App\Models\transaction;
class Expensecontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page=$request->get('per_page',25);
        $Expenses=Expense::paginate($per_page);
        return response()->json($Expenses);
    }
    
   /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
    $validatedData = $request->validated();
    $expense = Expense::create($validatedData);
    return response()->json($expense, 201);
    }
    public function expensesToday()
{
    $today = now()->startOfDay();
    $expenses = Expense::where('user_id', auth()->user()->id)
        ->whereDate('created_at', $today)
        ->get();

    return response()->json(['expenses_today' => $expenses]);
}
public function expensesThisWeek()
{
    $thisWeek = now()->startOfWeek();
    $expenses = Expense::where('user_id', auth()->user()->id)
        ->where('created_at', '>=', $thisWeek)
        ->get();

    return response()->json(['expenses_this_week' => $expenses]);
}
public function expensesLast30Days()
{
    $thirtyDaysAgo = now()->subDays(30);
    $expenses = Expense::where('user_id', auth()->user()->id)
        ->where('created_at', '>=', $thirtyDaysAgo)
        ->get();

    return response()->json(['expenses_last_30_days' => $expenses]);
}
public function todayDeposits()
{
    $today = Carbon::now()->startOfDay();

    // Calculate total deposits for today
    $todayDeposits = Transaction::where('created_at', '>=', $today)
        ->where('type', Transaction::TYPE_DEPOSIT)
        ->sum('amount');

    return response()->json(['todayDeposits' => $todayDeposits]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $expense=Expense::findOrFail($id);;
       return response()->json($expense);
    
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, string $id)
    {
        $expense=Expense::findOrFail($id);
        $expense->update($request->all());
        return response()->json($expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense=Expense::findOrFail($id);
        $expense->delete();
        return response()->json([
            'message' => 'Expense has been deleted successfully.'
        ],204);
        
    }
}
