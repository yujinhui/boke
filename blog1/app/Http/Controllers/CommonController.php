<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class CommonController extends Controller
{
    //操作成功
    public function ok($font='操作成功',$code=1,$skin=6)
    {
    	echo json_encode(['font'=>$font,'code'=>$code,'skin'=>$skin]);
    	return;
    }
    //操作失败
    public function no($font='操作失败',$code=2,$skin=5)
    {
    	echo json_encode(['font'=>$font,'code'=>$code,'skin'=>$skin]);
    	return;
    }
    //发送邮件
    public function sendMail($data)
    {
        $rand = rand(100000,999999);
        Mail::send('login.send',['code'=>$rand],function($message)use($data){
            //设置主题
            $message->subject('注册码');
            //设置接收方
            $message->to($data);
        });
        return $rand;
    }
    // 循环得到cate_id
    function getCateId($cateInfo,$pid){
    static $id = [];
    foreach ($cateInfo as $k => $v) {
        if ($v->pid == $pid) {
            $id[] = $v->cate_id;
            $this->getCateId($cateInfo,$v->cate_id);
        }
    }
    return $id;
}
    /*
     * @content JSSDK
     */
}