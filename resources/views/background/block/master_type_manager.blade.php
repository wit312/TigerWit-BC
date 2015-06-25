@extends('background.layout.index')
@section('content')
    <script>
        $(function(){
                Reload();
        });
         function Reload() {
        $.ajax({
            type: 'GET',
            url: '/GetMasterTypeList',
            success: function (e) {
               if(e!=null){
                                //先清空
                                $("#Tb").find("tr").remove();
                            　　for(var model in e){
                                $("#Tb").append('<tr> <td>'+e[model]["name"]+'</td> <td>'+e[model]["type"]+'</td>  <td><a href="#" onclick="Edit('+e[model]["mt4_id"]+')" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a> <a href="#" class="btn btn-danger btn-sm btn-icon icon-left" onclick="Remove('+e[model]["mt4_id"]+')">Delete</a></td> </tr>');
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
            <h1 class="title">高手类型管理</h1>
            <p class="description">提供高手类型管理的功能</p>
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

                    <strong>高手类型管理</strong>
                </li>
            </ol>

            </div>
        </div>
    <div class="panel panel-default panel-border">
        <div class="panel-heading">
            <strong>选项</strong>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="field-3" class="control-label">修改类型：</label>

                <select id="selected" class="form-control" style="width: 300px;">
                    <option value="-1">选择推荐类型</option>
                    <option value="0">短期投资</option>
                    <option value="1">长期投资</option>
                    <option value="2">最多复制</option>
                    <option value="3">最多盈利</option>
                </select>
            </div>
           
        </div>

    </div>

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
                    <th>用户名</th>
                    <th>推荐类型</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody id="Tb">

                </tbody>
            </table>
            <div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example-2_info" role="status" aria-live="polite">Showing 1 to 10 of 60 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_simple_numbers" id="example-2_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="example-2" tabindex="0" id="example-2_previous"><a href="#">Previous</a></li><li class="paginate_button active" aria-controls="example-2" tabindex="0"><a href="#">1</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">2</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">3</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">4</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">5</a></li><li class="paginate_button " aria-controls="example-2" tabindex="0"><a href="#">6</a></li><li class="paginate_button next" aria-controls="example-2" tabindex="0" id="example-2_next"><a href="#">Next</a></li></ul></div></div></div>


        </div>
    </div>
<link rel="stylesheet" href="assets/js/datatables/dataTables.bootstrap.css">

@endsection