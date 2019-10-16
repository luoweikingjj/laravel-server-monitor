<?php

namespace Luoweikingjj\ServerMonitor\Notifications\Senders;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Luoweikingjj\ServerMonitor\Notifications\BaseSender;

class Mail extends BaseSender {
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var mixed
     */
    private $config;

    /**
     * @param Mailer     $mailer
     * @param Repository $config
     */
    public function __construct(Mailer $mailer, Repository $config) {
        $this->config = $config->get('server-monitor.notifications.mail');
        $this->mailer = $mailer;
    }

    public function send() {
        $this->mailer->raw($this->message, function (Message $message) {
            $message
                ->subject($this->subject)
                ->from($this->config['from'])
                ->to($this->config['to']);
        });
    }
}