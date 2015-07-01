@extends('background.layout.index')
@section('content')
<script type="text/javascript">
    $(function(){
        $("#sboxit").selectBoxIt().on('open', function()
        {
            // Adding Custom Scrollbar
            $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
        });
        
         $("#btn_click").click(function(){
               var group = $("#sboxit").val();
               var mt4_id= $("#mt4_id").val();
               var mt4_repeat= $("#mt4_repeat").val();
                if (mt4_id!=null && mt4_repeat!=null && group!=null) {
                     if (mt4_repeat==mt4_id) {
                        $.ajax({
                            type: 'GET',
                            url: '/ChangeGroup',
                            data:{group:group,mt4_id:mt4_id},
                            success: function (e) {
                                    if (e=='true') {
                                        Myalert('modal-5','操作成功！'); 
                                        Reload();
                                    }else{
                                        Myalert('modal-5',e); 
                                    }
                            }
                        });
                     }else{
                        Myalert('modal-5','请填写正确的Mt4 Id！'); 
                     }
               };
         });
    });
</script>
  <div class="page-title">

        <div class="title-env">
            <h1 class="title">修改用户组</h1>
            <p class="description">提供修改用户组的功能</p>
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

                    <strong>修改用户组</strong>
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
                                 <label for="field-3" class="control-label">真实MT4 ID：</label>
                                   <div class="form-group">
                                     <input type="text" id="mt4_id" class="form-control" size="25" placeholder="MT4 ID" style="width:218px;">
                                   </div>
                                 </div>
                                  <div class="form-group">
                                 <label for="field-3" class="control-label">确认MT4 ID：</label>
                                   <div class="form-group">
                                     <input type="text" id="mt4_repeat" class="form-control" size="25" placeholder="Confirm MT4 ID" style="width:218px;">
                                   </div>
                                 </div>
            <div class="form-group">
                <label for="field-3" class="control-label">目标组：</label>
                                <div class="form-group">
                                    <div style="width:218px;">
                                    <select class="form-control" id="sboxit" style="display: none;">
                                    <option value ="BRD">BRD</option>

                                    <option value ="GTBSD">GTBSD</option>

                                    <option value ="STP">STP</option>

                                    <option value ="TBRD-000A">TBRD-000A</option>

                                    <option value ="TBRD-H00A">TBRD-H00A</option>

                                    <option value ="TBRD-H00B">TBRD-H00B</option>

                                    <option value ="TBRD-H10A">TBRD-H10A</option>

                                    <option value ="TBRD-H10B">TBRD-H10B</option>

                                    <option value ="TBRD-H20A">TBRD-H20A</option>

                                    <option value ="TBRD-H20B">TBRD-H20B</option>

                                    <option value ="TBRD-H30A">TBRD-H30A</option>

                                    <option value ="TBRD-H30B">TBRD-H30B</option>

                                    <option value ="TBRD-H40A">TBRD-H40A</option>

                                    <option value ="TBRD-H40B">TBRD-H40B</option>

                                    <option value ="TBRD-H50A">TBRD-H50A</option>

                                    <option value ="TBRD-H50B">TBRD-H50B</option>

                                    <option value ="TBRD-L00A">TBRD-L00A</option>

                                    <option value ="TBRD-L00B">TBRD-L00B</option>

                                    <option value ="TBRD-L10A">TBRD-L10A</option>

                                    <option value ="TBRD-L10B">TBRD-L10B</option>

                                    <option value ="TBRD-L20A">TBRD-L20A</option>

                                    <option value ="TBRD-L20B">TBRD-L20B</option>

                                    <option value ="TBRD-L30A">TBRD-L30A</option>

                                    <option value ="TBRD-L30B">TBRD-L30B</option>

                                    <option value ="TBRD-L40A">TBRD-L40A</option>

                                    <option value ="TBRD-L40B">TBRD-L40B</option>

                                    <option value ="TBRD-L50A">TBRD-L50A</option>

                                    <option value ="TBRD-L50B">TBRD-L50B</option>

                                    <option value ="TBRD-M00A">TBRD-M00A</option>

                                    <option value ="TBRD-M00A-HB">TBRD-M00A-HB</option>

                                    <option value ="TBRD-M00AC">TBRD-M00AC</option>

                                    <option value ="TBRD-M00AC-Tech">TBRD-M00AC-Tech</option>

                                    <option value ="TBRD-M00AL">TBRD-M00AL</option>

                                    <option value ="TBRD-M10A">TBRD-M10A</option>

                                    <option value ="TBRD-M10B">TBRD-M10B</option>

                                    <option value ="TBRD-M20A">TBRD-M20A</option>

                                    <option value ="TBRD-M20B">TBRD-M20B</option>

                                    <option value ="TBRD-M30A">TBRD-M30A</option>

                                    <option value ="TBRD-M30B">TBRD-M30B</option>

                                    <option value ="TBRD-M40A">TBRD-M40A</option>

                                    <option value ="TBRD-M40B">TBRD-M40B</option>

                                    <option value ="TBRD-M50A">TBRD-M50A</option>

                                    <option value ="TBRD-M50B">TBRD-M50B</option>

                                    <option value ="TSTP-000A">TSTP-000A</option>

                                    <option value ="TSTP-H00A">TSTP-H00A</option>

                                    <option value ="TSTP-H00B">TSTP-H00B</option>

                                    <option value ="TSTP-H10A">TSTP-H10A</option>

                                    <option value ="TSTP-H10B">TSTP-H10B</option>

                                    <option value ="TSTP-H20A">TSTP-H20A</option>

                                    <option value ="TSTP-H20B">TSTP-H20B</option>

                                    <option value ="TSTP-H30A">TSTP-H30A</option>

                                    <option value ="TSTP-H30B">TSTP-H30B</option>

                                    <option value ="TSTP-H40A">TSTP-H40A</option>

                                    <option value ="TSTP-H40B">TSTP-H40B</option>

                                    <option value ="TSTP-H50A">TSTP-H50A</option>

                                    <option value ="TSTP-H50B">TSTP-H50B</option>

                                    <option value ="TSTP-L00A">TSTP-L00A</option>

                                    <option value ="TSTP-L00B">TSTP-L00B</option>

                                    <option value ="TSTP-L10A">TSTP-L10A</option>

                                    <option value ="TSTP-L10B">TSTP-L10B</option>

                                    <option value ="TSTP-L20A">TSTP-L20A</option>

                                    <option value ="TSTP-L20B">TSTP-L20B</option>

                                    <option value ="TSTP-L30A">TSTP-L30A</option>

                                    <option value ="TSTP-L30B">TSTP-L30B</option>

                                    <option value ="TSTP-L40A">TSTP-L40A</option>

                                    <option value ="TSTP-L40B">TSTP-L40B</option>

                                    <option value ="TSTP-L50A">TSTP-L50A</option>

                                    <option value ="TSTP-L50B">TSTP-L50B</option>

                                    <option value ="TSTP-M00A">TSTP-M00A</option>

                                    <option value ="TSTP-M00A-HB">TSTP-M00A-HB</option>

                                    <option value ="TSTP-M00AC">TSTP-M00AC</option>

                                    <option value ="TSTP-M00AL">TSTP-M00AL</option>

                                    <option value ="TSTP-M10A">TSTP-M10A</option>

                                    <option value ="TSTP-M10B">TSTP-M10B</option>

                                    <option value ="TSTP-M20A">TSTP-M20A</option>

                                    <option value ="TSTP-M20B">TSTP-M20B</option>

                                    <option value ="TSTP-M30A">TSTP-M30A</option>

                                    <option value ="TSTP-M30B">TSTP-M30B</option>

                                    <option value ="TSTP-M40A">TSTP-M40A</option>

                                    <option value ="TSTP-M40B">TSTP-M40B</option>

                                    <option value ="TSTP-M50A">TSTP-M50A</option>

                                    <option value ="TSTP-M50B">TSTP-M50B</option>

                                    </select>
                                    </div>

                                </div>
                                    <button id="btn_click" class="btn btn-secondary btn-single" style="width:90px"> 修改 </button>
                                
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
@endsection('content')