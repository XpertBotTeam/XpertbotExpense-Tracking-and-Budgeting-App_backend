<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class PhotoController extends Controller
{
  public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
       ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Photo upload validation failed', 'errors' => $validator->errors()], 400);
        }
    
       if ($request->hasFile('photo')) {
           $photo = $request->file('photo');
           $photoPath = $photo->store('photos', 'public');
           $user = Auth::User();
           $user->photos()->create(['path' => $photoPath]);
    
           return response()->json(['message' => 'Photo uploaded successfully']);
       }
    
   return response()->json(['message' => 'Photo upload failed'], 400);
   }

}
