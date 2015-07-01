@extends('background.layout.index')
@section('content')
            <div class="page-title">
                
                <div class="title-env">
                    <h1 class="title">用户管理</h1>
                    <p class="description">管理用户信息</p>
                </div>
                
                    <div class="breadcrumb-env">
                    
                                <ol class="breadcrumb bc-1">
                                    <li>
                                        <a href="/BackGround"><i class="fa-home"></i>首页</a>
                                    </li>
                                    <li class="active">
                                            <strong>用户管理</strong>
                                    </li>
                                </ol>
                                
                </div>
                    
            </div>

            <!-- Basic Setup -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">用户信息</h3>
                    
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
                    var oTable;
                    jQuery(document).ready(function($)
                    {
                        initModal();
                        oTable = initTable();
                        $("#btnEdit").hide();
                        $("#btnSave").click(_addFun);
                        $("#btnEdit").click(_editFunAjax);
                        $("#deleteFun").click(_deleteList);
                        //checkbox全选
                        $("#checkAll").on("click", function () {
                            if ($(this).attr("checked") === "checked") {
                                $("input[name='checkList']").attr("checked", $(this).attr("checked"));
                            } else {
                                $("input[name='checkList']").attr("checked", false);
                            }
                        });
                    });
 
                    /**
                    * 表格初始化
                    * @returns {*|jQuery}
                    */
                    function initTable() {
                        var table = $("#table-1").dataTable({
                            //"iDisplayLength":10,
                            "sAjaxSource": "http://dt.thxopen.com/table-1/resources/user_share/basic_curd/dataList.php",
                            'bPaginate': true,
                            "bDestory": true,
                            "bRetrieve": true,
                            "bFilter":false,
                            "bSort": false,
                            "bProcessing": true,
                            "aoColumns": [
                                {
                                    "mDataProp": "id",
                                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                                        $(nTd).html("<input type='checkbox' name='checkList' value='" + sData + "'>");
                         
                                    }
                                },
                                {"mDataProp": "name"},
                                {"mDataProp": "password"},
                                {"mDataProp": "phone"},
                                {"mDataProp": "email"},
                                {
                                    "mDataProp": "id",
                                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                                        $(nTd).html("<a href='javascript:void(0);' " +
                                        "onclick='_editFun(" + oData + ")'>编辑</a>&nbsp;&nbsp;")
                                            .append("<a href='javascript:void(0);' onclick='_deleteFun(" + sData + ")'>删除</a>");
                                    }
                                },
                            ],
                            "fnInitComplete": function (oSettings, json) {
                                $('<a href="#myModal" id="addFun" class="btn btn-primary" data-toggle="modal">新增</a>' + '&nbsp;' +
                                '<a href="#" class="btn btn-primary" id="editFun">修改</a> ' + '&nbsp;' +
                                '<a href="#" class="btn btn-danger" id="deleteFun">删除</a>' + '&nbsp;').appendTo($('.myBtnBox'));
                                $("#deleteFun").click(_deleteList);
                                $("#editFun").click(_value);
                                $("#addFun").click(_init);
                            }
                        });
                        return table;
                    }
                     
                    /**
                    * 删除
                    * @param id
                    * @private
                    */
                    function _deleteFun(id) {
                    $.ajax({
                        url: "http://dt.thxopen.com/table-1/resources/user_share/basic_curd/deleteFun.php",
                        data: {"id": id},
                        type: "post",
                        success: function (backdata) {
                            if (backdata) {
                                oTable.fnReloadAjax(oTable.fnSettings());
                            } else {
                                alert("删除失败");
                            }
                        }, error: function (error) {
                            console.log(error);
                        }
                    });
                    }
                     
                    /**
                    * 赋值
                    * @private
                    */
                    function _value() {
                    if (oTable.$('tr.row_selected').get(0)) {
                        $("#btnEdit").show();
                        var selected = oTable.fnGetData(oTable.$('tr.row_selected').get(0));
                        $("#inputName").val(selected.name);
                        $("#inputJob").val(selected.job);
                        $("#inputDate").val(selected.date);
                        $("#inputNote").val(selected.note);
                        $("#objectId").val(selected.id);
                     
                        $("#myModal").modal("show");
                        $("#btnSave").hide();
                    } else {
                        alert('请点击选择一条记录后操作。');
                    }
                    }
                     
                    /**
                    * 编辑数据带出值
                    * @param id
                    * @param name
                    * @param job
                    * @param note
                    * @private
                    */
                    function _editFun(iData) {
                        $("#inputName").val(iData.name);
                        $("#inputJob").val(iData.job);
                        $("#inputNote").val(iData.note);
                        $("#objectId").val(iData.id);
                        $("#myModal").modal("show");
                        $("#btnSave").hide();
                        $("#btnEdit").show();
                    }
                     
                    /**
                    * 初始化
                    * @private
                    */
                    function _init() {
                        resetFrom();
                        $("#btnEdit").hide();
                        $("#btnSave").show();
                    }
                     
                    /**
                    * 添加数据
                    * @private
                    */
                    function _addFun() {
                    var jsonData = {
                        'name': $("#inputName").val(),
                        'job': $("#inputJob").val(),
                        'note': $("#inputNote").val()
                    };
                    $.ajax({
                        url: "http://dt.thxopen.com/table-1/resources/user_share/basic_curd/insertFun.php",
                        data: jsonData,
                        type: "post",
                        success: function (backdata) {
                            if (backdata == 1) {
                                $("#myModal").modal("hide");
                                resetFrom();
                                oTable.fnReloadAjax(oTable.fnSettings());
                            } else if (backdata == 0) {
                                alert("插入失败");
                            } else {
                                alert("防止数据不断增长，会影响速度，请先删掉一些数据再做测试");
                            }
                        }, error: function (error) {
                            console.log(error);
                        }
                    });
                    }
                     
                    /**
                    * 编辑数据
                    * @private
                    */
                    function _editFunAjax() {
                        var id = $("#objectId").val();
                        var name = $("#inputName").val();
                        var job = $("#inputJob").val();
                        var note = $("#inputNote").val();
                        var jsonData = {
                            "id": id,
                            "name": name,
                            "job": job,
                            "note": note
                        };
                        $.ajax({
                            type: 'POST',
                            url: 'http://dt.thxopen.com/table-1/resources/user_share/basic_curd/editFun.php',
                            data: jsonData,
                            success: function (json) {
                                if (json) {
                                    $("#myModal").modal("hide");
                                    resetFrom();
                                    oTable.fnReloadAjax(oTable.fnSettings());
                                } else {
                                    alert("更新失败");
                                }
                            }
                        });
                    }
                    /**
                    * 初始化弹出层
                    */
                    function initModal() {
                    $('#myModal').on('show', function () {
                        $('body', document).addClass('modal-open');
                        $('<div class="modal-backdrop fade in"></div>').appendTo($('body', document));
                    });
                    $('#myModal').on('hide', function () {
                        $('body', document).removeClass('modal-open');
                        $('div.modal-backdrop').remove();
                    });
                    }
                     
                    /**
                    * 重置表单
                    */
                    function resetFrom() {
                    $('form').each(function (index) {
                        $('form')[index].reset();
                    });
                    }
                     
                     
                    /**
                    * 批量删除
                    * 未做
                    * @private
                    */
                    function _deleteList() {
                    var str = '';
                    $("input[name='checkList']:checked").each(function (i, o) {
                        str += $(this).val();
                        str += ",";
                    });
                    if (str.length > 0) {
                        var IDS = str.substr(0, str.length - 1);
                        alert("你要删除的数据集id为" + IDS);
                    } else {
                        alert("至少选择一条记录操作");
                    }
                    }
                    </script>
                    
                    <table id="table-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Name1</th>
                                <th>Name2</th>
                                <th>Name3</th>
                                <th>Name4</th>
                                <th>Name5</th>
                            </tr>
                        </thead>
                    
                       <!--  <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Age</th>
                                <th>Start date</th>
                                <th>Salary</th>
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