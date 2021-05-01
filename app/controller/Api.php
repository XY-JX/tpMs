<?php
namespace app\controller;

use app\BaseController;

class Api extends BaseController
{

    public function index()
    {
        $orderInfo = $this::getData([
            ['goods', mt_rand(1, 3)],
            ['uid', mt_rand(1, 100)],
            ['num', mt_rand(1, 5)]
        ]);
        $redis = \utils::redis();
        $redis->lPush('order:createList', json_encode([
            'goods' => $orderInfo['goods'],
            'uid' => $orderInfo['uid'],
            'num' => $orderInfo['num']
        ]));
        for ($i = 0; $i < 500; $i++) {
            if ($result = $redis->get('order_list_' . $orderInfo['uid'] . '_' . $orderInfo['goods'])) {
                $data = json_decode($result,true);
                 \Api::success($data['data'],$data['code'],$data['msg']);
            }
            //  usleep(10000);
            usleep(7000);
        }
         \Api::success(['goods' => $orderInfo['goods']], 201, '正在排队中。。。');
    }

    public function cs()
    {
        $orderData=['goods'=>1,'uid'=>4023,'num'=>2];

        \Api::success($ecdada);
    }
}