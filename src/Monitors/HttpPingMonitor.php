<?php

namespace Luoweikingjj\ServerMonitor\Monitors;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Luoweikingjj\ServerMonitor\Events\HttpPingDown;
use Luoweikingjj\ServerMonitor\Events\HttpPingUp;
use Luoweikingjj\ServerMonitor\Exceptions\InvalidConfigException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpPingMonitor
 *
 * @package Luoweikingjj\ServerMonitor\Monitors
 */
class HttpPingMonitor extends BaseMonitor {
    /**
     * @var string URL
     */
    private $url;
    /**
     * @var int 检查状态码
     */
    private $checkCode = 200;
    /**
     * @var string|null 检查短语
     */
    private $checkPhrase = null;
    /**
     * @var string 超时时长
     */
    private $timeout = 5;
    /**
     * @var bool 允许重定向
     */
    private $allowRedirects = true;
    /**
     * @var string 响应方法
     */
    private $method = 'GET';
    /**
     * @var string 响应状态码
     */
    private $responseCode;
    /**
     * @var string 响应内容
     */
    private $responseContent;
    /**
     * @var string 响应包含短语
     */
    private $responseContainsPhrase;

    /**
     * HttpPingMonitor constructor.
     *
     * @param array $config
     */
    public function __construct(array $config) {
        if (!empty($config['url'])) {
            $this->url = $config['url'];
        }
        if (!empty($config['method'])) {
            $this->method = $config['method'];
        }
        if (!empty($config['timeout'])) {
            $this->timeout = $config['timeout'];
        }
        if (!empty($config['allowRedirects'])) {
            $this->allowRedirects = $config['allowRedirects'];
        }
        if (!empty($config['checkCode'])) {
            $this->checkCode = $config['checkCode'];
        }
        if (!empty($config['checkPhrase'])) {
            $this->checkPhrase = $config['checkPhrase'];
        }
    }

    /**
     * @return Client
     */
    public function getHttpClient() {
        return new Client(
            [
                'timeout'         => $this->getTimeout(),
                'allow_redirects' => $this->isAllowRedirects(),
            ]
        );
    }

    /**
     * @throws InvalidConfigException
     */
    public function runMonitor() {
        if (empty($this->getUrl())) {
            throw new InvalidConfigException("No URL Configured.");
        }
        try {
            $guzzle = $this->getHttpClient();
            $response = $guzzle->request($this->getMethod(), $this->getUrl());
            $this->responseCode = $response->getStatusCode();
            $this->responseContent = (string)$response->getBody();
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response instanceof ResponseInterface) {
                $this->responseCode = $response->getStatusCode();
                $this->responseContent = (string)$response->getBody();
            } else {
                $this->setResponseCodeAndContentOnException($e);
            }
        } catch (GuzzleException $e) {
            $this->setResponseCodeAndContentOnException($e);
        } catch (\Exception $e) {
            $this->setResponseCodeAndContentOnException($e);
        }
        if ($this->responseCode != $this->checkCode
            || !$this->checkResponseContains($this->responseContent, $this->checkPhrase)) {
            event(new HttpPingDown($this));
        } else {
            event(new HttpPingUp($this));
        }
    }

    /**
     * @param \Exception $e
     */
    protected function setResponseCodeAndContentOnException(\Exception $e) {
        $this->responseCode = null;
        $this->responseContent = $e->getMessage().PHP_EOL.$e->getTraceAsString();
    }

    /**
     * @param $html
     * @param $phrase
     *
     * @return bool|string
     */
    protected function checkResponseContains($html, $phrase) {
        if (!isset($phrase)) {
            return true;
        }
        $this->responseContainsPhrase = Str::contains($html, $phrase);

        return $this->responseContainsPhrase;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getCheckCode() {
        return $this->checkCode;
    }

    /**
     * @param int $checkCode
     */
    public function setCheckCode($checkCode) {
        $this->checkCode = $checkCode;
    }

    /**
     * @return string|null
     */
    public function getCheckPhrase() {
        return $this->checkPhrase;
    }

    /**
     * @param string|null $checkPhrase
     */
    public function setCheckPhrase($checkPhrase) {
        $this->checkPhrase = $checkPhrase;
    }

    /**
     * @return string
     */
    public function getTimeout() {
        return $this->timeout;
    }

    /**
     * @param string $timeout
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    /**
     * @return bool
     */
    public function isAllowRedirects() {
        return $this->allowRedirects;
    }

    /**
     * @param bool $allowRedirects
     */
    public function setAllowRedirects($allowRedirects) {
        $this->allowRedirects = $allowRedirects;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getResponseCode() {
        return $this->responseCode;
    }

    /**
     * @param string $responseCode
     */
    public function setResponseCode($responseCode) {
        $this->responseCode = $responseCode;
    }

    /**
     * @return string
     */
    public function getResponseContent() {
        return $this->responseContent;
    }

    /**
     * @param string $responseContent
     */
    public function setResponseContent($responseContent) {
        $this->responseContent = $responseContent;
    }

    /**
     * @return string
     */
    public function getResponseContainsPhrase() {
        return $this->responseContainsPhrase;
    }

    /**
     * @param string $responseContainsPhrase
     */
    public function setResponseContainsPhrase($responseContainsPhrase) {
        $this->responseContainsPhrase = $responseContainsPhrase;
    }


}