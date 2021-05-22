<?php
declare (strict_types=1);

namespace app\subscribe;

use think\facade\Log;

class Order
{
    /**  队列事件 */
    public function onCreateOrderRedisList()
    {
        $redis = \utils::redis();

        $o_len = $redis->lLen('order:createList');

        if ($o_len > 0) {

            $timeout = 30;

            $redisKey = 'is_order_list';

            $redisValue = 'is_order_list_' . mt_rand(1, 9999);

            $isLock = $redis->set($redisKey, $redisValue, ['nx', 'ex' => $timeout]);//ex 秒

            if($isLock){
                $i = 1;
                Log::record('订单队列开始执行数 ：' . $i . ' 订单队列开始执行时间 : ' . microtime(), 'notice');
                do {
                    if ($i % 5 == 0) if ($redis->ttl($redisKey) < 20) $redis->expire($redisKey, $timeout);//自动续期
                    $orderData = $redis->rPop('order:createList');
                    $orderData = json_decode($orderData, true);
                    $return = \app\utils\Order::createOrderList($orderData);
                    if (!$redis->get('order_list_' . $orderData['uid'] . '_' . $orderData['goods']))
                        $redis->set('order_list_' . $orderData['uid'] . '_' . $orderData['goods'], json_encode($return), 600);
                    if ($return['code'] != 200)  // 下单失败
                        Log::record('订单队列执行失败  ：' . $orderData['goods'] . ' //// 订单失败信息 : ' . json_encode($return), 'notice');
                    $o_len = $redis->lLen('order:createList');
                    $i++;
                } while ($o_len > 0);
                $redis->del($redisKey);
                Log::record('订单队列结束执行数 ：' . $i . ' //// 订单队列结束执行时间 : ' . microtime(), 'notice');
            }

        }
    }
}
