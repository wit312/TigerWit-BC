<?php

use app\libiray\Common;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/BackGround','BackGroundController@index');

Route::get('/MasterList','BackGroundController@masterrecommend');

Route::get('/unwraptel','BackGroundController@unwraptel');

Route::get('/mastertype','BackGroundController@mastertype');

Route::get('/userdetail','BackGroundController@userdetail');

Route::get('/mastermanager','BackGroundController@mastermanager');



Route::get('/test',function(){
//$context = new ZMQContext();
// Connect to task ventilator
//$sender = new ZMQSocket($context, ZMQ::SOCKET_REQ);
//$sender->connect("tcp://169.54.231.244:1990");
//var_dump(111);
//$sender->send("{'mt4UserID':500001,'__api':'Equity'}"); 
    

    $a= DB::select("SELECT mt4_id, SUM(`profit`+`storage`) AS profit_rate, COUNT(*) AS total_orders, SUM((close_price - open_price) * pow(10, digits -3) * ((cmd % 2) * -2 + 1) * volume * pip_coefficient) AS total_pip FROM tiger.history WHERE mt4_id=? AND `comment` <> \"cancelled\" GROUP BY `mt4_id` ORDER BY profit_rate",[500001]);
    var_dump($a);
});

Route::get('/unwrap_phone',function(){
    $phone = Request::input('phone');
    $result="解绑失败！";
    if(isMobile($phone))
    {
        $users = DB::select('SELECT id FROM tiger.user where phone=?', [$phone]);
        if($users!=null)
        {
            $count = DB::update('update tiger.user set phone=? where id=?', [$users[0]->id,$users[0]->id]);
                if ($count > 0)
                {
                    $result = "true";
                }
        }
    }else
    {
        $result= '请填写正确的手机号！';
    }
    return $result;
});



Route::get('/AddMasterList', function () {
    $type = Request::input('type');
    $data = Request::input('data');
    $result="插入失败！请先填写个人说明！";
    $users = DB::select('select username,user_code,mt4_real,`desc` from tiger.user where mt4_real=? limit 1', [$data]);
    $desc= $users[0]->desc;
    if($desc!=null)
    {
        //历史数据
        $dic=DB::select('select * from tiger.recommend where mt4_id=? and type=?', [$data,$type]);
        if($dic==null)
        {
            $i=DB::select('insert into tiger.recommend(mt4_id, `type`,`desc`) values(?,?,?)', [$data,$type,$desc]);
            if($i>0)
            {
                //成功
                $result="true";
            }
        }else
        {
            $result="已有相同项！";
        }
    }
    return $result;
});

Route::get('/GetMasterList', function () {
    $data = DB::select('select * from tiger.recommend');
    if($data!=null) {
        foreach($data as $model){
            $temp=DB::select('select * from tiger.user where mt4_real=?', [ $model->mt4_id]);
            $model->name=$temp[0]->username;
        }
    }
    return $data;
});

Route::get('/RemoveMasterList', function () {
    $id = Request::input('id');
    $result="删除失败！";
    $data = DB::select('select * from tiger.recommend where mt4_id=?',[$id]);
    if($data!=null) {
       $i=DB::select('delete from tiger.recommend where mt4_id=?',[$id]);
        if($i>0)
        {
            $result="true";
        }
    }
    return $result;
});

Route::get('/GetMasterTypeList',function(){
    $data = DB::select('select * from tiger.master_new');
    if($data!=null) {
            //当前时间 
            $now=date('Y-m-d H:i:s'); 
            //6月前
            $before=date('Y-m-d H:i:s',strtotime('-6 month')); 
        foreach($data as $model){
            $temp=DB::select('select * from tiger.user where mt4_real=?', [ $model->mt4_id]);
            $model->name=$temp[0]->username;
            $model->type=TypeToName($model->type);
            //算6个月盈利率
            $p=DB::select('select sum(profit+`storage`) as p from history where mt4_id=? and profit+`storage`>0 and timestamp between ? and ?',[$model->mt4_id,$before,$now])[0]->p; //盈利
            $w=DB::select('select amount from tiger.payment where mt4_id=?',[$model->mt4_id])==null?1000.0000:DB::select('select amount from tiger.payment where mt4_id=?',[$model->mt4_id])[0]->amount;//初始入金 没有按1000算
            $model->six_rate=$p/$w;
            //算胜率
            $w=DB::select('select count(*) as `count` from history where mt4_id=? and profit+`storage`>0 and `timestamp` between ? and ?',[$model->mt4_id,$before,$now]);
            $total=DB::select('select count(*) as `count` from history where mt4_id=? and `timestamp` between ? and ?',[$model->mt4_id,$before,$now]);
            if ($total==null) {
                $model->win=0.00;
            }
            $t=$w[0]->count/$total[0]->count*100;
            $model->win=number_format($w[0]->count/$total[0]->count*100,2);
            $model->lose=number_format(100.00-$t,2);
            $context = new ZMQContext();
            $sender = new ZMQSocket($context, ZMQ::SOCKET_REQ);
            $sender->connect("tcp://169.54.231.244:1990");
            $sender->send("{'mt4UserID':+$model->mt4_id+,'__api':'Equity'}");
            $recv=$sender->recv();
            if ($recv!=null) {
                $model->volunm= json_decode($recv)->balance;
            }else{
                $model->volunm= 0.00;
            }
            
        }
    }
    return $data;
});


Route::get('/RemoveMasterTypeList', function () {
    $id = Request::input('id');
    $result="删除失败！";
    $data = DB::select('select * from tiger.master_new where mt4_id=?',[$id]);
    if($data!=null) {
       $i=DB::select('delete from tiger.master_new where mt4_id=?',[$id]);
        if($i>0)
        {
            $result="true";
        }
    }
    return $result;
});

Route::get('/UpdateMasterType',function(){
    $id = Request::input("mt4_id");
    $type = Request::input("type");
    $result = "修改失败！";
    $data=DB::select('select count(*) from tiger.user where mt4_real=?',[$id]);
    if ($data!=null && $type!=null)
    {
       $i=DB::update('update master_new set type=? where mt4_id=?',[$type,$id]);
       if ($i>0)
       {
           $result = "true";
       }
    }
    return $result;
});


Route::get('/AddMaster',function(){
    $data=Request::input("data");
    $result = "添加失败！";
    //先查询是否存在
    $data=DB::select('select count(*) from tiger.user where mt4_real=?',[$data]);
    if ($data!=null)
    {
        $a= DB::select("SELECT mt4_id, SUM(`profit`+`storage`) AS profit_rate, COUNT(*) AS total_orders, SUM((close_price - open_price) * pow(10, digits -3) * ((cmd % 2) * -2 + 1) * volume * pip_coefficient) AS total_pip FROM tiger.history WHERE mt4_id=? AND `comment` <> \"cancelled\" GROUP BY `mt4_id` ORDER BY profit_rate",[500001]);
        $profit_rate=$a->profit_rate;
        $total_orders=$a->total_orders;
        $total_pip=$a->total_pip;
        $rate = DB::select("SELECT amount FROM payment WHERE mt4_id=? AND status = 4 LIMIT 1",[$data->mt4_real]);
        $ret = 0.0;
        if ($rate == null && and $rate!=0)
            $ret = $rate->amount / 10000;
        else{
            $ret = $rate /$rate;
        }
       
        //rank 
        //copycount
        //copyamount
        //maxretract
        //totalprofit
        //type
    }
    return $result;
});






function isMobile($value,$match='/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/'){
    $v = trim($value);
    if(empty($v) || strlen($v)>15) return false;
    return preg_match($match,$v);
}

function TypeToName($type)
        {
            switch ($type)
            {
                case "0":
                     $type="短期投资";
                    break;
                case "1":
                    $type="长期投资";
                    break;
                case "2":
                    $type="最多复制";
                    break;
                case "3":
                    $type = "最多盈利";
                    break;
            }
            return $type;
        }

