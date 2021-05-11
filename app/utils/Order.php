<?php


namespace app\utils;

use think\facade\Db;

class Order
{
    public static function createOrderList($orderData)
    {
        if ($orderData['goods'] && $orderData['uid'] && $orderData['num']) {
//            $goods = Db::table('goods')->where('id', $orderData['goods'])->field('stock,name')->find();
//            if ($goods && $goods['stock'] < $orderData['num']) {
//                return ['code' => 410, 'data' => $orderData, 'msg' => $goods['name'] . '【库存不足】'];
//            }
            Db::startTrans();
            try {
                if(Db::table('goods')->where('id', $orderData['goods'])->where('stock','>',$orderData['num']-1)->dec('stock', $orderData['num'])->inc('sales', $orderData['num'])->update()){
                    $order = Db::table('order')->insert([
                        'uid' => $orderData['uid'],
                        'goods_id' => $orderData['goods'],
                        'num' => $orderData['num'],
                    ],'id');
                    if($order){
                        Db::commit(); // 提交事务
                        return ['code'=>200,'data'=>$order,'msg'=>'success'];
                    }
                }else{
                    Db::rollback();// 回滚事务
                    return ['code'=>410,'data'=>$orderData,'msg'=>'库存不足'];
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return ['code'=>500,'data'=>$orderData,'msg'=> $e->getMessage()];
            }
        } else {
            return ['code' => 400, 'data' => $orderData, 'msg' => '参数错误'];
        }
    }
}