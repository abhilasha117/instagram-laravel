<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class InstagramController extends Controller
{
    public function getProfile($username)
    {
        // Replace with your RapidAPI keys
        $response = Http::withHeaders([
            'x-rapidapi-host' => env('RAPIDAPI_HOST'),
            'x-rapidapi-key' => env('RAPIDAPI_KEY'),
        ])->withBody(
            json_encode(['username' => $username]),
            'application/json'
        )->post('https://instagram120.p.rapidapi.com/api/instagram/profile');

        if ($response->status() !== 200) {
            return redirect()->route('login')->with('error', 'Failed to fetch API.');
        }

        $data = $response->json();
       
        if (!isset($data['result'])) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        $posts = $this->getPosts($username);
        return view('profile', [
            'profile' => $data['result'],
            'posts' => $posts 
        ]);
    }
    public function getPosts($username)
{
    $response = Http::withHeaders([
        'x-rapidapi-host' => env('RAPIDAPI_HOST'),
        'x-rapidapi-key'  => env('RAPIDAPI_KEY'),
        'Content-Type'    => 'application/json',
    ])->post('https://instagram120.p.rapidapi.com/api/instagram/posts', [
        'username' => $username
    ]);

    if (!$response->successful()) {
        return collect();
    }

    $data = $response->json();

    // 🔑 CORRECT PATH (THIS WAS THE BUG)
    $edges = $data['result']['edges'] ?? [];

    return collect($edges)->map(function ($edge) {
        $node = $edge['node'];

        return [
            'id'      => $node['id'],
            'code'    => $node['code'],
            'image'   =>
                $node['image_versions2']['candidates'][0]['url']
                ?? $node['thumbnails'][0]['url']
                ?? null,
            'likes'   => $node['like_count'] ?? 0,
            'caption' => $node['caption']['text'] ?? '',
            'time'    => $node['taken_at'] ?? null,
            'type'    => $node['product_type'] ?? 'photo',
        ];
    })->filter(fn ($post) => $post['image'] !== null);
}

    public function viewPost($username, $code)
    {
        $posts = $this->getPosts($username);

        $post = $posts->firstWhere('code', $code);

        if (!$post) {
            abort(404, 'Post not found');
        }

        return view('post', [
            'username' => $username,
            'post'     => $post
        ]);
    }
    public function boostLike(Request $request, $id, $username)
    {
        $increment = (int) $request->increment;
        return response()->json([
            'likes' => rand(1000, 5000) + $increment
        ]);
    }
    public function profile($username)
{
    // profile API
    $profile = Http::withHeaders([
        'x-rapidapi-host' => env('RAPIDAPI_HOST'),
        'x-rapidapi-key'  => env('RAPIDAPI_KEY'),
    ])->get(env('RAPIDAPI_URL')."/profile", [
        'username' => $username
    ])->json();

    // posts API
    $posts = Http::withHeaders([
        'x-rapidapi-host' => env('RAPIDAPI_HOST'),
        'x-rapidapi-key'  => env('RAPIDAPI_KEY'),
    ])->get(env('RAPIDAPI_URL')."/posts", [
        'username' => $username
    ])->json();

    return view('profile', [
        'profile' => $profile,
        'posts'   => $posts['data'] ?? []   // IMPORTANT
    ]);
}
public function logout(Request $request)
    {
        Auth::logout();                 // logout user
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
