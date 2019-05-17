<?php
/**
 * Created by PhpStorm.
 * User: linjian
 * Date: 2019-04-01
 * Time: 16:08
 */

namespace Harbor\Services;


use Harbor\Http\Client;

class Service
{
    protected $client;

    protected $endpoint;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $id
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(int $id)
    {
        $uri = $this->endpoint . '/' . $id;
        return $this->request('get', $uri);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $query
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(int $page = 1, int $limit = 250, $query = array())
    {
        $query = array_merge([
            'page' => $page,
            'limit' => $limit,
        ], $query);
        return $this->request('get', $this->endpoint, ['query' => $query]);
    }

    /**
     * @param array $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function insert(array $data)
    {
        return $this->request('post', $this->endpoint,['json' => $data]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(int $id, array $data)
    {
        $uri = $this->endpoint . '/' . $id;
        return $this->request('put', $uri, ['json' => $data]);
    }

    /**
     * @param $id
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(int $id)
    {
        $uri = $this->endpoint . '/' . $id;
        return $this->request('delete', $uri);
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri, $options = [])
    {
        $response = $this->client->request($method, $uri, $options);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function parent($id)
    {
        $this->endpoint = str_replace('{parent}', $id, $this->endpoint);
        return $this;
    }
}