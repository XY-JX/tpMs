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
                $data = json_decode($result, true);
                \Api::success($data['data'], $data['code'], $data['msg']);
            }
            //  usleep(10000);
            usleep(10000);
        }
        \Api::success(['goods' => $orderInfo['goods']], 201, '正在排队中。。。');
    }

    public function cs()
    {


        echo 132 % 5;







        exit();
        $redis = \utils::redis();


       // $timeout = 132;
        \Api::success('抢到锁了', 200, 132 % 5);

        $key = 'room\_lock';

        $value = 'room\_' . mt_rand(1, 9999); //分配一个随机的值针对问题3

        $isLock = $redis->set($key, $value, ['nx', 'ex' => $timeout]);//ex 秒

        if ($isLock) {

            if ($redis->get($key) == $value) { //防止提前过期，误删其它请求创建的锁

                //执行内部代码
                sleep(10);//睡眠，降低抢锁频率，缓解redis压力，针对问题2
                $redis->expire($key, $timeout);
                //    $redis->EXPIRE($key);
                //  $redis->del($key);
                \Api::success('抢到锁了', 200, $redis->ttl($key));


            } else {
                \Api::success('别人的锁', 200, $value);
            }

        } else {
            \Api::error('没有抢到锁');

            // usleep(5000); //睡眠，降低抢锁频率，缓解redis压力，针对问题2

        }


    }
}