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

Route::any('/','BackGroundController@index')->before("login");

Route::any('/home','BackGroundController@index')->before("login");

Route::get('/BackGround','BackGroundController@index')->before("login");

Route::get('/MasterList','BackGroundController@masterrecommend');

Route::get('/unwraptel','BackGroundController@unwraptel');

Route::get('/mastertype','BackGroundController@mastertype');

Route::get('/userdetail','BackGroundController@userdetail');

Route::get('/mastermanager','BackGroundController@mastermanager');


Route::get('/StatTab','BackGroundController@stat_tab')->before("login");

Route::get('/updategroup','BackGroundController@updategroup');

Route::get('/updateparity','BackGroundController@updateparity');

Route::get('/verifyuser','BackGroundController@verifyuser');


Route::get('/UserManager','BackGroundController@user_manager')->before("login");

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

// Authentication routes...
Route::get('auth/login', 'Auth\AuthUserController@getLogin');
Route::post('auth/login', 'Auth\AuthUserController@postLogin');
Route::get('auth/logout', 'Auth\AuthUserController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthUserController@getRegister');
Route::post('auth/register', 'Auth\AuthUserController@postRegister');

Route::post('StatTab/Data', 'StatTabController@getStatData');

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
    if(!empty($users) && $users!=0)
    {
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
    }else{
        $result="M T 4 I D :".$data."不存在！请先注册！";
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
    $data = DB::select('select * from tiger.master_new order by rank asc');
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
            $t=0;
            if ($total[0]->count!=0) {
                 $t=$w[0]->count/$total[0]->count*100;
            }
            if ($total[0]->count!=0) {
                $model->win=number_format($w[0]->count/$total[0]->count*100,2);
            }else{
                $model->win=0;
            }
            $model->lose=number_format(100.00-$t,2);
            $context = new ZMQContext();
            $sender = new ZMQSocket($context, ZMQ::SOCKET_REQ);
            $sender->connect("tcp://169.54.231.244:1990");
            $sender->send("{'mt4UserID':$model->mt4_id,'__api':'Equity'}");
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


Route::filter("login",function()
{
    if(!Auth::check())
    {
        Auth::logout();
        return Redirect::to('auth/login')->with('message','请先登录!');
    }  
});



Route::get('/AddMaster',function(){
    $data=Request::input("data");
    $argtype=Request::input("argtype");
    $result = "添加失败！";
    //先查询是否存在
    $da=DB::select('select * from tiger.user where mt4_real=? || phone=? || email=?',[$data,$data,$data]);
    if ($da!=null)
    {
        $mt4=$da[0]->mt4_real;
        $a= DB::select("SELECT mt4_id, SUM(`profit`+`storage`) AS profit_rate, COUNT(*) AS total_orders, SUM((close_price - open_price) * pow(10, digits -3) * ((cmd % 2) * -2 + 1) * volume * pip_coefficient) AS total_pip FROM tiger.history WHERE mt4_id=? AND `comment` <> \"cancelled\" GROUP BY `mt4_id` ORDER BY profit_rate",[$mt4]);
        $profit_rate=0.00;
        if ($a!=null) {
            $profit_rate=$a[0]->profit_rate;
        }
        $total_orders=0.00;
        if ($total_orders!=null) {
            $total_orders=$a[0]->total_orders;
        }
        $total_pip=0.00;
        if ($total_pip!=null) {
            $total_pip=$a[0]->total_pip;
        }
        $rate = DB::select("SELECT amount FROM payment WHERE mt4_id=? AND status = 4 LIMIT 1",[$mt4]);
        $ret = 0.0;
        if ($rate!=null){
            if($rate[0]->amount!=0){
                $ret = $profit_rate / 10000;
            }else{
                 $ret = $profit_rate /$rate[0]->amount;
            }
        }    
        else{
              $ret = $profit_rate / 10000;
        }
        $count=DB::select("SELECT COUNT(*) AS profitable_count FROM history WHERE mt4_id = ? AND `profit` + `storage` > 0",[$mt4]);
        $profitable_count=0.00;
        if($count!=null) {
           $profitable_count=$count[0]->profitable_count;
        }
        $uinfo=DB::select("SELECT * FROM user WHERE mt4_real = ?",[$mt4]);
        $username="";
        $sex=0;
        $usercod=0;
        if ($uinfo!=null) {
            $username=$uinfo[0]->username;
            $usercode=$uinfo[0]->user_code;
            $sex=$uinfo[0]->sex;
        }
        $percent_profitable = 0.0;
        if ($total_orders != 0){
             $percent_profitable = $profitable_count / $total_orders;
        }
          

        //rank         排名
        //copycount    跟单人数
        //copyamount   跟单金额
        //maxretract   最大回撤=浮动盈亏/总盈利
        //totalprofit  总收益
        //type         交易类型

        $context = new ZMQContext();
        $order=null;
        $orders= DB::select("select order_id,mt4_id from `order` where mt4_id=? and cmd=0 || cmd=1",[$mt4]);
        if ($orders!=null) {
           $arr=null;
           foreach ($orders as $key => $value) {
           $arr.=strval($value->order_id).',';
           }
          $order= rtrim($arr, ",");  //以，分隔的order字符串 
        }
        // Connect to task ventilator
        $sender = new ZMQSocket($context, ZMQ::SOCKET_REQ);
        $sender->connect("tcp://169.54.231.244:1990");
        $sender->send("{'from_mt4':$mt4,'to_mt4':null,'orders':$order,'__api':'CopyStatisticsInfo'}");
        $recv= $sender->recv(); 
        $copyamount=0.00;
        $copycount=0;
        $Maxretract=0.00;
        if($recv!=null) {
           $array=json_decode($recv);
           $copyamount=$array->Totalamount==null?0.00:$array->Totalamount;
           $copycount=$array->Totalnum;
           $Maxretract=$array->Maxretract;
        }
        $file_contents = file_get_contents("https://i.tigerwit.com/api/v2/summary_report_noauth?cros_user=$usercode&period=360&tiger_source=real");
        $totalprofit=0.00;
        $json=json_decode($file_contents);
        if ($json!=null) {
            if ($json->is_succ==true) {
                $totalprofit=$json->total_profit_rate;
            }else{
                    $totalprofit=0.00;
            }
        }
        $type=$argtype;
        $rank=100;//默认值
        //插入                          
        $date= date('Y-m-d');
        $i=DB::insert("INSERT INTO master_new(`date`,`username`,`sex`,`orders`, `profit_rate`,`pips`, `percent_profitable`, `mt4_id`, `period`,rank,copycount,copyamount,maxretract,totalprofit,`type`,`status`) VALUES('$date','$username', $sex, $total_orders, $ret, $total_pip, $percent_profitable, $mt4, 7, $rank, $copycount, $copyamount, $Maxretract, $totalprofit, $type,0)");
        //echo "INSERT INTO master_new(`date`,`username`,`sex`,`orders`, `profit_rate`,`pips`, `percent_profitable`, `mt4_id`, `period`,rank,copycount,copyamount,maxretract,totalprofit,`type`) VALUES(date("Y-m-d"),$username, $sex, $total_orders, $ret, $total_pip, $percent_profitable, $data, 7, $rank, $copycount, $copyamount, $Maxretract, $totalprofit, $type)";
        if ($i>0) {
           $result="true";
        }
    }
    return $result;
});


 Route::get('/UpdateMaster',function(){
    $mt4_id=Request::input("mt4_id");
    $rank=Request::input("rank");
    $result = "修改失败！";
    if ($mt4_id!=null && $rank!=null) {
        $data=DB::select('select count(*) from tiger.user where mt4_real=?',[$mt4_id]);
        if ($data!=null)
        {
           $i=DB::update('update master_new set rank=? where mt4_id=?',[$rank,$mt4_id]);
           if ($i>0)
           {
               $result = "true";
           }
        }
        return $result;
    }
 });


Route::get('/RemoveMaster',function(){
    $mt4_id=Request::input("mt4_id");
    $result = "删除失败！";
    if ($mt4_id!=null) {
        $data = DB::select('select * from tiger.master_new where mt4_id=?',[$mt4_id]);
        if($data!=null) {
           $i=DB::select('delete from tiger.master_new where mt4_id=?',[$mt4_id]);
            if($i>0)
            {
                $result="true";
            }
        }
    }
    return $result;
});


Route::get('/ChangeGroup',function(){
    $mt4_id=Request::input("mt4_id");
    $group=Request::input("group");
    $result = "修改失败！";
    if ($mt4_id!=null && $group!=null) {
        $context = new ZMQContext();
        $sender = new ZMQSocket($context, ZMQ::SOCKET_REQ);
        $sender->connect("tcp://169.54.231.244:1990");
        $sender->send("{'mt4_id':$mt4_id,'group_name':'$group','__api':'ChangeGroup'}");
        $recv= $sender->recv(); 
        if ($recv!=null) {

            $array=json_decode($recv);
            $result=$array->error_msg;
            if ($array->is_succ) {
               return "true";
            }
        }
    }
    return $result;
});


Route::get('/ChangeParity',function(){
    $parity=Request::input("parity");
    $result = "修改失败！";
    if($parity!=null){
        $i=0;
        $i=DB::update('UPDATE `options` SET `value` =? WHERE `key` =?',[$parity,'USDCNY']);
        if ($i>0) {
           $result="true";
        }
    }
    return $result;
});



Route::get('/GetUserVerifyList',function(){
        $data=DB::select('select * from tiger.user where id_front is not null and id_back is not null');
        return $data;
});


Route::get('/UpdateUserVerify',function(){
    $isverify=Request::input("isverify");
    $user_code=Request::input("user_code");
    $verifyinfo=Request::input("verifyinfo");
    $result="操作失败！";
    if ($isverify!=null && $user_code!=null) {
        if ($isverify==1) {
            $i=0;
            $i=DB::select('update tiger.user set verified=? where user_code=?',[$isverify,$user_code]);
            if ($i>0) {
                $result="true";
            }
        }else if($isverify==-1 && $verifyinfo!=null){
            $i=0;
            $i=DB::select('update tiger.user set verified=?,verifyinfo=? where user_code=?',[$isverify,$verifyinfo,$user_code]);
            if ($i>0) {
                $result="true";
            }
        }
    }
    return $result;
});


Route::get('/AgainUserVerify',function(){
    $user_code=Request::input("user_code");
    $result="操作失败！";
     if ($user_code!=null) {
            $i=0;
            $i=DB::select('update tiger.user set verified=? where user_code=?',[0,$user_code]);
            if ($i>0) {
                $result="true";
            }
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

