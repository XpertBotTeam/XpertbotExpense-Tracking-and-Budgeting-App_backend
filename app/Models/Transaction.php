<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;
    protected $fillable=['user_id','expense_id','amount','type',];
    public function user()
{
    return $this->belongsTo(User::class);
}
public function expense()
{
    return $this->belongsTo(Expense::class);
}

}
