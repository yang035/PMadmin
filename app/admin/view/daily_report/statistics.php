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
                    <label class="layui-form-label">真实姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="realname" value="{:input('get.realname')}" placeholder="真实姓名" autocomplete="off" class="layui-input">
                    </div>
                </div>
<!--                <div class="layui-inline">-->
<!--                    <label class="layui-form-label">任务代码</label>-->
<!--                    <div class="layui-input-inline">-->
<!--                        <input type="text" name="project_code" value="{:input('get.project_code')}" placeholder="任务代码" autocomplete="off" class="layui-input">-->
<!--                    </div>-->
<!--                </div>-->
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
                    <th>姓名</th>
                    <th>数量</th>
                </tr>
                </thead>
                <tbody>
                {volist name="data_list" id="vo"}
                <tr>
                    <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                    <td class="font12">
                        <strong class="mcolor">{$vo['realname']}</strong>
                    </td>
                    <td class="font12">{$vo['num']|default=0}</td>
                </tr>
                {/volist}
                </tbody>
            </table>
            {$pages}
        </div>
    </div>
</div>

{include file="block/layui" /}