<?php

namespace Luoweikingjj\ServerMonitor\Notifications;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Events\Dispatcher;
use Luoweikingjj\ServerMonitor\Events\HttpPingDown;
use Luoweikingjj\ServerMonitor\Events\HttpPingUp;

class EventHandler {
    /**
     * @var Application|mixed
     */
    protected $notifier;

    /**
     * EventHandler constructor.
     */
    public function __construct() {
        $notifierClass = config('server-monitor.notifications.handler');
        $this->notifier = app($notifierClass);
    }

    /**
     * @param HttpPingDown $event
     */
    public function whenHttpPingDown(HttpPingDown $event) {
        $this->notifier->httpPingDown($event->httpPingMonitor);
    }

    /**
     * @param HttpPingUp $event
     */
    public function whenHttpPingUp(HttpPingUp $event) {
        $this->notifier->httpPingUp($event->httpPingMonitor);
    }

    /**
     * 为订阅者注册监听器
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events) {
        $events->listen(
            HttpPingUp::class,
            static::class.'@whenHttpPingUp'
        );
        $events->listen(
            HttpPingDown::class,
            static::class.'@whenHttpPingDown'
        );
    }
}