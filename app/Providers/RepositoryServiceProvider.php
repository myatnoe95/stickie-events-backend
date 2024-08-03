<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserInterface;
use App\Repositories\UserRepository;
use App\Interfaces\EventInterface;
use App\Repositories\EventRepository;
use App\Interfaces\PhotoInterface;
use App\Repositories\PhotoRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
		$this->app->bind(UserInterface::class, UserRepository::class);
		$this->app->bind(EventInterface::class, EventRepository::class);
		$this->app->bind(PhotoInterface::class, PhotoRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}

