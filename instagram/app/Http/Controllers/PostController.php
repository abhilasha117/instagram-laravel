<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function boost(Request $request, $postId, $username)
    {
        $increment = (int) $request->increment;
        $amount    = (int) $request->amount;

        // Fetch current likes from DB or cache here - example uses static baseLikes for demo
        $baseLikes = 1000;
        $newLikes  = $baseLikes + $increment;

        // Save new likes count to DB here (optional)

        return response()->json([
            'likes' => $newLikes,
            'paid'  => $amount
        ]);
    }
}
