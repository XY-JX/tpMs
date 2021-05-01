<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }

    public function cs()
    {
        $this->getData([
            ['name',9],
            ['age',9],
            ['email',9],
        ], [
            'name'  => 'require|max:25',
            'age'   => 'number|between:1,120',
            'email' => 'email',
        ], [
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'age.number'   => '年龄必须是数字',
            'age.between'  => '年龄必须在1~120之间',
            'email'        => '邮箱格式错误',
        ]);
        return 'hello, world !';
    }
    public function cs1(){
        $cache = cache();
        for ($i=1;$i<10 ; $i++){
            $orderInfo =['goods'=> mt_rand(1, 10000), 'uid'=>mt_rand(1, 20) ,'num'=>mt_rand(1, 5)];
            $cache->lPush('order:create:list', json_encode([
                'goods' => $orderInfo['goods'],
                'uid' => $orderInfo['uid'],
                'num' => $orderInfo['num']
            ]));
        }

    }
    public function cs2(){
       echo cache('name1',99999);
        echo cache('name2',99999,10);

    }
}
