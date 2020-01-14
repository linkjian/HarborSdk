<?php
/**
 * Created by PhpStorm.
 * User: linjian
 * Date: 2019-04-01
 * Time: 15:33
 */
namespace Harbor\Services;

/**
 * Class Category
 * @package Harbor\Services
 */
class Inventories extends Service
{
    protected $endpoint = 'inventories';

    public function batchUpdate(array $data)
    {
        $uri = $this->endpoint;
        $data = isset($data[$this->endpoint]) ? $data : [$this->endpoint => $data];
        return $this->request('put', $uri, ['json' => $data]);
    }
}