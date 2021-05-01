<?php

class utils
{

    public static function randomCode($limit = 4)
    {
        return strtoupper(substr(md5(uniqid()), 8, $limit));
    }

    /**
     * @param array $id 必须是一个一维数组
     * @return array
     */
    public static function orderCode(array $id)
    {
        $randomCodeId = self::randomCode(12);
        $code = self::randomCode();
        cache('order_' . $randomCodeId, ['type' => 1, 'id' => $id, 'time' => time(), 'code' => $code], 3600 * 24 * 8);
        return ['url' => '/home/' . $randomCodeId, 'code' => $code];
    }


    /**
     * 根据经纬度返回距离
     * @param $lng1 经度
     * @param $lat1 纬度
     * @param $lng2 经度
     * @param $lat2 纬度
     * @return float 距离：m
     */
    public static function getDistance($lng1, $lat1, $lng2, $lat2)
    {
        $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6370996;
        return round($s, 0);
    }


    /**
     *  根据经纬度返回距离
     * @param $lng1 经度
     * @param $lat1 纬度
     * @param $lng2 经度
     * @param $lat2 纬度
     * @return float 距离：km <=50km
     */
    public static function Distance($lng1, $lat1, $lng2, $lat2)
    {
        $m = getDistance($lng1, $lat1, $lng2, $lat2);
        if ($m > 1000) {
            if ($m > 50000) {
                return '';
            } else {
                return round(($m / 1000), 1) . 'km';
            }
        } else {
            return $m . 'm';
        }
    }


    /**
     * 微信转账到零钱
     * @param string $url
     * @param array $postfields
     * @param int $type
     * @return mixed
     */
    public static function postData(string $url, array $postfields, $type = 1)
    {
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $postfields;
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        if ($type == 1) { //以下是证书相关代码
            $params[CURLOPT_SSLCERTTYPE] = 'PEM';
            $params[CURLOPT_SSLCERT] = getcwd() . '/Public/weixinpay/apiclient_cert.pem';//绝对路径
            $params[CURLOPT_SSLKEYTYPE] = 'PEM';
            $params[CURLOPT_SSLKEY] = getcwd() . '/Public/weixinpay/apiclient_key.pem';//绝对路径
        }
        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
        curl_close($ch); //关闭连接
        return $content;
    }

    /**
     * array转XML
     * @param $arr
     * @return string
     */
    public static function ArrToXml($arr)
    {
        if (!is_array($arr) || count($arr) == 0) return '';
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将XML转为array
     * @param $xml
     * @return mixed
     */
    public static function XmlToArr($xml)
    {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    public static function ws($data, $roomid = 0, $clientid = 0)
    {
        vendor("websocket-php.lib.Base");
        vendor("websocket-php.lib.ConnectionException");
        vendor("websocket-php.lib.Exception");
        vendor("websocket-php.lib.Client");
        $send = array('type' => 'push',
            'roomid' => $roomid,
            'clientid' => $clientid,
            'msg' => json_encode($data)
        );
        $url = "ws://47.92.106.81:8282"; //服务地址
        $client = new \WebSocket\Client($url); //实例化
        $client->send(json_encode($send)); //发送数据
        //$result=$client->receive(); //接收数据
        $client->close();//关闭连接
        return 1;
    }
    public static function redis($redis = 'redis'){
        return think\facade\Cache::store($redis)->handler();
    }
}