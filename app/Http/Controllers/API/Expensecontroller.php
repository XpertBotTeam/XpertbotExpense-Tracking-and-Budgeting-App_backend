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
    public function getExpensesLast30Days()
    {
        $thirtyDaysAgo = now()->subDays(30);
        $totalExpenses = Expense::where('user_id', auth()->user()->id)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount');
    
        return response()->json(['total_expenses_last_30_days' => $totalExpenses]);
    }
    public function getExpensesofthisweek(){
        $thisweek=now()->subDays(7);
        $totalExpenses=Expense::where('user_id',auth()->user()->id)
        ->where('created_at','>=',$thisweek)
        ->sum('amount');
        return response()->json(['total_expenses_this_week'=>$totalExpenses]);
    }
    public function todayExpenses()
{
    $today = now()->startOfDay();
    $expenses = Expense::where('user_id', auth()->user()->id)
        ->whereDate('created_at', $today)
        ->sum('amount');

    return response()->json([
        'todayExpenses' => $expenses
    ]);
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
