<?php

namespace App\Http\Controllers;

use App\Models\ArtWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $topArtworks = ArtWork::whereNotNull('sold_at')
            ->select('category as title', DB::raw('count(*) as sold_count'))
            ->groupBy('category')
            ->orderByDesc('sold_count')
            ->limit(10)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank'       => $index + 1,
                    'title'      => $item->title,
                    'sold_count' => $item->sold_count,
                    'image'      => $this->getCategoryImage($item->title),
                ];
            });

        return view('admin.dashboard.main.transactions', compact('topArtworks'));
    }

    public function getSoldArtworks($category)
    {
        $artworks = ArtWork::where('category', $category)
            ->whereNotNull('sold_at')
            ->orderByDesc('sold_at')
            ->get()
            ->map(function ($art) {
                return [
                    'artwork'   => $art->title,
                    'artist'    => $art->artist,
                    'collector' => $art->collector_name,
                    'price'     => '$' . number_format($art->price, 0),
                    'date'      => $art->sold_at->format('d M Y'),
                ];
            });

        return response()->json($artworks);
    }

    /**
     * API: Monthly visitor stats per year.
     * GET /api/dashboard/visitors?year=2026
     */
    public function getVisitorStats(Request $request)
    {
        $year = (int) $request->get('year', date('Y'));

        // Hitung pengunjung unik (collector_name) per bulan
        $data = ArtWork::selectRaw('MONTH(created_at) as month, COUNT(DISTINCT collector_name) as visitors')
            ->whereYear('created_at', $year)
            ->whereNotNull('collector_name')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Pastikan semua 12 bulan ada
        $monthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthly[] = $data->has($m) ? (int) $data[$m]->visitors : 0;
        }

        return response()->json([
            'year'    => $year,
            'monthly' => $monthly,
            'total'   => array_sum($monthly),
            'online'  => $this->countOnlineSessions(),
        ]);
    }

    /**
     * API: Live online visitor count (polling).
     * GET /api/dashboard/visitors/online
     */
    public function getOnlineVisitors()
    {
        return response()->json([
            'online' => $this->countOnlineSessions(),
        ]);
    }

    /**
     * API: Daftar tahun yang tersedia di database.
     * GET /api/dashboard/visitors/years
     */
    public function getAvailableYears()
    {
        $years = ArtWork::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('collector_name')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        // Pastikan tahun sekarang selalu ada meski belum ada data
        $currentYear = (int) date('Y');
        if (!$years->contains($currentYear)) {
            $years->prepend($currentYear);
        }

        return response()->json(['years' => $years->values()]);
    }

    /**
     * API: Summary stats untuk cards (income, orders, visitors).
     * GET /api/dashboard/stats
     */
    public function getStats()
    {
        $totalIncome = ArtWork::whereNotNull('sold_at')->sum('price');

        $totalOrders = ArtWork::whereNotNull('sold_at')->count();

        $totalVisitors = ArtWork::whereNotNull('collector_name')
            ->distinct('collector_name')
            ->count('collector_name');

        return response()->json([
            'total_income'   => (int) $totalIncome,
            'total_orders'   => (int) $totalOrders,
            'total_visitors' => (int) $totalVisitors,
        ]);
    }

    /**
     * Hitung sesi aktif dari cache/session.
     * Sesuaikan dengan mekanisme session yang digunakan.
     */
    private function countOnlineSessions(): int
    {
        // Contoh dengan database session driver:
        // return DB::table('sessions')
        //     ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
        //     ->count();

        // Placeholder — ganti dengan logika di atas jika session driver = database
        return rand(80, 350);
    }

    private function getCategoryImage($category): string
    {
        $images = [
            'Realisme'      => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400&auto=format&fit=crop',
            'Naturalisme'   => 'https://images.unsplash.com/photo-1549490349-8643362247b5?q=80&w=400&auto=format&fit=crop',
            'Impresionisme' => 'https://images.unsplash.com/photo-1615529151169-7b1ff50dc7f2?q=80&w=400&auto=format&fit=crop',
            'Ekspresionisme'=> 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=400&auto=format&fit=crop',
            'Kubisme'       => 'https://images.unsplash.com/photo-1612812166620-a072f77ec45b?w=500&auto=format&fit=crop&q=60',
            'Surealisme'    => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?q=80&w=400&auto=format&fit=crop',
            'Abstrak'       => 'https://images.unsplash.com/photo-1582201942988-13e60e4556ee?q=80&w=400&auto=format&fit=crop',
            'Minimalisme'   => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?q=80&w=400&auto=format&fit=crop',
            'Konseptual'    => 'https://images.unsplash.com/photo-1579783483458-83d02161294e?q=80&w=400&auto=format&fit=crop',
            'Pointilisme'   => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?q=80&w=400&auto=format&fit=crop',
        ];

        return $images[$category]
            ?? 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400&auto=format&fit=crop';
    }
}