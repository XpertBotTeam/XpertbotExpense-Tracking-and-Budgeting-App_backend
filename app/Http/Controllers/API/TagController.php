<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use PhpParser\Node\Stmt\Return_;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page=$request->get('per_page',10);
        $Tag=Tag::paginate($per_page);
        return response()->json($Tag);
    }

 

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $Tag=Tag::create($request->all());
        return response()->json($Tag);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Tag=Tag::findOrFail($id);
        return response()->json($Tag);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, string $id)
    {
        $Tag=Tag::findOrFail($id);
        $Tag->Update($request->all());
        return response()->json($Tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Tag=Tag::findOrFail($id);
        $Tag->delete();
        return response()->json([
            'message'=>'this Tag has been deleted'
        ]);
    }
}
