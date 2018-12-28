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
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="项目名称关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">是否完成</label>
                    <div class="layui-input-inline">
                        <select name="status">
                            <option value="0" {if condition="$Request.param.status eq 0"}selected{/if} >进行中</option>
                            <option value="1" {if condition="$Request.param.status eq '1' "}selected{/if} >已完成</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="type" value="{$Request.param.type}">
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
                <th>名称</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>预设分</th>
                <th>实际分</th>
                <th>紧急度</th>
                <th>参与人</th>
                <th>负责人</th>
                <th>审批人</th>
                <th>完成情况</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            {volist name="data_list" id="vo"}
            <tr>
                <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                <td class="font12" title="{$vo['remark']}">
                    <strong class="mcolor">{$vo['name']}</strong>
                </td>
                <td class="font12">{$vo['start_time']}</td>
                <td class="font12">{$vo['end_time']}</td>
                <td class="font12">{$vo['score']}</td>
                <td class="font12">{$vo['real_score']}</td>
                <td class="font12">{$vo['grade']}</td>
                <td class="font12">{$vo['deal_user']}</td>
                <td class="font12">{$vo['manager_user']}</td>
                <td class="font12">{$vo['send_user']}</td>
                <td class="font12" title="昨日计划完成{$vo['per']}%">
                    <div class="layui-progress" lay-showpercent="true">
                        {if condition="$vo['realper'] > $vo['per']"}
                        <div class="layui-progress-bar" lay-percent="{$vo['realper']}%"></div>
                        {else/}
                        <div class="layui-progress-bar layui-bg-red" lay-percent="{$vo['realper']}%"></div>
                        {/if}
                    </div>
                </td>
                <td>
<!--                    暂时屏蔽此功能-->
<!--                        <div class="layui-btn-group">-->
<!--                        <a href="{:url('editTask',['id'=>$vo['id'],'pid'=>$vo['pid'],'type'=>$type])}" class="layui-btn layui-btn-normal layui-btn-xs">汇报</a>-->
<!--                        </div>-->
                    {if condition="$vo['u_res'] eq 'a'"}
                        <span style="color: red;">已确认</span>
                    {else/}
                    <div class="layui-btn-group" onclick="accept_task({$vo['id']},{$Request.param.type})">
                        <a class="layui-btn layui-btn-normal layui-btn-xs">确认</a>
                    </div>
                    {/if}
                    {if condition="($vo['status'] eq 0) && ($Request.param.type eq 2) "}
                    <div class="layui-btn-group" onclick="check_result({$vo['id']},'{$vo['name']}')">
                        <a class="layui-btn layui-btn-normal layui-btn-xs">审核</a>
                    </div>
                    <div class="layui-btn-group" onclick="add_score({$vo['id']},'{$vo['code']}','{$vo['name']}')">
                        <a class="layui-btn layui-btn-normal layui-btn-xs">评分</a>
                    </div>
                    {/if}
                    {if condition="($vo['status'] eq 0) && ($Request.param.type eq 2) "}
                    <div class="layui-btn-group" onclick="finish_task({$vo['id']},{$Request.param.type})">
                        <a class="layui-btn layui-btn-normal layui-btn-xs">完结</a>
                    </div>
                    {elseif condition="$vo['status'] eq 1"}
                        <span style="color: red;">已完结</span>
                    {else/}
                        <span>进行中</span>
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

    function add_score(id,code,pname){
        var open_url = "{:url('Score/add')}?id="+id+"&code="+code+"&pname="+pname;
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
                layer.msg(res.msg);
                location.reload();
            }else {
                layer.msg(res.msg);
                location.reload();
            }
        });
    }

    function check_result(id,pname){
        var open_url = "{:url('Task/checkResult')}?id="+id+"&pname="+pname;
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