{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<!--<link rel="stylesheet" href="__ADMIN_JS__/viewer/viewer.min.css">-->
<!--<script src="__ADMIN_JS__/viewer/viewer.min.js"></script>-->
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
    .laytable-cell-1-0-1,.laytable-cell-1-0-2,.laytable-cell-1-0-5 {
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
                    <label class="layui-form-label">项目名称</label>
                    <div class="layui-input-inline">
                        <select name="project_id" class="field-project_id" type="select" lay-search>
                            {$subject_item}
                        </select>
                    </div>
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
                    <label class="layui-form-label">结束时间≤</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-start_time" name="start_time" value="{:input('get.start_time')}" readonly autocomplete="off" placeholder="选择结束时间">
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn layui-btn-primary" id="person_user_id">选择人员</button>
                        <div id="person_select_id"></div>
                        <input type="hidden" name="person_user" id="person_user" value="{:input('get.person_user')}">
                    </div>
                </div>
                <div class="layui-inline">
                    <input type="hidden" name="atype" value="{$Request.param.atype}">
                    <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
                </div>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a href="{:url('addTemplate')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">手动计划模板</a>
        <a href="{:url('addTemplate1')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">自动计划模板</a>
    </div>
</div>
<table id="table1" class="layui-table" lay-filter="table1"></table>
<!-- 操作列 -->
<script type="text/html" id="oper-col">
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="read">详情</a>
<!--    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="add">添加计划</a>-->
    {{#  if(d.pid > 0){ }}
    {eq name="$Think.session.admin_user.role_id" value='3'}
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
    {/eq}
    {{#  }else{ }}
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="import">导入</a>
    {{#  } }}
    {{#  if(d.is_period == 2){ }}
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="handout">下发</a>
    {{#  } }}
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
    var  project_id=$("select[name='project_id']").val();
    var  start_time=$("input[name='start_time']").val();
    var  end_time=$("input[name='end_time']").val();
    var  atype=$("input[name='atype']").val();
    var  p_status=$("select[name='p_status']").val();
    var  person_user=$("input[name='person_user']").val();
    var _url = "{:url('admin/project/index')}?project_id="+project_id+"&start_time="+start_time+"&end_time="+end_time+"&atype="+atype+"&p_status="+p_status+"&person_user="+person_user;
    layui.use(['layer', 'table','element','jquery','laydate','upload'], function () {
        var element = layui.element;
        var table = layui.table;
        var layer = layui.layer;

        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload;
        laydate.render({
            elem: '.field-start_time',
            trigger: 'click',
        });
        laydate.render({
            elem: '.field-end_time',
            range: true,
            trigger: 'click',
        });

        $('#person_user_id').on('click', function(){
            var person_user = $('#person_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=person&u="+person_user;
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
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
                    {field: 'xuhao', title: '序号',type: 'numbers'},
                    {field: 'project_name',merge: true, title: '项目名',width: 100},
                    {field: 'name', title: '任务名',width: 250},
                    // {field: 'deal_user', title: '参与人',width: 80},
                    {title: '成果展示',width: 400,templet:function (d) {
                            var t = '';
                            if (d.report){
                                $.each(d.report,function(index,value){
                                    var n = parseInt(index)+1;
                                    if (n > 1){
                                        t += '<br>';
                                    }
                                    t += value.real_name+'('+ value.create_time +')  '+value.realper+'%<br>'+value.mark;
                                    if(1 == value.status){
                                        t += '  <a onclick="open_reply('+ value.id +','+ value.project_id +')" class="layui-btn layui-btn-normal layui-btn-xs">评定</a>';
                                    }
                                    if (value.attachment.length > 0){
                                        t += '<ul class="liulan">';
                                        $.each(value.attachment,function(i,v){
                                            var m = parseInt(i)+1;
                                            if (v.is_img) {
                                                t += '<a href="'+v.path+'" style="color: red"><img src="/upload/anli.png" style="width: 30px;height: 30px"></a>  ';
                                            }else {
                                                t += '<a target="_blank" href="'+v.path+'" style="color: red">'+v.path.split('.').pop()+m+'</a>,';
                                            }

                                        });
                                        t += '</ul>';
                                    }

                                    if (value.reply.length > 0){
                                        t += '<br>';
                                        $.each(value.reply,function(k,val){
                                            t += '意见：<font style="color: blue">'+val.content+'</font>  ';
                                        });
                                    }

                                });
                            }
                            return t;
                        }},
                    {field: 'realper', title: '进度',width: 70, templet:'#oper-col-1'},
                    {field: 'end_time', title: '结束时间',width: 110},
                    {field: 'score', title: '计划斗',width: 80},
                    {field: 'real_score', title: '实际斗',width: 80},
                    {templet: '#oper-col', title: '操作',width: 200,}
                ]],
                done: function () {
                    element.render();
                    // $('.liulan').viewer({
                    //     url: 'data-original',
                    // });
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
                var open_url = "{:url('editTask')}?id="+id+"&pid="+pid+"&type=2"+"&child="+child+"&project_name="+project_name;
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
            }else if (layEvent === 'handout') {
                var open_url = "{:url('addTemplate3')}?id="+id+"&atype="+atype+"&project_name="+project_name;
                window.location.href = open_url;
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
    function open_reply(id,project_id) {
        var open_url = "{:url('ReportReply/add')}?id="+id+"&project_id="+project_id+"&type=2";
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            maxmin: true,
            title :'评价',
            area: ['600px', '400px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                body.contents().find(".field-report_id").val(id);
                body.contents().find(".field-project_id").val(project_id);
            }
        });
    }
</script>

