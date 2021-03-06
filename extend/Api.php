<?php

/**
 * code 可根据业务自行设置
 * 200成功，400失败，401数据验证错误，500 致命错误  （已内置状态码）
 * Class Api
 */
class Api
{
    /**
     * @param int $code 业务状态码
     * @param mixed $data 返回数据{}/[]
     * @param string $msg 返回描述
     */
    private static function return(int $code = 200,  $data = [], string $msg = 'success')
    {
        throw new \think\exception\HttpResponseException(json([
            'code' => $code,
            'data' => $data,
            'msg' => $msg,
        ]));
    }

    /**
     * 成功 success
     * @param mixed $data
     * @param int $code
     * @param string $msg
     */
    public static function success($data = [],int $code = 200, string $msg = 'success')
    {
        return self::return($code,  $data, $msg);
    }

    /**
     * 失败 FAIL
     * @param string $msg
     * @param int $code
     * @param mixed $data
     */
    public static function error(string $msg = 'FAIL',int $code = 400, $data = [])
    {
        return self::return($code, $data, $msg);
    }
}