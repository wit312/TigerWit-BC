<?php
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
Route::get('/hello', function () {
    return view('welcome');
});
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('/agent_report', function () {
    $timestamp=date('y-m-d h:i:s',time());
    $ip=Request::input('ip');
    $phone=Request::input('phone');
    $source=Request::input('source');
    //ip, phone, timestamp, source 
    $result="{'is_succ':"+true+", 'status':"+0+" }";
	//手机号码验证
	if(!isMobile($phone))
	{
		 return format("{is_succ:{0},error_msg:{1},data:{status:{2},msg:{3}}}","True","",4,"手机号码格式错误");
	}
    if (Request::isMethod('get') && !empty($phone)) {
	    //是否频繁提交
	    $Isfrequently = DB::select('select id from tiger.agent_manager where ip=? and `timestamp`>DATE_SUB(NOW(),INTERVAL 5 minute) limit 1', [$ip]);
	    if ($Isfrequently != null) {
	        if ($Isfrequently[0]->id != null)//频繁
	        {
	            return format("{is_succ:{0},error_msg:{1},data:{status:{2},msg:{3}}}","True","",2,"频繁提交");
	        }
	    }
	    //是否重复、
	    $IsRepeat = DB::select('select id from tiger.agent_manager where phone=? limit 1', [$phone]);
	    if ($IsRepeat != null) {
	        if ($IsRepeat[0]->id != null)//重复
	        {
	            return format("{is_succ:{0},error_msg:{1},data:{status:{2},msg:{3}}}","True","",1,"号码重复");
	        }
	    }
	    $insert = DB::select('insert into tiger.agent_manager(`ip`, `phone`, `timestamp`, `source`) values(?,?,?,?)', [$ip, $phone, $timestamp,$source]);
	  	$mail= mail("wendy@tigerwit.com","新的代理商电话号码:".$phone,$phone."申请成为代理商,时间:".$timestamp." IP:".$ip,'From: noreply@tigerwit.com' . "\r\n" . 'Reply-To: noreply@tigerwit.com' . "\r\n" . 'Content-type: text/html; charset=utf-8'");
	  	return format("{is_succ:{0},error_msg:{1},data:{status:{2},msg:{3}}}","True","",0,"成功记录");
    }else{
    	return format("{is_succ:{0},error_msg:{1},data:{status:{2},msg:{3}}}","True","",3,"参数为空");
    }
    
});
function format() {
    $args = func_get_args();
    if (count($args) == 0) { return;}
    if (count($args) == 1) { return $args[0]; }
    $str = array_shift($args);
    $str = preg_replace_callback('/\\{(0|[1-9]\\d*)\\}/', create_function('$match', '$args = '.var_export($args, true).'; return isset($args[$match[1]]) ? $args[$match[1]] : $match[0];'), $str);
    return json_encode($str);
}
function isMobile($value,$match='/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/'){
	$v = trim($value);
	if(empty($v) || strlen($v)>15) return false;
	return preg_match($match,$v);
}