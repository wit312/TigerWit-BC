@extends('background.layout.index')
@section('content')
    <script>
        $(function(){
                        $("#verify_reason").val($("#verify_select").children('option:selected').val());
                        $("#verify_select").change(function(){
                            if($(this).children('option:selected').val()=="其他"){
                                $("#verify_reason").attr("style","display:show");
                                $("#bag").attr("verifyinfo",$("#verify_reason").val());
                            }else{
                                 $("#verify_reason").attr("style","display:none");
                                $("#verify_reason").val($("#verify_select").children('option:selected').val());
                            }

                        });

                        var $state = $("#example-3 thead input[type='checkbox']");
                        
                        $("#example-3").on('draw.dt', function()
                        {
                            cbr_replace();
                            
                            $state.trigger('change');
                        });
                        
                        // Script to select all checkboxes
                        $state.on('change', function(ev)
                        {
                            var $chcks = $("#example-3 tbody input[type='checkbox']");
                            
                            if($state.is(':checked'))
                            {
                                $chcks.prop('checked', true).trigger('change');
                            }
                            else
                            {
                                $chcks.prop('checked', false).trigger('change');
                            }
                        });




              Reload();

              $('#modal-2').on('hidden.bs.modal', function () {
                     $("#verify_text").html("通过");
                     $("#verify_selectSelectBoxItContainer").attr("style","display:none");
                     $("#verify_reason").attr("style","display:none");
                })
              

              $("#sboxit").selectBoxIt().on('open', function()
                {
                    // Adding Custom Scrollbar
                    $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
                });

               $("#verify_select").selectBoxIt().on('open', function()
                {
                    // Adding Custom Scrollbar
                    $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
                });

                $("#Confirm_verify").click(function(){
                    //点击确认
                        var param= $("#bag").attr("tag"); //获取参数
                        var isverify= $("#bag").attr("isverify");
                        var verifyinfo=$("#verify_reason").val();
                        if (param!=null && isverify!=null) {
                            $.ajax({
                                type: 'GET',
                                url: '/UpdateUserVerify?user_code='+param+'&isverify='+isverify+'&verifyinfo='+verifyinfo,
                                success: function (e) {
                                    if (e == 'true') {
                                        $('#modal-2').modal('hide');
                                        Myalert('modal-5',"成功修改！");
                                         Reload();
                                    } else {
                                        //修改失败
                                        $('#modal-2').modal('hide');
                                        Myalert('modal-5',e);
                                    }
                                }
                            });
                        }
                        
                });
                
        });
        
        function Test(){
           //alert($("#Tb_1:checked"));
          $("input[type=checkbox]:checked").each(function() {
              var name = $(this).attr('class');
              alert(name);
            });
        }

        function Again(param){
                        if (param!=null) {
                            $.ajax({
                                type: 'GET',
                                url: '/AgainUserVerify?user_code='+param,
                                success: function (e) {
                                    if (e == 'true') {
                                        Myalert('modal-5',"成功修改！");
                                        Reload();
                                    } else {
                                        //修改失败
                                        Myalert('modal-5',e);
                                    }
                                }
                            });
                        }
        }


        function Confirm(param,isverify){
            $('#modal-2').modal('show');
            $("#bag").attr("tag",param); //传参数
            $("#bag").attr("isverify",isverify);
            $("#verify_selectSelectBoxItContainer").attr("style","display:none");
            $("#verify_reason").attr("style","display:none");
            if (isverify==-1) {
                $("#verify_selectSelectBoxItContainer").attr("style","display:show");
                            $("#verify_text").html("拒绝");
                        };
        }

         function Reload() {
        $.ajax({
            type: 'GET',
            url: '/GetUserVerifyList',
            success: function (e) {
               if(e!=null){
                                //先清空
                                $("#Tb").find("tr").remove();
                                $("#Tb_1").find("tr").remove();
                                $("#Tb_2").find("tr").remove();
                            　　for(var model in e){
                                    if (e[model]["verified"]==1) { //通过  real_create_date  username  real_name mt4 mt4_real id_front id_back

                                    $("#Tb").append('<tr>  <td>'+e[model]["username"]+'</td> <td>'+e[model]["real_name"]+'</td> <td>'+e[model]["mt4_real"]+'</td> <td>'+e[model]["mt4"]+'</td><td><img src="/'+e[model]["id_front"]+'" ></td><td><img src="/'+e[model]["id_back"]+'" ></td>  <td>已通过</td> </tr>');
                                   
                                    }else if(e[model]["verified"]==-1){ //未通过 

                                    $("#Tb_1").append('<tr> <td><input type="checkbox" class="cbr"></td> <td>'+e[model]["username"]+'</td> <td>'+e[model]["real_name"]+'</td> <td>'+e[model]["mt4_real"]+'</td> <td>'+e[model]["mt4"]+'</td><td><img src="/'+e[model]["id_front"]+'" ></td><td><img src="/'+e[model]["id_back"]+'" ></td>   <td><a href="#" class="btn btn-danger btn-sm btn-icon icon-left" onclick="Again('+e[model]["user_code"]+',-1)">重新审核</a></br>已拒绝，原因：</br>'+e[model]["verifyinfo"]+'</td> </tr>');
                                   
                                    }else{//待审核

                                        $("#Tb_2").append('<tr> <td><input type="checkbox" class="cbr"></td> <td>'+e[model]["username"]+'</td> <td>'+e[model]["real_name"]+'</td> <td>'+e[model]["mt4_real"]+'</td> <td>'+e[model]["mt4"]+'</td><td><img src="/'+e[model]["id_front"]+'" ></td><td><img src="/'+e[model]["id_back"]+'" ></td>  <td><a href="#" onclick="Confirm('+e[model]["user_code"]+',1)" class="btn btn-secondary btn-sm btn-icon icon-left">通过</a> <a href="#" class="btn btn-danger btn-sm btn-icon icon-left" onclick="Confirm('+e[model]["user_code"]+',-1)">拒绝</a></td> </tr>');
                                    }
                                }
                        }
            }
        });
    }
    </script>
    <div class="page-title">

        <div class="title-env">
            <h1 class="title">用户开户审核</h1>
            <p class="description">提供开户审核的功能</p>
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

                    <strong>用户开户审核</strong>
                    <button onclick="Test()">测试</button>

                </li>
            </ol>

            </div>
        </div>


<div class="tabs-vertical-env">
                            
                                <ul class="nav tabs-vertical">
                                    <li class="active">
                                        <a href="#web" data-toggle="tab">
                                            <i class="fa-globe visible-xs"></i>
                                            <span class="hidden-xs">已通过</span>
                                        </a>
                                    </li>

                                    <li class="">
                                        <a href="#images" data-toggle="tab">
                                            <i class="fa-picture visible-xs"></i>
                                            <span class="hidden-xs">未通过</span>
                                        </a>
                                    </li>

                                     <li class="">
                                        <a href="#users" data-toggle="tab">
                                            <i class="fa-picture visible-xs"></i>
                                            <span class="hidden-xs">待审核</span>
                                        </a>
                                    </li>

                                </ul>
                                
                                <div class="tab-content">
                                    



                                    <!-- Sample Search Results Tab -->
                                    <div class="tab-pane active" id="web">
                                        
                                         <div class="panel panel-color panel-white"><!-- Add class "collapsed" to minimize the panel -->
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>已通过</strong></h3>

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
                                                            

                                                            <th>昵称</th>
                                                            <th>真实姓名</th>
                                                            <th>真实MT4</th>
                                                            <th>模拟MT4</th>
                                                            <th>证件正面</th>
                                                            <th>证件反面</th>
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
                                       <div class="tab-pane active" id="web">
                                        
                                         <div class="panel panel-color panel-white"><!-- Add class "collapsed" to minimize the panel -->
        <div class="panel-heading">
            <h3 class="panel-title"><strong>未通过</strong></h3>

            <div class="panel-options">
                <a href="#">
                    <i class="linecons-cog"></i>
                </a>

                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">–</span>
                    <span class="expand-icon">+</span>
                </a>

                <a href="#" data-toggle="reload">
                    <i class="fa-rotate-right"></i>
                </a>

                <a href="#" data-toggle="remove">
                    ×
                </a>
            </div>
        </div>

        <div class="panel-body">

            <table class="table table-model-2 table-hover" id="example-3">
                <thead>
                <tr>
                <th class="no-sorting">
                                                            <input type="checkbox" class="cbr">
                                                            </th>
                      <th>昵称</th>
                      <th>真实姓名</th>
                    <th>真实MT4</th>
                    <th>模拟MT4</th>
                    <th>证件正面</th>
                    <th>证件反面</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody id="Tb_1">

                </tbody>
            </table>
            <div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example-2_info" role="status" aria-live="polite">Showing 1 to 10 of 60 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_simple_numbers" id="example-2_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="example-2" tabindex="0" id="example-2_previous"><a href="#">Previous</a></li><li class="paginate_button active" aria-controls="example-2" tabindex="0"><a href="#">1</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">2</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">3</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">4</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">5</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">6</a></li><li class="paginate_button next" aria-controls="example-2" tabindex="0" id="example-2_next"><a href="#">Next</a></li></ul></div></div></div>


        </div>
    </div>
                                        
                                    </div>
                                    </div>
                                                                        <!-- Search Results Tab -->
                                    <div class="tab-pane" id="users">
                                       <div class="tab-pane active" id="users">
                                        
                                         <div class="panel panel-color panel-white"><!-- Add class "collapsed" to minimize the panel -->
        <div class="panel-heading">
            <h3 class="panel-title"><strong>待审核</strong></h3>

            <div class="panel-options">
                <a href="#">
                    <i class="linecons-cog"></i>
                </a>

                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">–</span>
                    <span class="expand-icon">+</span>
                </a>

                <a href="#" data-toggle="reload">
                    <i class="fa-rotate-right"></i>
                </a>

                <a href="#" data-toggle="remove">
                    ×
                </a>
            </div>
        </div>

        <div class="panel-body">

            <table class="table table-model-2 table-hover" id="example-3">
                <thead>
                <tr>
                <th class="no-sorting">
                                                            <input type="checkbox" class="cbr">
                                                            </th>
                    <th>昵称</th>
                    <th>真实姓名</th>
                    <th>真实MT4</th>
                    <th>模拟MT4</th>
                    <th>证件正面</th>
                    <th>证件反面</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody id="Tb_2">

                </tbody>
            </table>
            <div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example-2_info" role="status" aria-live="polite">Showing 1 to 10 of 60 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_simple_numbers" id="example-2_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="example-2" tabindex="0" id="example-2_previous"><a href="#">Previous</a></li><li class="paginate_button active" aria-controls="example-2" tabindex="0"><a href="#">1</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">2</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">3</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">4</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">5</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">6</a></li><li class="paginate_button next" aria-controls="example-2" tabindex="0" id="example-2_next"><a href="#">Next</a></li></ul></div></div></div>


        </div>
    </div>
                                        
                                    </div>
                                    </div>
                                </div>
       
                            </div>




                    
<link rel="stylesheet" href="assets/js/datatables/dataTables.bootstrap.css">
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