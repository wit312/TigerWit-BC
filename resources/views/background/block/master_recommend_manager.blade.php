@extends('background.layout.index')
@section('content')

         <script>

      $(function () {
	  Reload();
          $("#Btn_Sub").click(function () {
              var data = $("#MT4ID").val();
              var type = $("#selected").val();
                      if (data != null && data != undefined && type != null && type != undefined && type != -1) {
                          $.ajax({
                              type: 'GET',
                              url: '/AddMasterList',
                              data: { 'data': data ,'type':type},
                              success: function (e) {
                                 if(e=='true') {
                                     Myalert('modal-5');
                                     //异步刷新
                                     Reload();
                                 }else {
                                     Myalert('modal-5','操作失败:'+e);
                                 }
                                }
              });
              }
          });
      });

	  function Reload() {
        $.ajax({
            type: 'GET',
            url: '/GetMasterList',
            success: function (e) {
               if(e!=null){
								//先清空
								$("#Tb").find("tr").remove();
							　　for(var model in e){
								$("#Tb").append('<tr> <td>'+e[model]["name"]+'</td> <td>'+e[model]["desc"]+'</td>  <td><a href="#" class="btn btn-danger btn-sm btn-icon icon-left" onclick="Remove('+e[model]["mt4_id"]+')">Delete</a></td> </tr>');
								}
						}
            }
        });
    }

	function Remove(param){
		 $.ajax({
            type: 'GET',
            url: '/RemoveMasterList?id=' + param,
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

    </script>




         <div class="page-title">

             <div class="title-env">
                 <h1 class="title">高手推荐管理</h1>
                 <p class="description">管理高手推荐列表</p>
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

                         <strong>高手推荐管理</strong>
                     </li>
                 </ol>

             </div>

         </div>
<div class="panel panel-default panel-border">
						<div class="panel-heading">
                            <strong>选项</strong>
						</div>
						<div class="panel-body">
									</div>
    <div class="panel-body">

        <h4><div class="label label-info">M T 4 I D ：</div></h4>

        <div class="vertical-top">

            <input type="text" style="width:250px;"  class="form-control" id="MT4ID" value="点击添入mt4id..." onfocus="if(value == '点击添入mt4id...') { value = '' }" onblur="if (value == '') { value = '点击添入mt4id...'}">

        </div>

        <h4><div class="label label-info">推荐 类型 ：</div></h4>

        <div class="vertical-top">

            <select class="form-control" id="selected" style="width:250px;">
                <option value="-1">选择推荐类型</option>
                <option value="0">短期投资</option>
                <option value="1">长期投资</option>
                <option value="2">最多复制</option>
                <option value="3">最多盈利</option>
            </select>

        </div>

        <h4> &nbsp;</h4>

        <div class="vertical-top">

            <button class="btn btn-info btn-block" id="Btn_Sub" style="width:100px;">提交</button>

        </div>
						</div>

</div>


					<div class="panel panel-color panel-white"><!-- Add class "collapsed" to minimize the panel -->
						<div class="panel-heading">
							<h3 class="panel-title"><strong>高手推荐列表</strong></h3>

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

														<table class="table table-model-2 table-hover">
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



						</div>
					</div>

@endsection