<?php

namespace Luoweikingjj\ServerMonitor\Events;

use Luoweikingjj\ServerMonitor\Monitors\HttpPingMonitor;

class HttpPingDown {
    /**
     * @var HttpPingMonitor
     */
    public $httpPingMonitor;

    /**
     * HttpPingDown constructor.
     *
     * @param HttpPingMonitor $httpPingMonitor
     */
    public function __construct(HttpPingMonitor $httpPingMonitor) {
        $this->httpPingMonitor = $httpPingMonitor;
    }
}