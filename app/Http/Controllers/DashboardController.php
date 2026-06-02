<?php

namespace App\Http\Controllers;

use App\Models\ArtWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $topArtworks = collect([
            ['rank' => 1, 'title' => 'Realisme',       'sold_count' => 142, 'image' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 2, 'title' => 'Surealisme',      'sold_count' => 118, 'image' => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 3, 'title' => 'Abstrak',         'sold_count' => 97,  'image' => 'https://images.unsplash.com/photo-1582201942988-13e60e4556ee?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 4, 'title' => 'Impresionisme',   'sold_count' => 85,  'image' => 'https://images.unsplash.com/photo-1615529151169-7b1ff50dc7f2?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 5, 'title' => 'Kubisme',         'sold_count' => 73,  'image' => 'https://images.unsplash.com/photo-1612812166620-a072f77ec45b?w=500&auto=format&fit=crop&q=60'],
            ['rank' => 6, 'title' => 'Ekspresionisme',  'sold_count' => 61,  'image' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 7, 'title' => 'Naturalisme',     'sold_count' => 54,  'image' => 'https://images.unsplash.com/photo-1549490349-8643362247b5?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 8, 'title' => 'Minimalisme',     'sold_count' => 42,  'image' => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 9, 'title' => 'Pointilisme',     'sold_count' => 36,  'image' => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?q=80&w=400&auto=format&fit=crop'],
            ['rank' => 10,'title' => 'Konseptual',      'sold_count' => 29,  'image' => 'https://images.unsplash.com/photo-1579783483458-83d02161294e?q=80&w=400&auto=format&fit=crop'],
        ]);

        return view('admin.dashboard.main.transactions', compact('topArtworks'));
    }

    public function getSoldArtworks($category)
    {
        // Coba ambil dari DB dulu
        $artworks = ArtWork::where('category', $category)
            ->whereNotNull('sold_at')
            ->orderByDesc('sold_at')
            ->get()
            ->map(fn($art) => [
                'artwork'   => $art->title,
                'artist'    => $art->artist,
                'collector' => $art->collector_name,
                'price'     => '$' . number_format($art->price, 0),
                'date'      => $art->sold_at->format('d M Y'),
            ]);

        // Jika kosong, kembalikan dummy
        if ($artworks->isEmpty()) {
            $dummyNames  = ['Anya Forger', 'Loid Forger', 'Mikasa Ackerman', 'Levi Heichou', 'Erwin Smith'];
            $dummyArtists = ['Picasso Jr.', 'Van Gogh II', 'Monet Black', 'Da Vinci III', 'Frida K.'];
            $artworks = collect(range(1, 6))->map(fn($i) => [
                'artwork'   => "$category Art No.$i",
                'artist'    => $dummyArtists[array_rand($dummyArtists)],
                'collector' => $dummyNames[array_rand($dummyNames)],
                'price'     => '$' . number_format(rand(1500, 15000)),
                'date'      => now()->subDays(rand(1, 90))->format('d M Y'),
            ]);
        }

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

    public function getCustomerCountries(Request $request)
    {
        $period = $request->get('period', 'today');

        $query = ArtWork::selectRaw('
                collector_country_code as country_code,
                collector_country as country_name,
                COUNT(DISTINCT collector_name) as count
            ')
            ->whereNotNull('collector_name')
            ->whereNotNull('collector_country_code');

        // Filter berdasarkan period
        $query->when($period === 'today', fn($q) => 
            $q->whereDate('created_at', today())
        )->when($period === 'yesterday', fn($q) => 
            $q->whereDate('created_at', today()->subDay())
        )->when($period === '7days', fn($q) => 
            $q->whereBetween('created_at', [now()->subDays(7), now()])
        );

        $countries = $query->groupBy('country_code', 'country_name')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        return response()->json(['countries' => $countries]);
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
            'total_income'   => (int) 82600,
            'total_orders'   => (int) 1240,
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