<?php

namespace Luoweikingjj\ServerMonitor;

use Illuminate\Support\ServiceProvider;
use Luoweikingjj\ServerMonitor\Commands\HttpPingCommand;
use Luoweikingjj\ServerMonitor\Helpers\ConsoleOutput;
use Luoweikingjj\ServerMonitor\Notifications\EventHandler;

class ServerMonitorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/server-monitor.php' => config_path('server-monitor.php'),
        ], 'config');
    }
    /**
     * Register the application services.
     */
    public function register()
    {
        // 合并配置
        $this->mergeConfigFrom(__DIR__.'/../config/server-monitor.php', 'server-monitor');
        // 注册事件订阅者
        $this->app['events']->subscribe(EventHandler::class);
        // 容器绑定
        $this->app->bind('command.monitor:httpping', HttpPingCommand::class);
        // 注册命令
        $this->commands([
            'command.monitor:httpping',
        ]);
        // 容器绑定
        $this->app->singleton(ConsoleOutput::class);
    }
}