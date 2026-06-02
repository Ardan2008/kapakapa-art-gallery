<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\ArtWork;

class HomeController extends Controller
{
    public function index()
    {
        // Hero grid: 5 artwork terbaru dengan gambar valid
        $heroArtworks = ArtWork::whereNotNull('image_url')
            ->where('image_url', 'not like', '%placeholder%')
            ->where('image_url', 'not like', '%dicebear%')
            ->where('stock', '>', 0)
            ->latest()
            ->take(5)
            ->get();

        // Fetch all artists for the "Behind the Mastery" carousel
        $featured_artists = Artist::latest()->get();
        
        // Dynamic categorization for 'Seasonal Collection Featured Curations'
        // Fetch only unique styles that have at least one 'published' (stock > 0) artwork
        $featured_categories = ArtWork::select('category')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->where('stock', '>', 0) // Considering 'published' as having stock available
            ->groupBy('category')
            ->get()
            ->map(function ($group) {
                // Retrieve the latest available artwork for this specific style
                $art = ArtWork::where('category', $group->category)
                    ->where('stock', '>', 0)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if (!$art) return null;

                // Extract the first image from the JSON array or fallback to image_url
                $images = is_array($art->images) ? $art->images : json_decode($art->images, true);
                $thumbnail = (is_array($images) && count($images) > 0) 
                    ? $images[0] 
                    : ($art->image_url ?? 'https://via.placeholder.com/800');

                return [
                    'title' => $art->category,
                    'medium' => 'By ' . ($art->artist ?? 'Various Artists'),
                    'year' => $art->created_at ? $art->created_at->format('Y') : date('Y'),
                    'price' => '$' . number_format($art->price, 0),
                    'image' => $thumbnail,
                    'link' => route('gallery', ['style' => $art->category])
                ];
            })
            ->filter(); // Automatically removes nulls (styles that no longer have stock)

        return view('home', compact('featured_artists', 'featured_categories', 'heroArtworks'));
    }
}
