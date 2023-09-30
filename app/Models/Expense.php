<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['user_id', 'category_id', 'name', 'amount'];
   const TYPE_EXPENSE = 'expense';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_INCOME='income';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function transactions()
    {
    return $this->hasMany(Transaction::class);
    }

}
