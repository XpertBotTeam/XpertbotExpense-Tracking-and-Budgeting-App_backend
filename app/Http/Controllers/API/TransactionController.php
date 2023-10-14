<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use App\Models\Expense;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\ExpenseRequest;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 25);
        $transactions = Transaction::paginate($per_page);
        return response()->json($transactions);
    }

    public function calculateTodayIncome()
    {
        $today = Carbon::now()->startOfDay();
        $todayIncome = Transaction::where('created_at', '>=', $today)
            ->where('type', Transaction::TYPE_INCOME)
            ->where('user_id', auth()->user()->id)
            ->sum('amount');

        return $todayIncome;
    }

    public function calculateTodayBudget()
    {
        $today = Carbon::now()->startOfDay();
        $todayIncome = Transaction::where('created_at', '>=', $today)
            ->where('type', Transaction::TYPE_INCOME)
            ->where('user_id', auth()->user()->id)
            ->sum('amount');
        $todayExpenses = Expense::where('created_at', '>=', $today)
            ->where('user_id', auth()->user()->id)
            ->sum('amount');

        $dailyBudget = $todayIncome - $todayExpenses;

        return $dailyBudget;
    }

    public function calculateTodayWithdrawal()
    {
        $today = Carbon::now()->startOfDay();
        $todayWithdrawals = Transaction::where('created_at', '>=', $today)
            ->where('type', Transaction::TYPE_WITHDRAWAL)
            ->where('user_id', auth()->user()->id)
            ->sum('amount');

        return $todayWithdrawals;
    }

    public function storeIncome(TransactionRequest $request)
   {
       $validatedData = $request->validated();
       $validatedData['type'] = Transaction::TYPE_INCOME;
       $user=auth()->user();
       $user->initialamount+=$request->amount;
       $user->save();
        $transaction = Transaction::create($validatedData);
        return response()->json($transaction, 201);
    }
    
    public function storeWithdrawal(TransactionRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['type'] = Transaction::TYPE_WITHDRAWAL;
        $amountToWithdraw = $request->amount;
        
        $user = auth()->user();
        $initialAmount = $user->initialamount; 
    
        if ($amountToWithdraw > $initialAmount) {
            return response()->json([
                'message' => 'Amount to withdraw is greater than the account balance'
            ], 400); 
        }
        $user->initialamount -= $amountToWithdraw;
        $user->save();
    
        $transaction = Transaction::create($validatedData);
        
        return response()->json($transaction, 201);
    }
    
    public function storedeposit(TransactionRequest $request){
        $validatedData=$request->validated();
        $validatedData['type'] = Transaction::TYPE_DEPOSIT;
        $transaction = Transaction::create($validatedData);
        return response()->json($transaction,201);
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
    public function todayDeposits()
{
    $today = Carbon::now()->startOfDay();

    $todayDeposits = Transaction::where('created_at', '>=', $today)
        ->where('type', Transaction::TYPE_DEPOSIT)
        ->sum('amount');

    return response()->json(['todayDeposits' => $todayDeposits]);
} 
 
    public function update(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());
        return response()->json($transaction);
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json([
            'message' => 'Transaction has been deleted successfully.'
        ], 204);
    }
}