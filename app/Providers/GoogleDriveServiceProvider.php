<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToReadFile;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('google-drive', function ($app) {
            return new class {
                public function copyFile($filename, $destination)
                {
                    $disk = Storage::disk('google');

                    if (!$disk->exists($filename)) {
                        throw new UnableToReadFile("File not found: $filename");
                    }

                    return $disk->copy($filename, $destination);
                }

                public function listFiles()
                {
                    return Storage::disk('google')->allFiles();
                }
            };
        });
    }

    public function boot()
    {
        //
    }
}
