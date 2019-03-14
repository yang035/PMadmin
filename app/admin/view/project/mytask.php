{include file="block/layui" /}
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
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">项目名称</label>
                    <div class="layui-input-inline">
                        <select name="project_id" class="field-project_id" type="select">
                            {$subject_item}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">开始时段</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-start_time" name="start_time" value="{:input('get.start_time')}" readonly autocomplete="off" placeholder="选择开始日期段">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">结束时段</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-end_time" name="end_time" value="{:input('get.end_time')}" readonly autocomplete="off" placeholder="选择结束日期段">
                    </div>
                </div>
                <div class="layui-inline">
                    <input type="hidden" name="type" value="{$Request.param.type}">
                    <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
                </div>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <!--            <a href="{:url('add',['atype'=>$Request.param.atype])}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加项目</a>-->
        <button class="layui-btn" id="btn-expand">全部展开</button>
        <button class="layui-btn" id="btn-fold">全部折叠</button>
        <button class="layui-btn" id="btn-refresh">刷新表格</button>
    </div>
</div>
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
    {{#  if(d.status == 0 && type == 2 && d.child == 0){ }}
<!--            <div class="layui-btn-group" onclick="check_result({{ d.id }},'{{ d.name }}')">-->
<!--                <a class="layui-btn layui-btn-normal layui-btn-xs">审核</a>-->
<!--            </div>-->
    {{#  if(d.realper >= 100 && d.real_score == 0){ }}
    <div class="layui-btn-group" onclick="add_score({{ d.id }},'{{ d.code }}','{{ d.name }}')">
        <a class="layui-btn layui-btn-normal layui-btn-xs">评分</a>
    </div>
    {{#  }else if(d.realper < 100){ }}
    <span style="color: green;">待完成</span>
    {{#  }else{ }}
    <span style="color: red;">已评定</span>
    {{#  } }}
    {{#  } }}
</script>
<table id="table1" class="layui-table" lay-filter="table1"></table>
<script>
    var  project_id=$("select[name='project_id']").val();
    var  start_time=$("input[name='start_time']").val();
    var  end_time=$("input[name='end_time']").val();
    var  atype=$("input[name='type']").val();
    var _url = "{:url('admin/project/mytask')}?project_id="+project_id+"&start_time="+start_time+"&end_time="+end_time+"&type="+atype;
    var type = "{$Request.param.type}";
    layui.config({
        base: '/../../static/js/'
    }).extend({
        treetable: 'treetable-lay/treetable'
    }).use(['layer', 'table','element', 'treetable'], function () {
        var $ = layui.jquery;
        var element = layui.element;
        var table = layui.table;
        var layer = layui.layer;
        var treetable = layui.treetable;

        // 渲染表格
        var renderTable = function () {
            layer.load(2);
            element.render();
            treetable.render({
                treeColIndex: 1,
                treeSpid: 0,
                treeIdName: 'id',
                treePidName: 'pid',
                treeDefaultClose: false,
                treeLinkage: true,
                elem: '#table1',
                url: _url,
                page: false,
                cols: [[
                    {type: 'numbers'},
                    {field: 'name', title: '项目名称',width: 200},
                    {field: 'start_time', title: '开始时间'},
                    {field: 'end_time', title: '结束时间'},
                    {field: 'score', title: '计划产量(斗)',width: 80},
                    {field: 'real_score', title: '实际产量(斗)',width: 80,templet:function (d) {
                            return "<span class='red'>"+d.real_score+"</span>";
                        }},
                    {field: 'grade', title: '紧急度',width: 70},
                    {field: 'deal_user', title: '参与人'},
                    {field: 'manager_user', title: '负责人'},
                    {field: 'send_user', title: '审批人',width: 80},
                    {field: 'realper', title: '完成情况',width: 80, templet:'#oper-col-1'},
                    {templet: '#oper-col-2', title: '操作',width: 200,}
                ]],
                done: function () {
                    element.render();
                    layer.closeAll('loading');
                }
            });
        };

        renderTable();

        $('#btn-expand').click(function () {
            treetable.expandAll('#table1');
        });

        $('#btn-fold').click(function () {
            treetable.foldAll('#table1');
        });

        $('#btn-refresh').click(function () {
            renderTable();
        });

        $('#edt-search').keyup(function () {
            var keyword = $('#edt-search').val();
            var searchCount = 0;
            $('#table1').next('.treeTable').find('.layui-table-body tbody tr td').each(function () {
                $(this).css('background-color', 'transparent');
                var text = $(this).text();
                if (keyword != '' && text.indexOf(keyword) >= 0) {
                    $(this).css('background-color', 'rgba(250,230,160,0.5)');
                    if (searchCount == 0) {
                        treetable.expandAll('#table1');
                        $('html,body').stop(true);
                        $('html,body').animate({scrollTop: $(this).offset().top - 150}, 500);
                    }
                    searchCount++;
                }
            });
            if (keyword == '') {
                layer.msg("请输入搜索内容", {icon: 5});
            } else if (searchCount == 0) {
                layer.msg("没有匹配结果", {icon: 5});
            }
        });

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

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload;
        laydate.render({
            elem: '.field-start_time',
            range: true,
            trigger: 'click',
        });
        laydate.render({
            elem: '.field-end_time',
            range: true,
            trigger: 'click',
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
</script>

