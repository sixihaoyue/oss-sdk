<?php

namespace OSS\SDK;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use OSS\SDK\Libs\Client;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            // 注册配置文件
            $this->publishes([__DIR__.'/../config' => config_path()], 'oss');
        }

    }


    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->bind(Service::class, function () {
            return new Service();
        });

        $this->app->bind(Client::class, function () {
          return new Client();
      });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Service::class];
    }

}
