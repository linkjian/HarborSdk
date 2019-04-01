<?php
namespace Harbor;

use Harbor\Http\Client;

/**
 * Class Sdk
 * @package Harbor
 * @property \Harbor\Services\Product product
 */
class Sdk
{
    /**
     * @var array Config for creating clients
     */
    private $config;

    /**
     * @var array
     */
    private $services;

    private $client;

    /**
     * Sdk constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->loadServices();
        $this->createClient();
    }

    /**
     * @param $name
     */
   public function __get($name)
    {
        if (!array_key_exists($name, $this->services)) {
            throw new \InvalidArgumentException("service $name not found");
        }
        if (!$this->services[$name]['object']) {
            $this->createService($name);
        }
        return $this->services[$name]['object'];
    }

    protected function createClient()
    {
        $this->client = new Client($this->config);
    }

    protected function createService($name)
    {
        $service = $this->services[$name];
        $this->services[$name]['object'] = new $service['class']($this->client);
    }

    protected function loadServices()
    {
        $services = array_filter(scandir(__DIR__ . '/Services'), function ($file) {
            return !in_array($file, ['.','..']);
        });

        foreach ($services as $service) {
            $service = explode('.', $service);
            $name = array_shift($service);
            $class = "Harbor\\Services\\$name";
            if (!class_exists($class)) {
                continue;
            }
            $this->services[strtolower($name)]['class'] = $class;
            $this->services[strtolower($name)]['object'] = null;
        }
    }
}