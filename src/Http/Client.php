<?php
/**
 * Created by PhpStorm.
 * User: linjian
 * Date: 2019-04-01
 * Time: 16:55
 */

namespace Harbor\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class Client
{
    private $config;

    /**
     * @var \GuzzleHttp\Client;
     */
    private $client;

    /**
     * Client constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->initClient();
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri, $options = array())
    {
        $uri = '/api/' . $uri;
        return $this->client->request($method, $uri, $options);
    }

    /**
     * Create \GuzzleHttp\Client object
     */
    protected function initClient()
    {
        $config = [];
        if ($this->getConfig('retry', null, false)) {
            $config['handler'] = $this->createRetryHandler($this->getConfig('retry'));
        }
        $this->client = new \GuzzleHttp\Client(array_merge($config, [
            'base_uri' => $this->getConfig('domain'),
            'auth' => [$this->getConfig('api_key'), $this->getConfig('password')],
            'timeout' => 30,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]));
    }

    /**
     * Create retry middleware
     * @param $retry
     * @return HandlerStack
     */
    protected function createRetryHandler($retry) : HandlerStack
    {
        $stack = HandlerStack::create(new CurlHandler());
        $stack->push(Middleware::retry(function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) use ($retry) {
            if ($retries >= $retry) {
                return false;
            }

            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                if ($response->getStatusCode() >= 500) {
                    return true;
                }
            }
            return false;
        }, function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        }));

        return $stack;
    }

    /**
     * Get signal config value
     * @param string $key
     * @param null $default
     * @param bool $mast
     * @return |null
     */
    protected function getConfig(string $key, $default = null , $mast = true)
    {
        if (!isset($this->config[$key]) || empty($this->config[$key])) {
            if ($mast) {
                throw new \InvalidArgumentException("$key");
            }
            return $default;
        }
        return $this->config[$key];
    }
}