<?php

namespace Luoweikingjj\ServerMonitor\Commands;

use Illuminate\Console\Command;
use Luoweikingjj\ServerMonitor\Helpers\ConsoleOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        app(ConsoleOutput::class)->setOutput($this);
        return parent::run($input, $output);
    }
}