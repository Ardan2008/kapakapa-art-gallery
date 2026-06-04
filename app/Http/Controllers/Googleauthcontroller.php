<?php

namespace App\Http\Controllers;

use App\Models\GoogleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth consent screen.
     * GET /auth/google
     */
    public function redirect(Request $request)
    {
        // FIX: simpan full URL termasuk fragment #artwork=ID dari query param
        $intended = $request->query('redirect', url()->previous());
        Session::put('google_redirect_after', $intended);

        $query = http_build_query([
            'client_id'     => config('services.google.client_id'),
            'redirect_uri'  => config('services.google.redirect'),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'online',
            'prompt'        => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Handle Google callback — exchange code for tokens, upsert user, set session.
     * GET /auth/google/callback
     */
    public function callback(Request $request)
    {
        $code = $request->query('code');

        if (! $code) {
            return redirect(Session::pull('google_redirect_after', '/'))
                ->with('error', 'Google login was cancelled.');
        }

        // 1. Exchange code for access token
        $tokenResponse = Http::asForm()
            ->withoutVerifying()
            ->post('https://oauth2.googleapis.com/token', [
                'code'          => $code,
                'client_id'     => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri'  => config('services.google.redirect'),
                'grant_type'    => 'authorization_code',
            ]);

        if ($tokenResponse->failed()) {
            return redirect(Session::pull('google_redirect_after', '/'))
                ->with('error', 'Failed to authenticate with Google.');
        }

        $accessToken = $tokenResponse->json('access_token');

        // 2. Fetch user info
        $userInfo = Http::withToken($accessToken)
            ->withoutVerifying()
            ->get('https://www.googleapis.com/oauth2/v3/userinfo')
            ->json();

        if (empty($userInfo['sub'])) {
            return redirect(Session::pull('google_redirect_after', '/'))
                ->with('error', 'Could not retrieve Google profile.');
        }

        // 3. Upsert GoogleUser
        $googleUser = GoogleUser::updateOrCreate(
            ['google_id' => $userInfo['sub']],
            [
                'name'   => $userInfo['name']    ?? 'Google User',
                'email'  => $userInfo['email']   ?? '',
                'avatar' => $userInfo['picture'] ?? null,
            ]
        );

        // 4. Store in session
        Session::put('google_user', [
            'id'     => $googleUser->id,
            'name'   => $googleUser->name,
            'email'  => $googleUser->email,
            'avatar' => $googleUser->avatar,
        ]);

        $redirectTo = Session::pull('google_redirect_after', '/');
        return redirect($redirectTo)->with('success', 'Welcome, ' . $googleUser->name . '!');
    }

    /**
     * Logout — clear Google session only (not admin auth).
     * POST /auth/google/logout
     */
    public function logout(Request $request)
    {
        Session::forget('google_user');

        $redirectTo  = $request->input('redirect', url()->previous());
        $artworkId   = $request->input('reopen_artwork_id');

        if ($artworkId) {
            $redirectTo = rtrim($redirectTo, '/') . '#artwork=' . $artworkId;
        }

        return redirect($redirectTo);
    }

    /**
     * Return current Google session user as JSON (for AJAX checks).
     * GET /auth/google/me
     */
    public function me()
    {
        $user = Session::get('google_user');
        if (! $user) {
            return response()->json(['authenticated' => false], 200);
        }
        return response()->json(['authenticated' => true, 'user' => $user]);
    }
}