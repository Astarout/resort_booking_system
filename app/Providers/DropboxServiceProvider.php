<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;
use League\Flysystem\Filesystem as Flysystem;

class DropboxServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('dropbox', function ($app, $config) {

            $client = new DropboxClient($config['authorization_token']);

            $adapter = new DropboxAdapter($client);

            $filesystem = new Flysystem($adapter);

            return new FilesystemAdapter($filesystem, $adapter, $config);
        });
    }

    public function register() {}
}
