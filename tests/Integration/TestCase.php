<?php

namespace Luoweikingjj\ServerMonitor\Test\Integration;

use Exception;
use Illuminate\Support\Facades\Event;
use Luoweikingjj\ServerMonitor\ServerMonitorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServerMonitorServiceProvider::class,
        ];
    }

    protected function expectsEvent($eventClassName)
    {
        Event::listen($eventClassName, function ($event) use ($eventClassName) {
            $this->firedEvents[] = $eventClassName;
        });
        $this->beforeApplicationDestroyed(function () use ($eventClassName) {
            $firedEvents = isset($this->firedEvents) ? $this->firedEvents : [];
            if (!in_array($eventClassName, $firedEvents)) {
                throw new Exception("Event {$eventClassName} not fired");
            }
        });
    }
}