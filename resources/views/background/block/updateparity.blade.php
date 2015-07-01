@extends('background.layout.index')
@section('content')
<script type="text/javascript">
    $(function(){
        $("#btn_click").click(function(){
            var parity=$("#parity").val();
            if (parity!=null) {
                $.ajax({
                     type: 'GET',
                            url: '/ChangeParity',
                            data:{parity:parity},
                            success: function (e) {
                                    if (e=='true') {
                                        Myalert('modal-5','操作成功！'); 
                                    }else{
                                        Myalert('modal-5',e); 
                                    }
                            }
                        });
            };
        });
    });
</script>
<div class="page-title">

        <div class="title-env">
            <h1 class="title">修改入金汇率</h1>
            <p class="description">提供修改入金汇率的功能</p>
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

                    <strong>修改入金汇率</strong>
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
                                 <label for="field-3" class="control-label">新汇率值：</label>
                                   <div class="form-group">
                                     <input type="text" id="parity" class="form-control" size="25" placeholder="新汇率" style="width:218px;">
                                   </div>
                                 </div>
                                  <div class="form-group">
                                 <label for="field-3" class="control-label">输入时建议保证汇率的精度</label>
                                 </div>
                                <div class="form-group">
                                  <button id="btn_click" class="btn btn-secondary btn-single" style="width:90px"> 修改 </button>
                                </div>
           
        </div>

    </div>
@endsection('content')
