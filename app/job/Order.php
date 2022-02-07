<?php

namespace app\job;

use think\queue\Job;

class Order
{
    public function task1(Job $job, $data)
    {

        $orderData = json_decode($data, true);
        $return = \app\utils\Order::createOrderList($orderData);
        $redisconfig = config('cache.stores.redis');
        $redisconfig['persistent_id'] = 'job_order_task1';
      //  print_r($redisconfig);exit();
        $redis = \xy_jx\Utils\Sundry::redis($redisconfig);


      //  $redis = \utils::redis();
        $redis->set('order:order_no_' . $return['id'], json_encode($return), 300);
        if ($return['code'] != 200) {// 下单失败
            trace('订单队列执行失败 :' . $orderData['goods_id'] . ' //// 订单失败信息 : ' . json_encode($return), 'orderListFail');
        }
        $job->delete();
    }

    public function failed($data)
    {
        echo '失败太多了';

    }

}