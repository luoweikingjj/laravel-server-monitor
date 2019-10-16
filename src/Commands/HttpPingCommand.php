<?php

namespace Luoweikingjj\ServerMonitor\Commands;

use Luoweikingjj\ServerMonitor\Monitors\ServerMonitorFactory;

class HttpPingCommand extends BaseCommand {
    /**
     * @var string
     */
    protected $signature = 'monitor:httpping
                            {url : URL}
                            {--method=GET : 请求方法}
                            {--timeout=3 : 超时时间}
                            {--allowRedirects=true : 允许重定向}
                            {--checkCode=200 : 检查状态码}
                            {--checkPhrase= : 检查短语}';
    /**
     * @var string
     */
    protected $description = 'Run Http-Ping server monitor tasks.';

    /**
     * @throws \Luoweikingjj\ServerMonitor\Exceptions\InvalidConfigException
     */
    public function handle() {
        $config = [
            'url'            => $this->argument("url"),
            'method'         => $this->option("method"),
            'timeout'        => $this->option("timeout"),
            'allowRedirects' => $this->option("allowRedirects"),
            'checkCode'      => $this->option("checkCode"),
            'checkPhrase'    => $this->option("checkPhrase"),
        ];
        $monitor = ServerMonitorFactory::createForMonitorConfig('HttpPing', $config);
        $monitor->runMonitor();
    }
}