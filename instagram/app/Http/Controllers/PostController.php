<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function boost(Request $request, $postId, $username)
{
    $request->validate([
        'type'     => 'required|in:like,comment,share',
        'quantity' => 'required|integer|min:1',
        'amount'   => 'required|integer'
    ]);

    $pricePerUnit = 18;

    $calculatedAmount = $request->quantity * $pricePerUnit;

    if ($calculatedAmount !== (int)$request->amount) {
        return response()->json(['error'=>'Invalid amount'], 422);
    }

    // Example DB update (pseudo)
    // Post::where('id',$postId)->increment($request->type.'s', $request->quantity);

    return response()->json([
        'status'  => true,
        'type'    => ucfirst($request->type),
        'quantity'=> $request->quantity,
        'amount'  => $calculatedAmount
    ]);
}

}
