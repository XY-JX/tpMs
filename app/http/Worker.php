<?php
namespace app\http;

use think\worker\Server;
use Workerman\Lib\Timer;

class Worker extends Server
{

    protected $timer;
    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {
        if($worker->id === 0){
            //定时任务
            \utils::redis()->set('is_order_list',0); // 订单队列标识
            Timer::add(1, function () {
                event('CreateOrderRedisList');
            });
        }

    }

}