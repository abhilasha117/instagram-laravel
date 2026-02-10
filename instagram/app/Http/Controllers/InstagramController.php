<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class InstagramController extends Controller
{
    /**
     * ===============================
     * Show profile + preload posts
     * ===============================
     */
    public function getProfile($username)
    {
        $profileResponse = Http::withHeaders([
            'x-rapidapi-host' => env('RAPIDAPI_HOST'),
            'x-rapidapi-key'  => env('RAPIDAPI_KEY'),
        ])->withBody(
            json_encode(['username' => $username]),
            'application/json'
        )->post('https://instagram120.p.rapidapi.com/api/instagram/profile');

        if (!$profileResponse->successful()) {
            abort(404, 'Profile not found');
        }

        $profile = $profileResponse->json()['result'];

        /* -------- FIRST PAGE -------- */
        $page1 = $this->fetchPosts($username);

        /* -------- SECOND PAGE -------- */
        $page2 = collect();
        $cursor = null;
        $hasNext = false;

        if ($page1['next_cursor']) {
            $page2Data = $this->fetchPosts(
                $username,
                $page1['next_cursor']
            );

            $page2 = $page2Data['posts'];
            $cursor = $page2Data['next_cursor'];
            $hasNext = $page2Data['has_next'];
        }

        /* -------- MERGE POSTS -------- */
        $posts = $page1['posts']->merge($page2);

        return view('profile', [
            'profile' => $profile,
            'posts'   => $posts,
            'cursor'  => $cursor,
            'hasNext' => $hasNext
        ]);
    }

    /**
     * ==================================
     * AJAX: Load more posts
     * ==================================
     */
    public function loadMorePosts(Request $request, $username)
    {
        $cursor = $request->query('cursor');

        if (!$cursor) {
            return response()->json([
                'posts' => [],
                'has_next' => false
            ]);
        }

        $result = $this->fetchPosts($username, $cursor);

        return response()->json([
            'posts'       => $result['posts'],
            'next_cursor' => $result['next_cursor'],
            'has_next'    => $result['has_next']
        ]);
    }

    /**
     * ==================================
     * Core: Fetch posts
     * ==================================
     */
    private function fetchPosts($username, $cursor = null)
    {
        $payload = ['username' => $username];

        if (!empty($cursor)) {
            $payload['max_id'] = $cursor;
        }

        $response = Http::withHeaders([
            'x-rapidapi-host' => env('RAPIDAPI_HOST'),
            'x-rapidapi-key'  => env('RAPIDAPI_KEY'),
            'Content-Type'    => 'application/json',
        ])->post(
            'https://instagram120.p.rapidapi.com/api/instagram/posts',
            $payload
        );

        if (!$response->successful()) {
            return [
                'posts'       => collect(),
                'next_cursor' => null,
                'has_next'    => false
            ];
        }

        $data   = $response->json();
        $result = $data['result'] ?? [];

        $posts = collect($result['edges'] ?? [])
            ->map(function ($edge) {

                $node = $edge['node'] ?? [];

                return [
                    'id' => $node['id'] ?? null,
                    'code' => $node['code'] ?? null,

                    'image' =>
                        $node['image_versions2']['candidates'][0]['url']
                        ?? $node['thumbnails'][0]['url']
                        ?? null,

                    'likes' =>
                        $node['like_count']
                        ?? $node['edge_media_preview_like']['count']
                        ?? 0,

                    'comments' =>
                        $node['comment_count']
                        ?? $node['edge_media_to_comment']['count']
                        ?? 0,

                    'caption' =>
                        $node['caption']['text']
                        ?? $node['edge_media_to_caption']['edges'][0]['node']['text']
                        ?? '',
                ];
            })
            ->filter(fn ($post) => !empty($post['image']))
            ->values();

        return [
            'posts' => $posts,

            'next_cursor' =>
                $result['page_info']['end_cursor']
                ?? $result['next_max_id']
                ?? null,

            'has_next' =>
                $result['page_info']['has_next_page']
                ?? false
        ];
    }

    /**
     * ===============================
     * View post
     * ===============================
     */
    public function viewPost($username, $code)
    {
        $result = $this->fetchPosts($username);
        $post = $result['posts']->firstWhere('code', $code);

        if (!$post) {
            abort(404, 'Post not found');
        }

        return view('post', [
            'username' => $username,
            'post' => $post
        ]);
    }

    /**
     * ===============================
     * Fake like boost
     * ===============================
     */
    public function boostLike(Request $request, $id, $username)
    {
        return response()->json([
            'likes' => rand(1000, 5000)
        ]);
    }

    /**
     * ===============================
     * Logout
     * ===============================
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
