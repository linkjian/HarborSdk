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
class Order extends Service
{
    protected $endpoint = 'orders';

    public function refund(int $id, array $data)
    {
        $uri = $this->endpoint . '/' . $id . '/refund';
        return $this->request('post', $uri, ['json' => $data]);
    }
}