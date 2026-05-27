<?php

namespace App\Http\Controllers;

use App\Models\ArtWork;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtWorkController extends Controller
{
    public function create()
    {
        $categories = [
            'Realisme', 'Naturalisme', 'Impresionisme', 'Ekspresionisme', 
            'Kubisme', 'Surealisme', 'Abstrak', 'Minimalisme', 
            'Konseptual', 'Street Art / Graffiti', 'Pointilisme', 'Naif / Primitif'
        ];
        return view('admin.dashboard.create.add_product', compact('categories'));
    }

    public function edit($id)
    {
        $artist = Artist::with('artworks')->findOrFail($id);
        $categories = [
            'Realisme', 'Naturalisme', 'Impresionisme', 'Ekspresionisme', 
            'Kubisme', 'Surealisme', 'Abstrak', 'Minimalisme', 
            'Konseptual', 'Street Art / Graffiti', 'Pointilisme', 'Naif / Primitif'
        ];
        return view('admin.dashboard.edit.edit_product', compact('artist', 'categories'));
    }

    public function index()
    {
        $collections = Artist::with('artworks')->orderBy('created_at', 'desc')->get()->map(function($artist) {
            // Collect all images from all associated artworks
            $images = [];
            if ($artist->artworks) {
                foreach ($artist->artworks as $artwork) {
                    $artImages = is_array($artwork->images) ? $artwork->images : json_decode($artwork->images, true);
                    if (is_array($artImages)) {
                        foreach ($artImages as $img) {
                            $images[] = $img;
                        }
                    }
                }
            }

            // Ensure we have at least 3 images for the card layout
            while (count($images) < 3) {
                $images[] = 'https://api.dicebear.com/8.x/notionists/svg?seed=' . urlencode($artist->name) . count($images);
            }

            return [
                'id' => sprintf('%02d', $artist->id),
                'title' => $artist->name . ' Collection',
                'category' => $artist->artworks->first()->category ?? 'Art',
                'artist' => 'By ' . $artist->name,
                'images' => array_slice($images, 0, 3),
                'full_artist_name' => $artist->name,
                'birthplace' => $artist->birthplace,
                'career' => $artist->career,
                'artist_desc' => $artist->bio,
                'dimensions' => $artist->artworks->first() ? ($artist->artworks->first()->width . 'x' . $artist->artworks->first()->height . ' ' . $artist->artworks->first()->unit) : null,
            ];
        });

        $categories = [
            'Realisme', 'Naturalisme', 'Impresionisme', 'Ekspresionisme', 
            'Kubisme', 'Surealisme', 'Abstrak', 'Minimalisme', 
            'Konseptual', 'Street Art / Graffiti', 'Pointilisme', 'Naif / Primitif'
        ];

        return view('admin.dashboard.features.submit_artworks', compact('collections', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'painterName' => 'required|string|max:255',
            'birthplace' => 'required|string|max:255',
            'career' => 'required|string|max:255',
            'artistDesc' => 'required|string',
            'artName' => 'required|string|max:255',
            'artDesc' => 'required|string',
            'painterRef' => 'required|string|max:255',
            'artStyle' => 'required|string',
            'stock' => 'required|integer|min:0',
            'maxLimit' => 'required|integer|min:0',
            'basePrice' => 'required|numeric|min:0',
            'salePrice' => 'nullable|numeric|min:0',
            'profileInput' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'mediaInput.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        $images = [];

        // Handle profile image as the first image if provided
        if ($request->hasFile('profileInput')) {
            $path = $request->file('profileInput')->store('artworks', 'public');
            $images[] = Storage::url($path);
        }

        // Handle multiple media images
        if ($request->hasFile('mediaInput')) {
            foreach ($request->file('mediaInput') as $file) {
                $path = $file->store('artworks', 'public');
                $images[] = Storage::url($path);
            }
        }

        // Fill gaps if less than 3 images
        while (count($images) < 3) {
            $images[] = 'https://via.placeholder.com/500x500?text=No+Image';
        }

        $artwork = ArtWork::create([
            'title' => $validated['artName'],
            'artist' => $validated['painterName'],
            'category' => $validated['artStyle'],
            'price' => $validated['salePrice'] ?? $validated['basePrice'],
            'birthplace' => $validated['birthplace'],
            'career' => $validated['career'],
            'artist_desc' => $validated['artistDesc'],
            'art_desc' => $validated['artDesc'],
            'painter_ref' => $validated['painterRef'],
            'stock' => $validated['stock'],
            'max_limit' => $validated['maxLimit'],
            'base_price' => $validated['basePrice'],
            'sale_price' => $validated['salePrice'],
            'images' => $images,
            'image_url' => $images[0] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artwork published successfully!',
            'artwork' => [
                'id' => sprintf('%02d', $artwork->id),
                'title' => $artwork->title,
                'category' => $artwork->category,
                'artist' => 'By ' . $artwork->artist,
                'images' => $artwork->images
            ]
        ]);
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'artwork' => 'required|array',
            'artwork.*.title' => 'required|string|max:255',
            'artwork.*.desc' => 'required|string',
            'artwork.*.painterRef' => 'required|string|max:255',
            'artwork.*.style' => 'required|string',
            'artwork.*.stock' => 'required|integer|min:0',
            'artwork.*.maxLimit' => 'required|integer|min:0',
            'artwork.*.basePrice' => 'required|numeric|min:0',
            'artwork.*.salePrice' => 'nullable|numeric|min:0',
            'artwork.*.certificate' => 'nullable|file|max:20480',
            'artistData.name' => 'required|string',
            'artistData.birthplace' => 'required|string',
            'artistData.career' => 'required|string',
            'artistData.desc' => 'required|string',
        ]);

        $artistData = $request->input('artistData');
        $artworksData = $request->input('artwork');
        $createdArtworks = [];

        foreach ($artworksData as $index => $artData) {
            $images = [];
            
            // Handle multiple media images
            if ($request->hasFile("artwork.$index.images")) {
                foreach ($request->file("artwork.$index.images") as $file) {
                    $path = $file->store('artworks', 'public');
                    $images[] = Storage::url($path);
                }
            }

            // Handle Certificate
            $certificateUrl = null;
            if ($request->hasFile("artwork.$index.certificate")) {
                $path = $request->file("artwork.$index.certificate")->store('certificates', 'public');
                $certificateUrl = Storage::url($path);
            }

            // Fill gaps
            while (count($images) < 3) {
                $images[] = 'https://via.placeholder.com/500x500?text=No+Image';
            }

            $artwork = ArtWork::create([
                'title' => $artData['title'],
                'artist' => $artistData['name'],
                'category' => $artData['style'],
                'price' => $artData['salePrice'] ?? $artData['basePrice'],
                'birthplace' => $artistData['birthplace'],
                'career' => $artistData['career'],
                'artist_desc' => $artistData['desc'],
                'art_desc' => $artData['desc'],
                'painter_ref' => $artData['painterRef'],
                'stock' => $artData['stock'],
                'max_limit' => $artData['maxLimit'],
                'base_price' => $artData['basePrice'],
                'sale_price' => $artData['salePrice'],
                'images' => $images,
                'image_url' => $images[0] ?? null,
                'certificate_url' => $certificateUrl,
            ]);

            $createdArtworks[] = [
                'id' => sprintf('%02d', $artwork->id),
                'title' => $artwork->title,
                'category' => $artwork->category,
                'artist' => 'By ' . $artwork->artist,
                'images' => $artwork->images,
                'full_artist_name' => $artwork->artist,
                'birthplace' => $artwork->birthplace,
                'career' => $artwork->career,
                'artist_desc' => $artwork->artist_desc
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($createdArtworks) . ' artworks published successfully!',
            'artworks' => $createdArtworks
        ]);
    }

    public function storeCollection(Request $request)
    {
        try {
            $validated = $request->validate([
                'artist.id' => 'nullable',
                'artist.name' => 'required|string|max:255',
                'artist.birthplace' => 'required|string|max:255',
                'artist.career' => 'required|string|max:255',
                'artist.desc' => 'required|string',
                'artist.profile' => 'nullable|image|max:20480',
                'artwork.*.id' => 'nullable',
                'artwork.*.name' => 'required|string|max:255',
                'artwork.*.desc' => 'required|string',
                'artwork.*.painterRef' => 'required|string|max:255',
                'artwork.*.style' => 'required|string',
                'artwork.*.stock' => 'required|integer|min:0',
                'artwork.*.maxLimit' => 'required|integer|min:0',
                'artwork.*.basePrice' => 'nullable|numeric|min:0',
                'artwork.*.salePrice' => 'required|numeric|min:0',
                'artwork.*.width' => 'nullable|numeric|min:0',
                'artwork.*.height' => 'nullable|numeric|min:0',
                'artwork.*.unit' => 'nullable|string|max:10',
                'artwork.*.media.*' => 'nullable|image|max:20480',
                'artwork.*.certificate' => 'nullable|file|max:20480',
            ]);

            $artistData = $request->input('artist');
            $artworksData = $request->input('artwork');

            // 1. Create or Update Artist
            $artist = Artist::updateOrCreate(
                ['id' => (!empty($artistData['id'])) ? $artistData['id'] : null],
                [
                    'name' => $artistData['name'] ?? '',
                    'birthplace' => $artistData['birthplace'] ?? '',
                    'career' => $artistData['career'] ?? '',
                    'bio' => $artistData['desc'] ?? '',
                ]
            );

            if ($request->hasFile('artist.profile')) {
                $path = $request->file('artist.profile')->store('artists', 'public');
                $artist->update(['profile_url' => Storage::url($path)]);
            }

            $processedArtIds = [];

            // 2. Process Artworks
            foreach ($artworksData as $index => $artData) {
                $artwork = (!empty($artData['id'])) ? ArtWork::find($artData['id']) : new ArtWork();
                if (!$artwork) $artwork = new ArtWork();
                
                $images = $artwork->images ?? [];
                if ($request->hasFile("artwork.$index.media")) {
                    foreach ($request->file("artwork.$index.media") as $file) {
                        $path = $file->store('artworks', 'public');
                        $images[] = Storage::url($path);
                    }
                }

                // Fallback for images
                if (empty($images)) {
                    $images = ['https://via.placeholder.com/500x500?text=No+Image'];
                }

                $certificateUrl = $artwork->certificate_url;
                if ($request->hasFile("artwork.$index.certificate")) {
                    $path = $request->file("artwork.$index.certificate")->store('certificates', 'public');
                    $certificateUrl = Storage::url($path);
                }

                $artwork->fill([
                    'artist_id' => $artist->id,
                    'title' => $artData['name'] ?? 'Untitled',
                    'artist' => $artist->name,
                    'category' => $artData['style'] ?? 'Art',
                    'price' => $artData['salePrice'] ?? ($artData['basePrice'] ?? 0),
                    'birthplace' => $artist->birthplace,
                    'career' => $artist->career,
                    'artist_desc' => $artist->bio,
                    'art_desc' => $artData['desc'] ?? '',
                    'painter_ref' => $artData['painterRef'] ?? '',
                    'stock' => $artData['stock'] ?? 0,
                    'max_limit' => $artData['maxLimit'] ?? 0,
                    'base_price' => $artData['basePrice'] ?? null,
                    'sale_price' => $artData['salePrice'] ?? 0,
                    'images' => $images,
                    'image_url' => $images[0] ?? null,
                    'certificate_url' => $certificateUrl,
                    'width' => $artData['width'] ?? null,
                    'height' => $artData['height'] ?? null,
                    'unit' => $artData['unit'] ?? 'cm',
                ]);
                $artwork->save();
                $processedArtIds[] = $artwork->id;
            }

            // 3. Cleanup removed artworks (for edit mode)
            if (!empty($artistData['id'])) {
                $artist->artworks()->whereNotIn('id', $processedArtIds)->delete();
            }

            // Prepare response data for the frontend card
            $artist->load('artworks');
            $allImages = $artist->artworks->flatMap(fn($a) => is_array($a->images) ? $a->images : [])->values()->all();
            while (count($allImages) < 3) $allImages[] = 'https://via.placeholder.com/500x500?text=No+Image';

            return response()->json([
                'success' => true,
                'message' => 'Collection processed successfully!',
                'artwork' => [
                    'id' => sprintf('%02d', $artist->id),
                    'title' => $artist->name . ' Collection',
                    'category' => $artist->artworks->first()->category ?? 'Art',
                    'artist' => 'By ' . $artist->name,
                    'images' => array_slice($allImages, 0, 3),
                    'full_artist_name' => $artist->name,
                    'birthplace' => $artist->birthplace,
                    'career' => $artist->career,
                    'artist_desc' => $artist->bio
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCollection($id)
    {
        $artist = Artist::with('artworks')->findOrFail($id);
        return response()->json([
            'success' => true,
            'artist' => $artist
        ]);
    }

    public function destroyCollection($id)
    {
        $artist = Artist::findOrFail($id);
        $artist->delete(); // This will cascade to artworks
        return response()->json([
            'success' => true,
            'message' => 'Collection deleted successfully!'
        ]);
    }

    public function gallery(Request $request)
    {
        $style = $request->query('style');
        $artworks = collect(); // Initialize as empty collection
        $styleName = $style ?? 'All Collections';

        if ($style) {
            // Fetch artworks belonging to the specific style
            $artworks = ArtWork::where('category', $style)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('component.gallery.review_gallery', [
                'styleName' => $styleName,
                'artworks' => $artworks
            ]);
        }

        // Fetch unique categories (styles) that have available stock
        $styles = ArtWork::select('category')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->where('stock', '>', 0)
            ->distinct()
            ->get()
            ->map(function($group) {
                $latest_art = ArtWork::where('category', $group->category)
                    ->where('stock', '>', 0)
                    ->latest()
                    ->first();
                
                if (!$latest_art) return null;

                $artworks_count = ArtWork::where('category', $group->category)
                    ->where('stock', '>', 0)
                    ->count();
                
                $images = $latest_art ? (is_array($latest_art->images) ? $latest_art->images : json_decode($latest_art->images, true)) : [];
                
                return [
                    'title' => $group->category,
                    'author' => $latest_art->artist ?? 'Various Artists',
                    'count' => $artworks_count,
                    'main_img' => (is_array($images) && count($images) > 0) ? $images[0] : ($latest_art->image_url ?? 'https://via.placeholder.com/800'),
                    'sub_img1' => (is_array($images) && count($images) > 1) ? $images[1] : ((is_array($images) && count($images) > 0) ? $images[0] : ($latest_art->image_url ?? 'https://via.placeholder.com/400')),
                    'sub_img2' => (is_array($images) && count($images) > 2) ? $images[2] : ((is_array($images) && count($images) > 0) ? $images[0] : ($latest_art->image_url ?? 'https://via.placeholder.com/400')),
                ];
            })
            ->filter();

        return view('component.gallery.gallery', compact('styles'));
    }

    public function allArtists()
    {
        $artists = Artist::orderBy('name', 'asc')->get();
        return view('component.artists.artists', compact('artists'));
    }

    public function profile($id = null)
    {
        // If no ID, get the first artist
        $artist = $id ? Artist::with('artworks')->findOrFail($id) : Artist::with('artworks')->first();
        
        if (!$artist) {
            return redirect()->route('home');
        }

        $categories = [
            'Realisme', 'Naturalisme', 'Impresionisme', 'Ekspresionisme', 
            'Kubisme', 'Surealisme', 'Abstrak', 'Minimalisme', 
            'Konseptual', 'Street Art / Graffiti', 'Pointilisme', 'Naif / Primitif'
        ];

        return view('component.artists.profile_art', compact('artist', 'categories'));
    }
}
