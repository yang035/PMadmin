{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<link rel="stylesheet" href="__ADMIN_JS__/viewer/viewer.min.css">
<script src="__ADMIN_JS__/viewer/viewer.min.js"></script>
<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    .layui-progress-text {
        position: relative;
        top: 0px;
         line-height: 18px;
        font-size: 12px;
        color: #666;
    }
    .laytable-cell-1-0-1,.laytable-cell-1-0-4,.laytable-cell-1-0-3 {
        height: auto;
        line-height: 28px;
        padding: 0 15px;
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        box-sizing: border-box;
    }
    .layui-table-box{
        float: left;
    }
    img{
        cursor:pointer
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">选择项目</label>
                    <div class="layui-input-inline box box1">
                    </div>
                    <input id="project_name" type="hidden" name="project_name" value="{$Request.param.project_name}">
                    <input id="project_id" type="hidden" name="project_id" value="{$Request.param.project_id}">
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <select name="p_status" class="field-p_status" type="select" lay-search>
                            {$p_status}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">时间</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-start_time" name="start_time" value="{:input('get.start_time')}" readonly autocomplete="off" placeholder="选择开始日期段">
                    </div>
                </div>
                <div class="layui-inline">
                    <input type="hidden" name="type" value="{$Request.param.type}">
                    <button type="submit" id="submit" class="layui-btn layui-btn-normal">搜索</button>
                </div>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <button onclick="ajax_send(1)" type="button" class="layui-btn layui-btn-primary layui-btn-radius">当日未完成</button>
        <button onclick="ajax_send(2)" type="button" class="layui-btn layui-btn-danger layui-btn-radius">逾期</button>
        <button onclick="ajax_send(3)" type="button" class="layui-btn layui-btn-normal layui-btn-radius">待完成</button>
        <button onclick="ajax_send(4)" type="button" class="layui-btn layui-btn-warm layui-btn-radius">待评定</button>
    </div>
</div>
<table id="table1" class="layui-table" lay-filter="table1"></table>
<script type="text/html" id="oper-col-1">
    <div class="layui-progress" lay-showpercent="true">
        {{#  if(d.realper > d.per){ }}
        <div class="layui-progress-bar" lay-percent="{{ d.realper }}%"></div>
        {{#  }else{ }}
        <div class="layui-progress-bar layui-bg-red" lay-percent="{{ d.realper }}%"></div>
        {{#  } }}
    </div>
</script>
<script type="text/html" id="oper-col-2">
        {{#  if(d.child == 1){ }}
        <a lay-event="read" class="layui-btn layui-btn-warm layui-btn-xs">
            {{#  if(d.status == 0 && type == 1){ }}
            阶段成果
            {{#  }else if(d.status == 0 && type == 2){ }}
            查看成果
            {{#  }else{ }}
            查看成果
            {{#  } }}
        </a>
        {{#  }else{ }}
        <a lay-event="read" class="layui-btn layui-btn-normal layui-btn-xs">
            {{#  if(d.status == 0 && type == 1){ }}
            汇报
            {{#  }else if(d.status == 0 && type == 2){ }}
            查看汇报
            {{#  }else{ }}
            查看汇报
            {{#  } }}
        </a>
        {{#  } }}
    {{#  if(d.u_res == 'a'){ }}
    <span style="color: red;">已确认</span>
    {{#  }else{ }}
    <div class="layui-btn-group" onclick="accept_task({{ d.id }},type)">
        <a class="layui-btn layui-btn-normal layui-btn-xs">确认</a>
    </div>
    {{#  } }}
    {{#  if(d.status == 1 && type == 2 && d.child == 0){ }}
<!--            <div class="layui-btn-group" onclick="check_result({{ d.id }},'{{ d.name }}')">-->
<!--                <a class="layui-btn layui-btn-normal layui-btn-xs">审核</a>-->
<!--            </div>-->
    {{#  if(d.realper >= 100 && d.real_score == 0){ }}
    <div class="layui-btn-group" onclick="add_score({{ d.id }},'{{ d.code }}','{{ d.name.replace(/<[^>]+>/g,\'\') }}')">
        <a class="layui-btn layui-btn-normal layui-btn-xs">评分</a>
    </div>
    {{#  }else if(d.realper < 100){ }}
    <span style="color: green;">待完成</span>
    {{#  }else{ }}
    <span style="color: red;">已评定</span>
    {{#  } }}
    {{#  } }}
</script>

<script>
    var  project_id=$("input[name='project_id']").val();
    var  start_time=$("input[name='start_time']").val();
    var  end_time=$("input[name='end_time']").val();
    var  atype=$("input[name='type']").val();
    var  p_status=$("select[name='p_status']").val();
    if (typeof(project_id) == 'undefined'){
        project_id = 0;
    }
    var _url = "{:url('admin/project/mytask')}?project_id="+project_id+"&start_time="+start_time+"&end_time="+end_time+"&type="+atype+"&p_status="+p_status;
    var type = "{$Request.param.type}";
    layui.use(['layer', 'table','element', 'laydate','upload'], function () {
        var $ = layui.jquery,laydate = layui.laydate, upload = layui.upload;
        var element = layui.element;
        var table = layui.table;
        var layer = layui.layer;
        laydate.render({
            elem: '.field-start_time',
            trigger: 'click',
        });
        laydate.render({
            elem: '.field-end_time',
            range: true,
            trigger: 'click',
        });

        // 渲染表格
        var renderTable = function () {
            layer.load(2);
            element.render();
            table.render({
                elem: '#table1',
                url: _url,
                page: true,
                limit: 30,
                text: {
                    none : '暂无相关数据'
                },
                cols: [[
                    {field: 'xuhao', title: '序号',type: 'numbers'},
                    {field: 'project_name',merge: true, title: '项目名',width: 100},
                    {field: 'name', title: '任务名',width: 250},
                    {field: 'start_time', title: '开始时间',width: 110},
                    {field: 'end_time', title: '结束时间',width: 110},
                    {field: 'score', title: '计划产量(斗)',width: 70},
                    {field: 'real_score', title: '实际产量(斗)',width: 70,templet:function (d) {
                            return "<span class='red'>"+d.real_score+"</span>";
                        }},
                    // {field: 'grade', title: '紧急度',width: 70},
                    // {field: 'deal_user', title: '参与人'},
                    {field: 'manager_user', title: '负责人',width: 80},
                    // {field: 'send_user', title: '审批人',width: 80},
                    {title: '成果展示',width: 300,templet:function (d) {
                            var t = '';
                            if (d.report){
                                $.each(d.report,function(index,value){
                                    var n = parseInt(index)+1;
                                    if (n > 1){
                                        t += '<br>';
                                    }
                                    t += '('+ n +')'+value.mark;
                                    if (value.attachment.length > 0){
                                        t += '<ul class="liulan">';
                                        $.each(value.attachment,function(i,v){
                                            var m = parseInt(i)+1;
                                            if (v.is_img) {
                                                t += '<img data-original="'+v.path+'" src="/upload/anli.png" style="width: 30px;height: 30px">  ';
                                            }else {
                                                t += '<a target="_blank" href="'+v.path+'" style="color: red">'+v.path.split('.').pop()+m+'</a>,';
                                            }

                                        });
                                        t += '</ul>';
                                    }

                                    if (value.reply.length > 0){
                                        t += '<br>';
                                        $.each(value.reply,function(k,val){
                                            t += '意见：<font style="color: blue">'+val.content+'</font>';
                                        });
                                    }

                                });
                            }
                            return t;
                        }},
                    {field: 'realper', title: '完成情况',width: 70, templet:'#oper-col-1'},
                    {templet: '#oper-col-2', title: '操作',width: 200,}
                ]],
                done: function () {
                    element.render();
                    $('.liulan').viewer({
                        url: 'data-original',
                    });
                    layer.closeAll('loading');
                }
            });
        };

        renderTable();

        //监听工具条
        table.on('tool(table1)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            var id=data.id,pid=data.pid,code=data.code,pname=data.name,pscore=data.score,project_name=data.project_name,child=data.child;

            if (layEvent === 'read') {
                var open_url = "{:url('editTask')}?id="+id+"&pid="+pid+"&type="+type+"&child="+child+"&project_name="+project_name;
                window.location.href = open_url;
            } else if (layEvent === 'add') {
                var reg = /<[^>]+>/g;
                if (reg.test(data.send_user)){
                    var open_url = "{:url('add')}?id="+id+"&atype="+atype;
                    window.location.href = open_url;
                } else {
                    layer.alert('此项目或计划未审批，暂不能添加');
                }
            } else if (layEvent === 'edit') {
                if (0 == pid){
                    layer.alert('项目信息不能在计划中修改');
                } else {
                    var open_url = "{:url('edit')}?id="+id+"&atype="+atype;
                    window.location.href = open_url;
                }
            }
        });
    });

    function add_score(id,code,pname){
        var open_url = "{:url('addScore')}?id="+id+"&code="+code+"&pname="+pname;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'评分',
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function ajax_send(p_status){
        $("select[name='p_status']").val(p_status);
        $("#submit").click();
    }
    function accept_task(id,type) {
        var open_url = "{:url('setConfirm')}?id="+id+"&type="+type;
        $.post(open_url, function(res) {
            if (res.code == 1) {
                layer.msg(res.msg);
                location.reload();
            }else {
                layer.msg(res.msg);
                location.reload();
            }
        });
    }

    function finish_task(id,type) {
        var open_url = "{:url('setStatus')}?id="+id+"&type="+type;
        $.post(open_url, function(res) {
            if (res.code == 1) {
                layer.alert(res.msg);
                location.reload();
            }else {
                layer.alert(res.msg);
            }
        });
    }

    function check_result(id,pname){
        var open_url = "{:url('Project/checkResult')}?id="+id+"&pname="+pname;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :pname,
            maxmin: true,
            area: ['900px', '700px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    new SelectBox($('.box1'),{$project_select},function(result){
        if ('' != result.id){
            $('#project_name').val(result.name);
            $('#project_id').val(result.id);
        }
    },{
        dataName:'name',//option的html
        dataId:'id',//option的value
        fontSize:'14',//字体大小
        optionFontSize:'14',//下拉框字体大小
        textIndent:4,//字体缩进
        color:'#000',//输入框字体颜色
        optionColor:'#000',//下拉框字体颜色
        arrowColor:'#D2D2D2',//箭头颜色
        backgroundColor:'#fff',//背景色颜色
        borderColor:'#D2D2D2',//边线颜色
        hoverColor:'#009688',//下拉框HOVER颜色
        borderWidth:1,//边线宽度
        arrowBorderWidth:0,//箭头左侧分割线宽度。如果为0则不显示
        // borderRadius:5,//边线圆角
        placeholder:'输入关键字搜索',//默认提示
        defalut:'{$Request.param.project_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:300,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>

