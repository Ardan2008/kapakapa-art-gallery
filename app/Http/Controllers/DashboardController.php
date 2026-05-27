<?php

namespace App\Http\Controllers;

use App\Models\ArtWork;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get top selling categories (based on sold count)
        $topArtworks = ArtWork::whereNotNull('sold_at')
            ->select('category as title', \DB::raw('count(*) as sold_count'))
            ->groupBy('category')
            ->orderByDesc('sold_count')
            ->limit(10)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'title' => $item->title,
                    'sold_count' => $item->sold_count,
                    'image' => $this->getCategoryImage($item->title)
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
                    'artwork' => $art->title,
                    'artist' => $art->artist,
                    'collector' => $art->collector_name,
                    'price' => '$' . number_format($art->price, 0),
                    'date' => $art->sold_at->format('d M Y'),
                ];
            });

        return response()->json($artworks);
    }

    private function getCategoryImage($category)
    {
        $images = [
            'Realisme' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400&auto=format&fit=crop',
            'Naturalisme' => 'https://images.unsplash.com/photo-1549490349-8643362247b5?q=80&w=400&auto=format&fit=crop',
            'Impresionisme' => 'https://images.unsplash.com/photo-1615529151169-7b1ff50dc7f2?q=80&w=400&auto=format&fit=crop',
            'Ekspresionisme' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=400&auto=format&fit=crop',
            'Kubisme' => 'https://images.unsplash.com/photo-1612812166620-a072f77ec45b?w=500&auto=format&fit=crop&q=60',
            'Surealisme' => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?q=80&w=400&auto=format&fit=crop',
            'Abstrak' => 'https://images.unsplash.com/photo-1582201942988-13e60e4556ee?q=80&w=400&auto=format&fit=crop',
            'Minimalisme' => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?q=80&w=400&auto=format&fit=crop',
            'Konseptual' => 'https://images.unsplash.com/photo-1579783483458-83d02161294e?q=80&w=400&auto=format&fit=crop',
            'Pointilisme' => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?q=80&w=400&auto=format&fit=crop',
        ];

        return $images[$category] ?? 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400&auto=format&fit=crop';
    }
}
