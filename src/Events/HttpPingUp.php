<?php

namespace Luoweikingjj\ServerMonitor\Events;

use Luoweikingjj\ServerMonitor\Monitors\HttpPingMonitor;

class HttpPingUp
{
    /**
     * @var HttpPingMonitor
     */
    public $httpPingMonitor;

    /**
     * HttpPingUp constructor.
     *
     * @param HttpPingMonitor $httpPingMonitor
     */
    public function __construct(HttpPingMonitor $httpPingMonitor)
    {
        $this->httpPingMonitor = $httpPingMonitor;
    }
}