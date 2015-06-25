@extends('background.layout.index')
@section('content')
    <script>
        $(function(){
            Reload();
              $("#sboxit").selectBoxIt().on('open', function()
                {
                    // Adding Custom Scrollbar
                    $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
                });
        });
         function Reload() {
        $.ajax({
            type: 'GET',
            url: '/GetMasterTypeList',
            success: function (e) {
               if(e!=null){
                                //先清空
                                $("#Tb").find("tr").remove();
                                var index=0;
                            　　for(var model in e){
                                ++index;
                                $("#Tb").append('<tr> <td>'+index+'</td> <td>'+e[model]["type"]+'</td>  <td><a href="#" onclick="Edit('+e[model]["mt4_id"]+')" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a> <a href="#" class="btn btn-danger btn-sm btn-icon icon-left" onclick="Remove('+e[model]["mt4_id"]+')">Delete</a></td> </tr>');
                                }
                        }
            }
        });
    }
    function Remove(param){
         $.ajax({
            type: 'GET',
            url: '/RemoveMasterTypeList?id=' + param,
            success: function (e) {
                if (e == 'true') {
                    Myalert('modal-5');
                    Reload();
                } else {
                    //添加失败
                    Myalert('modal-5','操作失败');
                }
            }
        });
    }
    function Edit(param){
        var type = $("#selected").val();
        if (type!=-1) {
            $.ajax({
                type: 'GET',
                url: '/UpdateMasterType?mt4_id=' + param + '&type=' + type,
                success: function (e) {
                    if (e == 'true') {
                        Myalert('modal-5',"成功修改！");
                        LoadPage();
                    } else {
                        //修改失败
                        Myalert('modal-5',"修改失败，请联系数据库管理员！");
                    }
                }
            });
        } 
    }
    </script>
    <div class="page-title">

        <div class="title-env">
            <h1 class="title">高手榜管理</h1>
            <p class="description">提供高手榜管理的功能</p>
        </div>

        <div class="breadcrumb-env">

            <ol class="breadcrumb bc-1">
                <li>
                    <a href="dashboard-1.html"><i class="fa-home"></i>Home</a>
                </li>
                <li>

                    <a href="ui-panels.html">UI Elements</a>
                </li>
                <li class="active">

                    <strong>高手榜管理</strong>
                </li>
            </ol>

            </div>
        </div>
<div class="row">
                <div class="col-sm-12">
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">选项</h3>
                            <div class="panel-options">
                                <a href="#" data-toggle="panel">
                                    <span class="collapse-icon">–</span>
                                    <span class="expand-icon">+</span>
                                </a>
                            </div>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                                    <div style="width:218px;">
                                    <select class="form-control" id="sboxit" style="display: none;">
                                        <option value="0">高手排行榜</option>
                                        <option value="1">高手审核榜</option>

                                    </select>
                                    </div>
                                </div>
                            <form role="form" class="form-inline">
                                
                                <div class="form-group">
                                    <input type="text" class="form-control" size="25" placeholder="邮箱">
                                </div>
                                
                                <div class="form-group">
                                    <input type="password" class="form-control" size="25" placeholder="手机">
                                </div>
                                
                                 <div class="form-group">
                                    <input type="password" class="form-control" size="25" placeholder="MT4 ID">
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-secondary btn-single"> 添 加 </button>
                                </div>
                                
                            </form>
                        
                        </div>
                    </div>
                
                </div>
            </div>

<div class="tabs-vertical-env">
                            
                                <ul class="nav tabs-vertical">
                                    <li class="active">
                                        <a href="#web" data-toggle="tab">
                                            <i class="fa-globe visible-xs"></i>
                                            <span class="hidden-xs">高手审核榜</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#images" data-toggle="tab">
                                            <i class="fa-picture visible-xs"></i>
                                            <span class="hidden-xs">高手排行榜</span>
                                        </a>
                                    </li>
                                </ul>
                                
                                <div class="tab-content">
                                    
                                    <!-- Sample Search Results Tab -->
                                    <div class="tab-pane active" id="web">
                                        
                                         <div class="panel panel-color panel-white"><!-- Add class "collapsed" to minimize the panel -->
        <div class="panel-heading">
            <h3 class="panel-title"><strong>高手列表</strong></h3>

            <div class="panel-options">
                <a href="#">
                    <i class="linecons-cog"></i>
                </a>

                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>

                <a href="#" data-toggle="reload">
                    <i class="fa-rotate-right"></i>
                </a>

                <a href="#" data-toggle="remove">
                    &times;
                </a>
            </div>
        </div>

        <div class="panel-body">

            <table class="table table-model-2 table-hover" id="example-3">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>MT4账号</th>
                    <th>昵称</th>
                    <th>近6个月盈利率</th>
                    <th>盈利率占比</th>
                    <th>亏损占比</th>
                    <th>账户总额</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody id="Tb">

                </tbody>
            </table>
            <div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example-2_info" role="status" aria-live="polite">Showing 1 to 10 of 60 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_simple_numbers" id="example-2_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="example-2" tabindex="0" id="example-2_previous"><a href="#">Previous</a></li><li class="paginate_button active" aria-controls="example-2" tabindex="0"><a href="#">1</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">2</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">3</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">4</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">5</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">6</a></li><li class="paginate_button next" aria-controls="example-2" tabindex="0" id="example-2_next"><a href="#">Next</a></li></ul></div></div></div>


        </div>
    </div>
                                        
                                    </div>
                                    
                                    <!-- Search Results Tab -->
                                    <div class="tab-pane" id="images">
                                        Search results about images...
                                    </div>
                                    
                                </div>
                                
                            </div>

<link rel="stylesheet" href="assets/js/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="assets/js/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="assets/js/select2/select2.css">
    <link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
    <link rel="stylesheet" href="assets/js/multiselect/css/multi-select.css">
<script src="assets/js/daterangepicker/daterangepicker.js"></script>
    <script src="assets/js/datepicker/bootstrap-datepicker.js"></script>
    <script src="assets/js/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/colorpicker/bootstrap-colorpicker.min.js"></script>
    <script src="assets/js/select2/select2.min.js"></script>
    <script src="assets/js/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
    <script src="assets/js/tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="assets/js/typeahead.bundle.js"></script>
    <script src="assets/js/handlebars.min.js"></script>
    <script src="assets/js/multiselect/js/jquery.multi-select.js"></script>

@endsection