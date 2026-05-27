<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Realisme', 'Naturalisme', 'Impresionisme', 'Ekspresionisme', 'Kubisme', 'Surealisme', 'Abstrak', 'Minimalisme', 'Konseptual', 'Pointilisme'];
        $artists = ['Leonardo Da Vinci', 'Vincent van Gogh', 'Edvard Munch', 'Sandro Botticelli', 'Salvador Dalí', 'Johannes Vermeer', 'Rembrandt van Rijn', 'Gustav Klimt', 'Diego Velázquez', 'Grant Wood'];
        $collectors = ['Clark Kent', 'Bruce Wayne', 'Diana Prince', 'Barry Allen', 'Hal Jordan', 'Arthur Curry', 'Victor Stone', 'Selina Kyle', 'Oliver Queen', 'Billy Batson'];

        foreach ($categories as $cat) {
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\ArtWork::create([
                    'title' => $cat . ' Painting #' . $i,
                    'artist' => $artists[array_rand($artists)],
                    'category' => $cat,
                    'price' => rand(1000, 5000),
                    'collector_name' => $collectors[array_rand($collectors)],
                    'sold_at' => now()->subDays(rand(1, 30)),
                    'image_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400',
                ]);
            }
        }
    }
}
