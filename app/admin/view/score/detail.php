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
                <div class="layui-inline">
                    <label class="layui-form-label">任务代码</label>
                    <div class="layui-input-inline">
                        <input type="text" name="project_code" value="{:input('get.project_code')}" placeholder="任务代码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <input type="hidden" name="user" value="{$Request.param.user}">
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
                    <th>员工</th>
                    <th>来源</th>
                    <th>ML加分</th>
                    <th>ML扣分</th>
                    <th>GL加分</th>
                    <th>GL扣分</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {volist name="data_list" id="vo"}
                <tr>
                    <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                    <td class="font12">
                        <strong class="mcolor">{$vo['realname']}</strong>
                    </td>
                    <td class="font12">{$vo['pname']}</td>
                    <td class="font12">{$vo['ml_add_score']}</td>
                    <td class="font12">{$vo['ml_sub_score']}</td>
                    <td class="font12">{$vo['gl_add_score']}</td>
                    <td class="font12">{$vo['gl_sub_score']}</td>
                    <td class="font12">{$vo['create_time']}</td>
                    <td>
                        <div class="layui-btn-group">
                            <div class="layui-btn-group">
<!--                                <a href="{:url('edit?id='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm"><i class="layui-icon">&#xe642;</i></a>-->
<!--                                <a data-href="{:url('del?table=admin_company&ids='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm j-tr-del"><i class="layui-icon">&#xe640;</i></a>-->
                            </div>
                        </div>
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