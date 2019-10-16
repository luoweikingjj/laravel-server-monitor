<?php

namespace Luoweikingjj\ServerMonitor\Notifications;
/**
 * Class BaseSender
 *
 * @package Luoweikingjj\ServerMonitor\Notifications
 */
abstract class BaseSender implements SendsNotifications
{
    /**
     * 类型：success
     */
    const TYPE_SUCCESS = 'success';
    /**
     * 类型：error
     */
    const TYPE_ERROR = 'error';
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $subject;
    /**
     * @var string
     */
    protected $message;

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param $message
     *
     * @return $this|mixed
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}