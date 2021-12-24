<?php

namespace app\controller;

use app\BaseController;

class Api extends BaseController
{

    public function index()
    {
        $orderInfo = $this::getData([
            ['goods_id', mt_rand(1, 3)],
            ['uid', mt_rand(1, 100)],
            ['num', mt_rand(1, 5)]
        ]);
        $redis = \utils::redis();
        $id = md5(uniqid($orderInfo['uid'], true));
        $redis->lPush('order:createList', json_encode([
            'goods_id' => $orderInfo['goods_id'],
            'uid' => $orderInfo['uid'],
            'num' => $orderInfo['num'],
            'id' => $id
        ]));
        for ($i = 0; $i < 500; $i++) {
            if ($result = $redis->get('order:order_no_' . $id)) {
                $data = json_decode($result, true);
                \Api::success($data['data'], $data['code'], $data['msg']);
            }
            //  usleep(10000);
            usleep(10000);
        }
        \Api::success(['goods_id' => $orderInfo['goods_id']], 201, '正在排队中。。。');
    }

    public function cs1()
    {
        $orderInfo = $this->getData([
            ['goods_id', mt_rand(1, 3)],
            ['uid', mt_rand(1, 100)],
            ['num', mt_rand(1, 5)]
        ]);
        $redis = \utils::redis();
        $r = $redis->lPush('order:createList', json_encode([
            'goods_id' => $orderInfo['goods_id'],
            'uid' => $orderInfo['uid'],
            'num' => $orderInfo['num']
        ]));
        \Api::success($r);
    }

    public function cs()
    {
        //trace('111','orderList');
        $redis = \utils::redis();
        \Api::success($redis->lLen('order:createList'));

    }
}