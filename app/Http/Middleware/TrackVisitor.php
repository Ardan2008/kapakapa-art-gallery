<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\VisitorLog;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        try {
            $ip = $request->ip();

            // ── LOCAL: ganti dengan IP publik untuk testing ──
            if (app()->environment('local')) {
                if ($ip === '127.0.0.1' || $ip === '::1') {
                    $ip = '202.125.83.14'; 
                }
            }

            // ── PRODUCTION: skip kalau IP private/lokal ──
            if (app()->environment('production')) {
                if ($ip === '127.0.0.1' || $ip === '::1' || $this->isPrivateIp($ip)) {
                    return $next($request);
                }
            }

            $sessionKey = 'visitor_tracked_' . date('Ymd');
            if (session()->has($sessionKey)) {
                return $next($request);
            }

            $position = Location::get($ip);

            // MaxMind kadang return object tapi countryCode kosong
            $countryName = ($position && $position->countryName) ? $position->countryName : 'Unknown';
            $countryCode = ($position && $position->countryCode) ? strtolower($position->countryCode) : 'un';
            $city        = ($position && $position->cityName)    ? $position->cityName : null;

            VisitorLog::create([
                'ip_address'   => $ip,
                'country_name' => $countryName,
                'country_code' => $countryCode,
                'city'         => $city,
                'url'          => $request->url(),
                'user_agent'   => $request->userAgent(),
            ]);

            session([$sessionKey => true]);

        } catch (\Exception $e) {
            logger('[TrackVisitor] ' . $e->getMessage());
        }

        return $next($request);
    }

    private function shouldSkip(Request $request): bool
    {
        if ($request->is('api/*')) return true;
        if (preg_match('/\.(css|js|png|jpg|ico|svg|woff|ttf)$/i', $request->path())) return true;
        if ($request->is('livewire/*')) return true;
        return false;
    }

    // Cek apakah IP adalah IP private/internal
    private function isPrivateIp(string $ip): bool
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, 
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}