<?php

use Illuminate\Support\Facades\Artisan;
use Luoweikingjj\ServerMonitor\Events\HttpPingDown;
use Luoweikingjj\ServerMonitor\Test\Integration\TestCase;

class HttpPingDownTest extends TestCase {
    public function setUp() {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_when_http_is_down() {
        $this->expectsEvent(HttpPingDown::class);
        Artisan::call('monitor:httpping', [
            'url'              => 'http://somelongdomainthatdoesntexist12345asdf.com',
            '--timeout'        => 1,
            '--allowRedirects' => false,
        ]);
        $this->assertTrue(true);
    }

    /** @test */
    public function it_will_fire_an_event_when_page_not_found() {
        $this->expectsEvent(HttpPingDown::class);
        Artisan::call('monitor:httpping', [
            'url'              => 'http://www.example.com/bad/path',
            '--allowRedirects' => false,
        ]);
        $this->assertTrue(true);
    }

    /** @test */
    public function it_will_fire_an_event_when_http_is_up_and_phrase_not_found() {
        $this->expectsEvent(HttpPingDown::class);
        Artisan::call('monitor:httpping', [
            'url'           => 'http://www.example.com/',
            '--checkPhrase' => 'This will not be found! ASDF!',
        ]);
        $this->assertTrue(true);
    }
}