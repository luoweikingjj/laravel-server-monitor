<?php

namespace Luoweikingjj\ServerMonitor\Monitors;

abstract class BaseMonitor
{
    public abstract function __construct(array $config);
    public abstract function runMonitor();
}