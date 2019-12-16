{include file="block/layui" /}
<!--<link rel="stylesheet" href="__ADMIN_JS__/viewer/viewer.min.css">-->
<!--<script src="__ADMIN_JS__/viewer/viewer.min.js"></script>-->
<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a:hover{
        cursor:pointer
    }
    .laytable-cell-1-0-1,.laytable-cell-1-0-6 {
        height: auto;
        line-height: 28px;
        padding: 0 15px;
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        box-sizing: border-box;
    }
    img{
        cursor:pointer
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">选择项目</label>
                <div class="layui-input-inline">
                    <select name="project_id" class="field-project_id" type="select" lay-filter="project_type" lay-search="">
                        {$mytask}
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">任务名</label>
                <div class="layui-input-inline">
                    <input type="text" name="content" value="{:input('get.content')}" placeholder="任务名关键字" autocomplete="off" class="layui-input">
                </div>
            </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('addItem')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=assignment_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=assignment_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="user_table"></table>

<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|黑名单" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=assignment_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a onclick="read({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">查看</a>
    <a href="{:url('editItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
    {{#  if(d.p_id){ }}
    <a href="{:url('Project/edittask')}?id={{ d.p_id }}&pid={{ d.p_id }}&type=2&project_name={{ d.project_name }}" class="layui-btn layui-btn-xs layui-btn-danger">结果</a>
    {{#  }else{ }}
    <a href="{:url('Project/addAssignment')}?assignment_id={{ d.id }}&project_id={{ d.project_id }}&name={{ d.content }}" class="layui-btn layui-btn-xs layui-btn-normal">发布</a>
    {{#  } }}

<!--    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/javascript">
    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,page: true //开启分页
            ,limit: 30
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                // {field: 'cat_id', title: '类别',width:80, templet:function(d){
                //         return d.cat.name;
                //     }},
                {field: 'project_name', title: '项目名',width:200,},
                {field: 'content', title: '任务名',width:200, templet:function(d){
                        var open_url = "{:url('editItem')}?id="+d.id;
                        return "<a class='mcolor' href='"+open_url+"'>"+d.content+"</a>";
                }},
                {title: '成果展示',width: 400,templet:function (d) {
                        var t = '';
                        if (d.report){
                            $.each(d.report,function(index,value){
                                var n = parseInt(index)+1;
                                if (n > 1){
                                    t += '<br>';
                                }
                                t += value.real_name+'('+ value.create_time +')  '+value.realper+'%<br>'+value.mark;
                                t += '  <a onclick="open_reply('+ value.id +','+ value.project_id +')" class="layui-btn layui-btn-normal layui-btn-xs">意见</a>';
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
                {title: '执行情况',width:90, templet:function(d){
                        if (d.p_id){
                            return '已下发';
                        } else {
                            return '';
                        }
                    }},
                {title: '操作', templet: '#buttonTpl',width:160},
                {field: 'create_time', title: '添加时间',width:110},
                {field: 'ml', title: 'ML',width:80},
                // {field: 'gl', title: 'GL',width:80},
                {field: 'time_type', title: '日期类型',width:100},
                {field: 'start_time', title: '开始日期',width:110},
                {field: 'end_time', title: '结束日期',width:110},
                {field: 'send_user', title: '发送给',width:110},
                {field: 'deal_user', title: '执行人',width:110},
                {field: 'user_name', title: '添加人',width:80},
            ]],
            done: function () {
                // $('.liulan').viewer({
                //     url: 'data-original',
                // });
                layer.closeAll('loading');
            }
        });

        //监听单元格编辑
        table.on('edit(user_table)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            // layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
            var open_url = "{:url('setKV')}";
            $.post(open_url, {
                t:'assignment_item',
                id:data.id,
                k:field,
                v:value,
            },function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                    // location.reload();
                }else {
                    layer.msg(res.msg);
                    // location.reload();
                }
            });
        });
    });

    function read(id){
        var open_url = "{:url('read')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'详情',
            maxmin: true,
            area: ['800px', '600px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

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