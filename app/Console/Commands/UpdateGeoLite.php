<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateGeoLite extends Command
{
    protected $signature   = 'geoip:update';
    protected $description = 'Update GeoLite2 database dari MaxMind';

    public function handle()
    {
        $licenseKey = config('services.maxmind.license_key');
        $url = "https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&license_key={$licenseKey}&suffix=tar.gz";

        $this->info('Downloading GeoLite2-City...');

        $tempFile = storage_path('app/geoip/GeoLite2-City.tar.gz');
        file_put_contents($tempFile, file_get_contents($url));

        // Extract
        $phar = new \PharData($tempFile);
        $phar->extractTo(storage_path('app/geoip/tmp'), null, true);

        // Cari file .mmdb di dalam folder hasil extract
        $files = glob(storage_path('app/geoip/tmp/GeoLite2-City_*/*.mmdb'));
        if (!empty($files)) {
            rename($files[0], storage_path('app/geoip/GeoLite2-City.mmdb'));
        }

        // Cleanup
        unlink($tempFile);
        array_map('unlink', glob(storage_path('app/geoip/tmp/GeoLite2-City_*/*.mmdb')));
        rmdir(storage_path('app/geoip/tmp'));

        $this->info('GeoLite2-City berhasil diupdate!');
    }
}