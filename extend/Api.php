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
     * @param array $data 返回数据{}/[]
     * @param string $msg 返回描述
     * @param array $error 返回错误信息
     */
    private static function return(int $code = 200, array $data = [], string $msg = 'success')
    {
        $result = [
            'code' => $code,
            'data' => $data,
            'msg' => $msg,
        ];
        throw new \think\exception\HttpResponseException(json($result));
    }

    /**
     * 成功 success
     * @param array $data
     * @param int $code
     * @param string $msg
     */
    public static function success($data = [], $code = 200, $msg = 'success')
    {
        return self::return($code, is_array($data) ? $data : [$data], $msg);
    }

    /**
     * 失败 FAIL
     * @param string $msg
     * @param int $code
     * @param array $data
     */
    public static function error($msg = 'FAIL', $code = 400, $data = [])
    {
        return self::return($code, $data, $msg);
    }
}