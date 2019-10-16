<?php

namespace Luoweikingjj\ServerMonitor\Notifications\Senders;

use Luoweikingjj\ServerMonitor\Notifications\BaseSender;
use Psr\Log\LoggerInterface as LogContract;

class Log extends BaseSender {
    /**
     * @var LogContract
     */
    protected $log;

    /**
     * Log constructor.
     *
     * @param LogContract $log
     */
    public function __construct(LogContract $log) {
        $this->log = $log;
    }

    public function send() {
        $method = ($this->type === static::TYPE_SUCCESS ? 'info' : 'error');
        $this->log->$method("{$this->subject}: {$this->message}");
    }
}