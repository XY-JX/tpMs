<?php
namespace app\http;

use think\worker\Server;

class Worker extends Server
{
    protected $socket = 'http://0.0.0.0:23461';//必须申明
    protected  $timer;
    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {
        if($worker->id === 0){
            //定时任务
          $this->timer = \Workerman\Lib\Timer::add(1, function () {
                event('CreateOrderRedisList');
            });
        }

    }

}