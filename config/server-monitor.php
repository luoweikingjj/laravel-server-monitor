<?php
return [
    /*
     *  In this array you can specify which monitors will run.
     */
    'monitors'      => [
        'HttpPing' => Luoweikingjj\ServerMonitor\Monitors\HttpPingMonitor::class,
    ],
    'notifications' => [
        /*
         * This class will be used to send all notifications.
         */
        'handler' => Luoweikingjj\ServerMonitor\Notifications\Notifier::class,
        'senders' => [
            'log'  => Luoweikingjj\ServerMonitor\Notifications\Senders\Log::class,
            'mail' => Luoweikingjj\ServerMonitor\Notifications\Senders\Mail::class,
        ],
        /*
         * Here you can specify the ways you want to be notified when certain
         * events take place. Possible values are "log", "mail", "slack" and "pushover".
         *
         * Slack requires the installation of the maknz/slack package.
         */
        'events'  => [
            'whenHttpPingUp'   => ['log'],
            'whenHttpPingDown' => ['log', 'mail'],
        ],
        /*
         * Here you can specify how emails should be sent.
         */
        'mail'    => [
            'from' => 'your@email.com',
            'to'   => 'your@email.com',
        ],
    ]
];