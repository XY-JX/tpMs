<?php


namespace app\utils;

use think\facade\Db;

class Order
{
    public static function createOrderList($orderData)
    {
        if ($orderData['goods_id'] && $orderData['uid'] && $orderData['num']) {
//            $goods = Db::table('goods_id')->where('id', $orderData['goods_id'])->field('stock,name')->find();
//            if ($goods && $goods['stock'] < $orderData['num']) {
//                return ['code' => 410, 'data' => $orderData, 'msg' => $goods['name'] . '【库存不足】'];
//            }
            Db::startTrans();
            try {
                if(Db::table('goods')->where('id', $orderData['goods_id'])->where('stock','>=',$orderData['num'])->dec('stock', $orderData['num'])->inc('sales', $orderData['num'])->update()){
                    $order = Db::table('order')->insert([
                        'uid' => $orderData['uid'],
                        'goods_id' => $orderData['goods_id'],
                        'num' => $orderData['num'],
                    ],'id');
                    if($order){
                        Db::commit(); // 提交事务
                        return ['code'=>200,'data'=>$order,'msg'=>'抢购成功'];
                    }
                }else{
                    Db::rollback();// 回滚事务
                    return ['code'=>410,'data'=>$orderData,'msg'=>'库存不足'];
                }
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code'=>500,'data'=>$orderData,'msg'=> $e->getMessage()];
            }
        } else {
            return ['code' => 400, 'data' => $orderData, 'msg' => '参数错误'];
        }
    }
}