<div class="page-toolbar">
    <div class="page-filter fr">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
<!--        <div class="layui-form-item">-->
<!--            <label class="layui-form-label">搜索</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <input type="text" name="q" value="{:input('get.q')}" lay-verify="required" placeholder="公司名称、法人手机号码" autocomplete="off" class="layui-input">-->
<!--            </div>-->
<!--        </div>-->
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
<!--        <a data-href="{:url('status?table=welfare&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>-->
<!--        <a data-href="{:url('status?table=welfare&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>-->
<!--        <a data-href="{:url('del?table=admin_company')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<form id="pageListForm">
<div class="layui-form">
    <table class="layui-table mt10" lay-even="" lay-skin="row">
        <colgroup>
            <col width="50">
        </colgroup>
        <thead>
            <tr>
                <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
                <th style="width: 70px">目标值比例范围(%)</th>
                <th>奖励百分比(%)</th>
                <th>带薪年假(天)</th>
                <th>旅游金(元)</th>
                <th>设备折旧率(‰)</th>
                <th>职业技能培训费(‰)</th>
                <th>专家就业指导费(‰)</th>
                <th>损差补偿(‰)</th>
                <th>社保</th>
                <th>公积金</th>
                <th>个人意外险</th>
                <th>家人幸福保险</th>
                <th>状态</th>
                <th style="width: 120px">更新时间</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            {volist name="data_list" id="vo"}
            <tr>
                <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                <td class="font12">{$vo['max_num']} > M ≥ {$vo['min_num']}</td>
                <td class="font12">{$vo['prize_ratio']}</td>
                <td class="font12">{$vo['annual_leave']}</td>
                <td class="font12">{$vo['travel_money']}</td>
                <td class="font12">{$vo['davice_use']}</td>
                <td class="font12">{$vo['train_per']}</td>
                <td class="font12">{$vo['job_train']}</td>
                <td class="font12">{$vo['compensation']}</td>
                <td><input type="checkbox" name="social" {if condition="$vo['social'] eq 1"}checked=""{/if} value="{$vo['social']}" lay-skin="switch" lay-filter="switchStatus" lay-text="有|无" data-href="{:url('status?table=welfare&f=social&ids='.$vo['id'])}"></td>
                <td><input type="checkbox" name="accumulation" {if condition="$vo['accumulation'] eq 1"}checked=""{/if} value="{$vo['accumulation']}" lay-skin="switch" lay-filter="switchStatus" lay-text="有|无" data-href="{:url('status?table=welfare&f=accumulation&ids='.$vo['id'])}"></td>
                <td><input type="checkbox" name="accident_insurance" {if condition="$vo['accident_insurance'] eq 1"}checked=""{/if} value="{$vo['accident_insurance']}" lay-skin="switch" lay-filter="switchStatus" lay-text="有|无" data-href="{:url('status?table=welfare&f=accident_insurance&ids='.$vo['id'])}"></td>
                <td><input type="checkbox" name="happy_insurance" {if condition="$vo['happy_insurance'] eq 1"}checked=""{/if} value="{$vo['happy_insurance']}" lay-skin="switch" lay-filter="switchStatus" lay-text="有|无" data-href="{:url('status?table=welfare&f=happy_insurance&ids='.$vo['id'])}"></td>
                <td><input type="checkbox" name="status" {if condition="$vo['status'] eq 1"}checked=""{/if} value="{$vo['status']}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" data-href="{:url('status?table=welfare&ids='.$vo['id'])}"></td>
                <td class="font12">{$vo['update_time']}</td>
                <td>
                    <div class="layui-btn-group">
                        <div class="layui-btn-group">
                        <a href="{:url('edit?id='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm"><i class="layui-icon">&#xe642;</i></a>
<!--                        <a data-href="{:url('del?table=admin_company&ids='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm j-tr-del"><i class="layui-icon">&#xe640;</i></a>-->
                        </div>
                    </div>
                </td>
            </tr>
            {/volist}
        </tbody>
    </table>
    {$pages}
</div>
</form>
{include file="block/layui" /}