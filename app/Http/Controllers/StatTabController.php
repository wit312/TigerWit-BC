<?php
namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatTabController extends Controller
{
    protected $resp;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resp = ['draw'=>0, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>(object)null ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $request
     * @return json
     */
    protected function getStatData(Request $request)
    {
        $data = '[
        {
            "name": "Angelica",
            "name1": "Ramos",
            "name2": "System Architect",
            "name3": "London",
            "name4": "9th Oct 09",
            "name5": "$2,875"
        },
        {
            "name": "Angelica1",
            "name1": "Ramos",
            "name2": "System Architect",
            "name3": "London1",
            "name4": "9th Oct 09",
            "name5": "$2,875"
        },
        {
            "name": "Angelica2",
            "name1": "Ramos",
            "name2": "System Architect",
            "name3": "London2",
            "name4": "9th Oct 09",
            "name5": "$2,875"
        }
    ]';
        $resp = $this->resp;

        //获取Datatables发送的参数 必要
        $resp['draw'] = $_POST['draw'];//这个值作者会直接返回给前台

        //排序
        $order_dir = "asc";
        $order_column = $_POST['order']['0']['column'];//那一列排序，从0开始
        $order_dir = $_POST['order']['0']['dir'];//asc desc 升序或者降序
         
        //拼接排序sql
        $orderSql = "";
        if(isset($order_column)){
            $i = intval($order_column);
            switch($i){
                case 0;$orderSql = " order by username ".$order_dir;break;
                case 1;$orderSql = " order by phone ".$order_dir;break;
                case 2;$orderSql = " order by email ".$order_dir;break;
                case 3;$orderSql = " order by lp ".$order_dir;break;
                case 4;$orderSql = " order by unit ".$order_dir;break;
                case 5;$orderSql = " order by key ".$order_dir;break;
                case 6;$orderSql = " order by pid ".$order_dir;break;
                case 7;$orderSql = " order by date ".$order_dir;break;
                default;$orderSql = '';
            }
        }

        if(isset($order_column)){
            $i = intval($order_column);
            switch($i){
                case 0;$order_column_n = "username";break;
                case 1;$order_column_n = "phone";break;
                case 2;$order_column_n = "email";break;
                case 3;$order_column_n = "lp";break;
                case 4;$order_column_n = "unit";break;
                case 5;$order_column_n = "key";break;
                case 6;$order_column_n = "pid";break;
                case 7;$order_column_n = "date";break;
                default;$order_column_n = 'date';
            }
        }
        //搜索
        $search = $_POST['search']['value'];//获取前台传过来的过滤条件

        //分页
        $start = $_POST['start'];//从多少开始
        $length = $_POST['length'];//数据长度
        $limitSql = '';
        $limitFlag = isset($_POST['start']) && $length != -1 ;
        if ($limitFlag ) {
            $limitSql = " LIMIT ".intval($start).", ".intval($length);
        }

        //定义查询数据总记录数sql
        $sumSql = "SELECT count(id) as sum FROM DATATABLES_DEMO";
        //条件过滤后记录数 必要
        $recordsFiltered = 0;
        //表的总记录数 必要
        $recordsTotal = 0;

        // $recordsTotalResult = DB::table('stat')
        //                         ->select('count(*) as CN')
        //                         ->where('username||phone||email||lp||unit||key||pid||date', 'like', '%'.$search.'%')
        //                         ->first();

        $whereSql = "";
        if(isset($search) && $search != ""){
            $whereSql = " WHERE username||phone||email||lp||unit||key||pid||date like %".$search."% ";
        }
        
        // $recordsResult = DB::select("SELECT 'username','phone','email','lp','unit','key','pid','date' FROM stat".$whereSql.$orderSql)
        //                     ->get();

        $recordsResult = DB::table('stat')
                            ->select('username','phone','email','lp','unit','key','pid','date')
                            ->where('username', 'like', '')
                            ->where('username||phone||email||lp||unit||key||pid||date', 'like', '%'.$search.'%')
                            ->orderBy($order_column_n,$order_dir)
                            ->get()
                            ->toArray();                            

        $recordsFiltered = $recordsTotal = count($recordsResult);
        $resp['data'] = json_decode($data);
        $resp['data'] = $recordsResult;


        $resp['recordsTotal'] = $recordsTotal;
        $resp['recordsFiltered'] = $recordsFiltered;
        return json_encode($resp);
    }

}
