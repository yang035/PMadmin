{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
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
    .laytable-cell-1-0-4 {
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
    .layui-table, .layui-table-view {
        margin: 45px 0;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">项目名称</label>
                    <div class="layui-input-inline box box1">
                    </div>
                    <input id="project_name" type="hidden" name="project_name" value="{$Request.param.project_name}">
                    <input id="project_id" type="hidden" name="project_id" value="{$Request.param.project_id}">
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">真实姓名</label>
                    <div class="layui-input-inline box box2">
                    </div>
                    <input id="real_name" type="hidden" name="real_name" value="{$Request.param.real_name}">
                    <input id="user_id" type="hidden" name="user_id" value="{$Request.param.user_id}">
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">日期</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-start_time" name="start_time" value="{$start_time}" readonly autocomplete="off" placeholder="选择日期">
                    </div>
                </div>
                <div class="layui-inline">
                    <input type="hidden" name="atype" value="{$Request.param.atype}">
                    <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
                </div>
            </div>
        </form>
    </div>

</div>
<table id="table1" class="layui-table" lay-filter="table1"></table>
<!-- 操作列 -->
<script type="text/html" id="oper-col">
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="read">详情</a>
<!--    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="add">添加计划</a>-->
<!--    {{#  if(d.pid > 0){ }}-->
<!--    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>-->
<!--    {{#  }else{ }}-->
<!--    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="import">导入</a>-->
<!--    {{#  } }}-->
    <!--            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>-->
</script>
<script type="text/html" id="oper-col-1">
    <div class="layui-progress" lay-showpercent="true">
        {{#  if(d.realper > d.per){ }}
        <div class="layui-progress-bar" lay-percent="{{ d.realper }}%"></div>
        {{#  }else{ }}
        <div class="layui-progress-bar layui-bg-red" lay-percent="{{ d.realper }}%"></div>
        {{#  } }}
    </div>
</script>

<script>
    var  project_id=$("input[name='project_id']").val();
    if (typeof(project_id) == 'undefined'){
        project_id = 0;
    }
    var  start_time=$("input[name='start_time']").val();
    var  end_time=$("input[name='end_time']").val();
    var  atype=$("input[name='atype']").val();
    var  p_status=$("select[name='p_status']").val();
    var  person_user=$("input[name='user_id']").val();
    var _url = "{:url('admin/project/dailyreport')}?project_id="+project_id+"&start_time="+start_time+"&end_time="+end_time+"&atype="+atype+"&p_status="+p_status+"&person_user="+person_user;
    layui.use(['layer','element', 'table','laydate'], function () {
        var $ = layui.jquery,laydate = layui.laydate,table = layui.table,layer = layui.layer,element = layui.element;

        laydate.render({
            elem: '.field-start_time',
            trigger: 'click',
        });

        // 渲染表格
        table.render({
                elem: '#table1',
                url: _url,
                page: true,
                limit: 30,
                text: {
                    none : '暂无相关数据'
                },
                cols: [[
                    {field: 'project_name', title: '项目名',width: 250,templet:function (d) {
                            var open_url = "{:url('DailyReport/read')}?id="+d.id;
                            return "<a class='mcolor' href='"+open_url+"'>"+d.project_name+"</a>";
                    }},
                    {title: '今日汇报',width: 300,templet:function (d) {
                            var t = '';
                            if (d.detail){
                                $.each(d.detail,function(index,value){
                                    var n = parseInt(index)+1;
                                    if (n > 1){
                                        t += '<br>';
                                    }
                                    t += '('+ n +')'+'<br>'+value.content;
                                });
                            }
                            return t;
                        }},
                    {title: '明日计划',width: 300,templet:function (d) {
                            var t = '';
                            if (d.p_detail){
                                $.each(d.p_detail,function(index,value){
                                    var n = parseInt(index)+1;
                                    if (n > 1){
                                        t += '<br>';
                                    }
                                    t += '('+ n +')'+'<br>'+value.plan;
                                });
                            }
                            return t;
                        }},
                    {field: 'user_id', title: '提交人',width: 150},
                    {field: 'create_time', title: '提交时间',width: 110},
                    {templet: '#oper-col', title: '操作',width: 80,}
                ]],
            done: function () {
                element.render();
                layer.closeAll('loading');
            }
            });

        //监听工具条
        table.on('tool(table1)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            // var id=data.id,pid=data.pid,code=data.code,pname=data.name,pscore=data.score;
            var id=data.id,pid=data.pid,code=data.code,pname=data.name,pscore=data.score,project_name=data.project_name,child=data.child;

            if (layEvent === 'del') {
                var open_url = "{:url('del')}?ids="+id;
                layer.confirm('删除之后无法恢复，您确定要删除吗？', {title:false, closeBtn:0}, function(index){
                    $.get(open_url, function(res) {
                        if (res.code == 1) {
                            layer.msg(res.msg);
                            location.reload();
                        }else {
                            layer.msg(res.msg);
                            location.reload();
                        }
                    });
                    layer.close(index);
                });
            }else if (layEvent === 'read') {
                // var open_url = "{:url('read')}?id="+id+"&atype="+atype;
                var open_url = "{:url('DailyReport/read')}?id="+id;
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
            } else if (layEvent === 'dep_auth') {
                var open_url = "{:url('depAuth')}?id=" + id;
                window.location.href = open_url;
            }else if (layEvent === 'import') {
                var reg = /<[^>]+>/g;
                if (reg.test(data.send_user)){
                    var open_url = "{:url('doimport')}?id=" + id;
                    if (open_url.indexOf('?') >= 0) {
                        open_url += '&hisi_iframe=yes';
                    } else {
                        open_url += '?hisi_iframe=yes';
                    }
                    layer.open({
                        type:2,
                        title :'导入',
                        maxmin: true,
                        area: ['800px', '500px'],
                        content: open_url,
                        success:function (layero, index) {
                        }
                    });
                } else {
                    layer.alert('此项目未审批，暂不能导入');
                }
            }
        });
    });

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
        placeholder:'输入关键字',//默认提示
        defalut:'{$Request.param.project_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:200,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });


    new SelectBox($('.box2'),{$user_select},function(result){
        if ('' != result.id){
            $('#real_name').val(result.name);
            $('#user_id').val(result.id);
        }
    },{
        dataName:'realname',//option的html
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
        placeholder:'输入关键字',//默认提示
        defalut:'{$Request.param.real_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:200,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>

