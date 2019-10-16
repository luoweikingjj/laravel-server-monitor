<?php

namespace Luoweikingjj\ServerMonitor\Notifications;

interface SendsNotifications {
    /**
     * @param string $type
     *
     * @return mixed
     */
    public function setType($type);

    /**
     * @param string $subject
     *
     * @return mixed
     */
    public function setSubject($subject);

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function setMessage($message);

    public function send();
}