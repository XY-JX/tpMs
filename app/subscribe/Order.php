<?php
declare (strict_types = 1);

namespace app\subscribe;

use think\facade\Log;

class Order
{
    /**  队列事件 */
    public function onCreateOrderRedisList()
    {
        $redis = \utils::redis();
        $is_orderList = $redis->get('is_order_list');
        $o_len = $redis->lLen('order:createList');
        if ($o_len > 0 && (!$is_orderList || $is_orderList != 1)) {
            $redis->set('is_order_list',1);
            $i = 1;
            Log::record('订单队列开始执行数 ：' . $i . ' 订单队列开始执行时间 : ' . microtime(), 'notice');
            do {
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
            $redis->set('is_order_list', 0);
            Log::record('订单队列结束执行数 ：' . $i . ' //// 订单队列结束执行时间 : ' . microtime(), 'notice');
        }
    }
}
