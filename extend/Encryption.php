<?php
class Encryption
{

    private static  $method='aes-256-xts';
    private static  $key='dd2b1fa5a39b9051fb1982';
    private static  $iv='encrypt@decrypt@';//请保证16位
    private static  $options=OPENSSL_ZERO_PADDING;
    /**
     * openssl_decrypt解密
     * @param $data
     * @return string
     */
    public static function decrypt($data,$iv=''){
        $openssl_decrypt =  openssl_decrypt($data,self::$method,self::$key,self::$options,$iv?:self::$iv);
        if (false === $openssl_decrypt) {
            return ['code'=>0,'data'=>[],'msg'=>'解密失败'];
        }else{
            return ['code'=>1,'data'=>json_decode($openssl_decrypt,true),'msg'=>'解密成功'];
        }
    }
    /**
     * openssl_encrypt加密
     * @param $data
     * @return string
     */
    public static function encrypt($data,$iv=''){
        if(is_array($data))
            $data=json_encode($data);
       $openssl_encrypt =  openssl_encrypt($data,self::$method,self::$key,self::$options,$iv?:self::$iv);
        if (false === $openssl_encrypt) {
            return ['code'=>0,'data'=>[],'msg'=>'字符长度不能少于16位，目前长度'.strlen($data).'位)'];
        }else{
            return ['code'=>1,'data'=>$openssl_encrypt,'msg'=>'加密成功'];
        }
    }
    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function long_decrypt( $encryptedData, $iv, &$data )
    {
        $result='';
        foreach (explode('|',$encryptedData) as $chunk) {
            $result.= openssl_decrypt($chunk,self::$method,self::$key,self::$options,$iv?:self::$iv);
        }
        if( !$result )
        {
            return -1;
        }
        $data=json_decode($result,true);
        if(!$data)
            $data = $result;
        return 0;
    }
    public function long_encrypt($encryptedData,$iv,&$data){

        $result='';
        foreach (str_split($encryptedData, 660) as $chunk) {
            $result.= openssl_encrypt($data,self::$method,self::$key,self::$options,$iv?:self::$iv).'|'; //第四参数OPENSSL_RAW_DATA输出原始数据
        }
        $data=array(
            'data'=>$result,
            'iv'=>$iv
        );
        return 0;
    }

}