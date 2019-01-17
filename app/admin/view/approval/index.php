<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="class_type" class="field-class_type" type="select">
                            <option value="0">全部</option>
                            {volist name="panel_type" id="vo"}
                            <option value="{$key}" {eq name="$Request.param.class_type" value="$key"} selected {/eq}>{$vo['title']}</option>
                            {/volist}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">开始时间</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-start_time" name="start_time" value="{:input('get.start_time')}" autocomplete="off" placeholder="选择开始时间">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">结束时间</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-end_time" name="end_time" value="{:input('get.end_time')}" autocomplete="off" placeholder="选择结束时间">
                    </div>
                </div>
                <input type="hidden" name="atype" value="{$Request.param.atype}">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
        <div class="layui-form">
            <table class="layui-table mt10" lay-even="" lay-skin="row">
                <colgroup>
                    <col width="50">
                </colgroup>
                <thead>
                <tr>
                    <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
                    <th>姓名</th>
                    <th>类型</th>
                    <th>审批人</th>
                    <th>添加时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {volist name="data_list" id="vo"}
                <tr>
                    <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                    <td class="font12">
                        <strong class="mcolor">{$vo['user_id']}</strong>
                    </td>
                    <td class="font12">{$panel_type[$vo['class_type']]['title']}</td>
                    <td class="font12">{$vo['send_user']}</td>
                    <td class="font12">{$vo['create_time']}</td>
                    <td class="font12">{$approval_status[$vo['status']]}</td>
                    <td>
                        <div class="layui-btn-group" onclick="approval_read({$vo['id']},{$atype},{$vo['class_type']},'{$panel_type[$vo['class_type']]['title']}')">
                            <a class="layui-btn layui-btn-normal layui-btn-xs">
                                {if condition="($vo['status'] eq 1) && ($Request.param.atype eq 3) "}
                                批示
                                {else/}
                                查看
                                {/if}
                            </a>
                        </div>
                        {if condition="($vo['status'] eq 1) && ($Request.param.atype eq 2) "}
                        <div class="layui-btn-group" onclick="approval_back({$vo['id']})">
                            <a class="layui-btn layui-btn-normal layui-btn-xs">撤销</a>
                        </div>
                        <div class="layui-btn-group" onclick="expense({$vo['id']},'{$panel_type[$vo['class_type']]['title']}')">
                            <a class="layui-btn layui-btn-danger layui-btn-xs">报销</a>
                        </div>
                        {/if}
                    </td>
                </tr>
                {/volist}
                </tbody>
            </table>
            {$pages}
        </div>
    </div>
</div>

{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });


    });

    function approval_read(id,atype,class_type,cls_name){
        var open_url = "{:url('Approval/read')}?id="+id+"&atype="+atype+"&class_type="+class_type;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :cls_name,
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function approval_back(id) {
        var open_url = "{:url('approvalBack')}?id="+id;
        layer.confirm('确定撤销？', {
            btn: ['是','否'] //按钮
        }, function(){
            $.post(open_url, function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                }else {
                    layer.msg(res.msg);
                }
                location.reload();
            });
        });

    }

    function expense(id,cls_name){
        var open_url = "{:url('Approval/expense')}?id="+id+"&class_type=2";
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :cls_name,
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>