<?php

namespace App\Http\Controllers;

use App\Models\ArtWork;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    public function index(ArtWork $artwork)
    {
        $comments = $artwork->comments()
            ->with('googleUser:id,name,avatar')
            ->orderBy('created_at', 'asc')
            ->get(['id', 'google_user_id', 'name', 'body', 'sticker_url', 'type', 'created_at'])
            ->map(function ($c) {
                return [
                    'id'          => $c->id,
                    'name'        => $c->googleUser?->name ?? $c->name,
                    'avatar'      => $c->googleUser?->avatar ?? null,
                    'body'        => $c->body,
                    'sticker_url' => $c->sticker_url,
                    'type'        => $c->type ?? 'text',
                    'created_at'  => $c->created_at,
                    // FIX: cegah false positif saat google_user_id null
                    'is_own'      => !is_null($c->google_user_id)
                                     && Session::get('google_user.id') == $c->google_user_id,
                ];
            });

        return response()->json(['comments' => $comments, 'total' => $comments->count()]);
    }

    public function store(Request $request, ArtWork $artwork)
    {
        $googleUser = Session::get('google_user');

        if (!$googleUser) {
            return response()->json([
                'error'     => 'unauthenticated',
                'message'   => 'You must sign in with Google to leave a comment.',
                'login_url' => route('auth.google') . '?redirect=' . url()->previous(),
            ], 401);
        }

        $validated = $request->validate([
            'body'        => 'nullable|string|max:280',
            'sticker_url' => 'nullable|string',
            'type'        => 'nullable|in:text,sticker,gif',
        ]);

        $type       = $validated['type'] ?? 'text';
        $body       = trim($validated['body'] ?? '');
        $stickerUrl = trim($validated['sticker_url'] ?? '');

        if ($type === 'text' && $body === '') {
            return response()->json(['error' => 'body is required for text comments'], 422);
        }

        if (in_array($type, ['sticker', 'gif']) && $stickerUrl === '') {
            return response()->json(['error' => 'sticker_url is required for sticker/gif'], 422);
        }

        $comment = $artwork->comments()->create([
            'google_user_id' => $googleUser['id'],
            'name'           => $googleUser['name'],
            'body'           => $body ?: null,
            'sticker_url'    => $stickerUrl ?: null,
            'type'           => $type,
        ]);

        return response()->json(['comment' => [
            'id'          => $comment->id,
            'name'        => $googleUser['name'],
            'avatar'      => $googleUser['avatar'],
            'body'        => $comment->body,
            'sticker_url' => $comment->sticker_url,
            'type'        => $comment->type,
            'created_at'  => $comment->created_at,
            'is_own'      => true,
        ]], 201);
    }

    public function update(Request $request, ArtWork $artwork, Comment $comment)
    {
        $googleUser = Session::get('google_user');

        // FIX: cek ownership
        if (!$googleUser || $googleUser['id'] != $comment->google_user_id) {
            return response()->json(['error' => 'unauthorized'], 403);
        }

        // FIX: cek comment benar-benar milik artwork ini
        if ($comment->artwork_id !== $artwork->id) {
            return response()->json(['error' => 'comment does not belong to this artwork'], 404);
        }

        // FIX: sticker dan gif tidak bisa diedit
        if (in_array($comment->type, ['sticker', 'gif'])) {
            return response()->json(['error' => 'sticker and gif comments cannot be edited'], 422);
        }

        $validated = $request->validate(['body' => 'required|string|max:280']);
        $comment->update(['body' => $validated['body']]);

        // FIX: tambahkan sticker_url dan type di response agar konsisten
        return response()->json(['comment' => [
            'id'          => $comment->id,
            'name'        => $googleUser['name'],
            'avatar'      => $googleUser['avatar'],
            'body'        => $comment->body,
            'sticker_url' => $comment->sticker_url,
            'type'        => $comment->type,
            'created_at'  => $comment->created_at,
            'is_own'      => true,
        ]]);
    }

    public function destroy(ArtWork $artwork, Comment $comment)
    {
        $googleUser = Session::get('google_user');

        // FIX: cek ownership
        if (!$googleUser || $googleUser['id'] != $comment->google_user_id) {
            return response()->json(['error' => 'unauthorized'], 403);
        }

        // FIX: cek comment benar-benar milik artwork ini
        if ($comment->artwork_id !== $artwork->id) {
            return response()->json(['error' => 'comment does not belong to this artwork'], 404);
        }

        $comment->delete();
        return response()->json(['deleted' => true]);
    }
}