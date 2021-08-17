<?php

namespace App\Providers;

use App\Repositories\Article\ArticleDetailRepository;
use App\Repositories\Article\ArticleDetailRepositoryInterface;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\Channel\ChannelRepository;
use App\Repositories\Channel\ChannelRepositoryInterface;
use App\Repositories\Keyword\KeywordRepository;
use App\Repositories\Keyword\KeywordRepositoryInterface;
use App\Repositories\Platform\PlatformAccountRepository;
use App\Repositories\Platform\PlatformAccountRepositoryInterface;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Platform\PlatformRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(PlatformRepositoryInterface::class, PlatformRepository::class);
        $this->app->bind(PlatformAccountRepositoryInterface::class, PlatformAccountRepository::class);
        $this->app->bind(ChannelRepositoryInterface::class, ChannelRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, KeywordRepository::class);
        $this->app->bind(ArticleDetailRepositoryInterface::class, ArticleDetailRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
