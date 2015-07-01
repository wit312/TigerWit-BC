@extends('background.layout.index')
@section('content')
            <div class="page-title">
                
                <div class="title-env">
                    <h1 class="title">客户来源</h1>
                    <p class="description">统计客户来源信息</p>
                </div>
                
                    <div class="breadcrumb-env">
                    
                                <ol class="breadcrumb bc-1">
                                    <li>
                                        <a href="/BackGround"><i class="fa-home"></i>首页</a>
                                    </li>
                                    <li class="active">
                                            <strong>客户来源</strong>
                                    </li>
                                </ol>
                                
                </div>
                    
            </div>

            <!-- Basic Setup -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">来源统计</h3>
                    
                    <div class="panel-options">
                        <a href="#" data-toggle="panel">
                            <span class="collapse-icon">&ndash;</span>
                            <span class="expand-icon">+</span>
                        </a>
                        <a data-toggle="reload" href="#">
                            <i class="fa-rotate-right"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    
                    <script type="text/javascript">
                    jQuery(document).ready(function($)
                    {
                        $("#example-1").dataTable({
                            aLengthMenu: [
                                [3, 25, 50, 100, -1], [3, 25, 50, 100]
                            ],
                            "pagingType": "full_numbers",
                            "processing": true,
                            "serverSide": true,
                            "columns": [
                                   { "name": "username", "data": "username" },
                                   { "name": "phone", "data": "phone" },
                                   { "name": "email",  "data": "email" },
                                   { "name": "lp", "data": "lp" },
                                   { "name": "unit", "data": "unit" },
                                   { "name": "key", "data": "key" },
                                   { "name": "pid", "data": "pid" },
                                   { "name": "date", "data": "date" }
                                 ],
                            // "columnDefs": [ {
                            //        "targets": [ 3 ],
                            //        "createdCell": function (td, cellData, rowData, row, col) {
                            //                          if ( cellData == "London" ) {
                            //                            $(td).css('color', 'red')
                            //                          }
                            //                        }
                            //         },
                            //     {
                            //        "targets": [5],
                            //        "data": null,
                            //        "defaultContent": "<button>Click!</button>"
                            //     } ],
                            "ajax": {
                                // "url": "data/1.txt",
                                "url": "StatTab/Data",
                                "type": "POST"
                            },
                            "fnServerParams": function(oData){
                                oData._token = "{{Session::token()}}";
                            }
                            // ,"ajax": {
                            //     "url": "D:/wamp/www/TigerWit-BC/resources/views/background/block/data/123.txt",
                            //     "type": "POST",
                            //     "datatype": "json",
                            //     "dataSrc": function ( json ) {
                            //         for ( var i=0, ien=json.length ; i<ien ; i++ ) {
                            //             json[i][1] = '<a href="/message/'+json[i][1]+'>View message</a>';
                            //         }
                            //         return json;
                            //     }
                        });
    $('#example-1 tbody').on( 'click', 'button', function () {
        var data = table.row( $(this).parents('tr') ).data();
        alert( data[name] +"'s salary is: "+ data[ name2 ] );
    } );
                    });
                    </script>
                    
                    <table id="example-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>名字</th>
                                <th>手机号</th>
                                <th>邮件</th>
                                <th>lp</th>
                                <th>unit</th>
                                <th>key</th>
                                <th>pid</th>
                                <th>date</th>
                            </tr>
                        </thead>
                    
                       <!--  <tfoot>
                            <tr>
                                <th>名字</th>
                                <th>手机号</th>
                                <th>邮件</th>
                                <th>lp</th>
                                <th>unit</th>
                                <th>key</th>
                                <th>pid</th>
                                <th>date</th>
                            </tr>
                        </tfoot> -->
                    </table>
                    
                </div>
            </div>

    <!-- Imported styles on this page -->
    <link rel="stylesheet" href="assets/js/datatables/dataTables.bootstrap.css">

    <!-- Bottom Scripts -->
    <script src="assets/js/datatables/js/jquery.dataTables.js?v=1"></script>


    <!-- Imported scripts on this page -->
    <script src="assets/js/datatables/dataTables.bootstrap.js"></script>
    <script src="assets/js/datatables/yadcf/jquery.dataTables.yadcf.js"></script>
    <script src="assets/js/datatables/tabletools/dataTables.tableTools.min.js"></script>           
@endsection