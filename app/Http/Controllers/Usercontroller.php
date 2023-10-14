<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Usercontroller extends Controller
{
    public function showInitialAmount()
{
    $user = auth()->user();

    return response()->json(['initialamount' => $user->initialamount]);
}

}
