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
<!--        <a data-href="{:url('status?table=admin_company&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>-->
<!--        <a data-href="{:url('status?table=admin_company&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>-->
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
                <th style="width: 60px">合伙级别</th>
                <th>合伙系数</th>
                <th>绩效指标</th>
                <th>项目红利</th>
                <th>社保</th>
                <th>公积金</th>
                <th>个人意外险</th>
                <th>带薪年假</th>
                <th>旅游金</th>
                <th>职业技能培训</th>
                <th>设备补贴</th>
                <th  style="width: 120px">添加时间</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            {volist name="data_list" id="vo"}
            <tr>
                <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                <td class="font12">{$vo['name']}</td>
                <td class="font12">{$vo['ratio']}</td>
                <td class="font12">
                    {if condition="$vo['type'] eq 1"}
                    月度
                    {elseif condition="$vo['type'] eq 2"}
                    季度
                    {elseif condition="$vo['type'] eq 3"}
                    办年度
                    {else/}
                    年度
                    {/if}
                    ({$vo['quantity']}%)
                </td>
                <td class="font12">{$vo['bonus']}%</td>
                <td class="font12">
                    {if condition="$vo['social'] eq 1"}
                    有
                    {else/}
                    无
                    {/if}
                </td>
                <td class="font12">
                    {if condition="$vo['accumulation'] eq 1"}
                    有
                    {else/}
                    无
                    {/if}
                </td>
                <td class="font12">
                    {if condition="$vo['accident_insurance'] eq 1"}
                    有
                    {else/}
                    无
                    {/if}
                    </td>
                <td class="font12">{$vo['annual_leave']} 天/年</td>
                <td class="font12">{$vo['travel_money']} 元/年</td>
                <td class="font12">{$vo['train_per']} 元/年</td>
                <td class="font12">{$vo['subsidy']} 元/年</td>
                <td class="font12">{$vo['create_time']}</td>
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