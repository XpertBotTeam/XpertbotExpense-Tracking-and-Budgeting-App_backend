<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//use Laravel\Passport\HasApiTokens;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens,Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $primaryKey='id';
    protected $hidden=['password'];
   
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}