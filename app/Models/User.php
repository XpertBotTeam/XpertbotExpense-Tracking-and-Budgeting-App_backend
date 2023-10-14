<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//use Laravel\Passport\HasApiTokens;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;
use  Illuminate\Database\Eloquent\Model;
class User extends Authenticatable
{

    use HasApiTokens,Notifiable,HasFactory;

    protected $fillable = ['name', 'email', 'password','avatar',];
    protected $primaryKey='id';
    protected $hidden=['password'];
   
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function transactions()
{
    return $this->hasMany(Transaction::class);
}
public function photos()
{
    return $this->hasMany(Avatar::class);
}
}