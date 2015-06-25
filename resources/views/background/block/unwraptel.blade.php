@extends('background.layout.index')
@section('content')
    <script >
       $(function(){
           $('#btn_click').click(function(){
               var data = $("#tel").val();
               if (data != null && data !=undefined) {
                   $.ajax({
                       type: 'GET',
                       url: '/unwrap_phone',
                       data: { 'phone': data},
                       success: function (e) {
                           if (e == 'true') {
                               Myalert('modal-5');
                           } else {
                               //添加失败
                               Myalert('modal-5',e);
                           }
                       }
                   });
               }
           });
       });
    </script>
    <div class="page-title">

        <div class="title-env">
            <h1 class="title">解绑手机号码</h1>
            <p class="description">提供解绑手机号码的功能</p>
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

                    <strong>解绑手机号码</strong>
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
                <label for="field-3" class="control-label">手机号码：</label>

                <input type="text" class="form-control" id="tel" placeholder="Phone">
            </div>
            <button id="btn_click" class="btn btn-blue" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="点击解绑手机号码！" data-original-title="TigerWit-BackGround">解绑</button>
        </div>

    </div>



@endsection