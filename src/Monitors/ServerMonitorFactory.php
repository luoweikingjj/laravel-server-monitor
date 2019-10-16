<?php

namespace Luoweikingjj\ServerMonitor\Monitors;

use Luoweikingjj\ServerMonitor\Exceptions\InvalidConfigException;

class ServerMonitorFactory {
    /**
     * @param string $monitorName
     * @param array  $monitorConfig
     *
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function createForMonitorConfig($monitorName, array $monitorConfig) {
        $monitors = config('server-monitor.monitors');
        $handler = $monitors[$monitorName] ?? null;
        if (is_null($handler)) {
            throw new InvalidConfigException("Could not find monitor named `{$monitorName}`.");
        }

        return new $handler($monitorConfig);
    }
}