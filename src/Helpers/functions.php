<?php

use Luoweikingjj\ServerMonitor\Helpers\ConsoleOutput;

/**
 * @return ConsoleOutput
 */
function monitorConsoleOutput()
{
    return app(ConsoleOutput::class);
}