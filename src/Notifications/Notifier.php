<?php

namespace Luoweikingjj\ServerMonitor\Notifications;

use Exception;
use Luoweikingjj\ServerMonitor\Monitors\HttpPingMonitor;
use Psr\Log\LoggerInterface as LogContract;

class Notifier
{
    /**
     * @var LogContract
     */
    private $log;

    public function __construct(LogContract $log)
    {
        $this->log = $log;
    }
    /**
     * @param HttpPingMonitor $httpPingMonitor
     */
    public function httpPingUp(HttpPingMonitor $httpPingMonitor)
    {
        $this->sendNotification(
            'whenHttpPingUp',
            "HTTP Ping Success: {$httpPingMonitor->getUrl()}",
            "HTTP Ping Succeeded for {$httpPingMonitor->getUrl()}. Response Code {$httpPingMonitor->getResponseCode()}.",
            BaseSender::TYPE_SUCCESS
        );
    }
    /**
     * @param HttpPingMonitor $httpPingMonitor
     */
    public function httpPingDown(HttpPingMonitor $httpPingMonitor)
    {
        $additionalInfo = '';
        if ($httpPingMonitor->getCheckPhrase() && ! $httpPingMonitor->getResponseContainsPhrase()) {
            $additionalInfo = " Response did not contain \"{$httpPingMonitor->getCheckPhrase()}\"";
        }
        $this->sendNotification(
            'whenHttpPingDown',
            "HTTP Ping Failed: {$httpPingMonitor->getUrl()}!",
            "HTTP Ping Failed for {$httpPingMonitor->getUrl()}! Response Code {$httpPingMonitor->getResponseCode()}.{$additionalInfo}",
            BaseSender::TYPE_ERROR
        );
    }
    /**
     * @param string $eventName
     * @param string $subject
     * @param string $message
     * @param string $type
     */
    protected function sendNotification($eventName, $subject, $message, $type)
    {
        $senderNames = config("server-monitor.notifications.events.{$eventName}");
        $senders = config("server-monitor.notifications.senders");
        collect($senderNames)
            ->map(function ($senderName) use ($senders) {
                $className = $senders[$senderName] ?? $senderName;
                return app($className);
            })
            ->each(function (SendsNotifications $sender) use ($subject, $message, $type) {
                try {
                    $sender
                        ->setSubject($subject)
                        ->setMessage($message)
                        ->setType($type)
                        ->send();
                } catch (Exception $exception) {
                    $errorMessage = "Server Monitor notifier failed because {$exception->getMessage()}"
                                    .PHP_EOL
                                    .$exception->getTraceAsString();
                    $this->log->error($errorMessage);
                    monitorConsoleOutput()->error($errorMessage);
                }
            });
    }
}