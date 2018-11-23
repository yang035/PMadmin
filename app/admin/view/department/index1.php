<div class="layui-tab-item layui-form menu-dl {if condition="$k eq 1"}layui-show{/if}">
<form class="page-list-form">
    <div class="page-toolbar">
        <div class="layui-btn-group fl">
            <a data-href="{:url('status?table=admin_department&val=1')}" class="layui-btn layui-btn-primary j-page-btns"><i class="aicon ai-qiyong"></i>启用</a>
            <a data-href="{:url('status?table=admin_department&val=0')}" class="layui-btn layui-btn-primary j-page-btns"><i class="aicon ai-jinyong1"></i>禁用</a>
            <a data-href="{:url('del')}" class="layui-btn layui-btn-primary j-page-btns confirm"><i class="aicon ai-jinyong"></i>删除</a>
        </div>
    </div>
    <dl class="menu-dl1 menu-hd mt10">
        <dt>菜单名称</dt>
        <dd>
            <span class="hd2">状态</span>
            <span class="hd3">操作</span>
        </dd>
    </dl>
    {volist name="department_list" id="v" key="k"}
    <dl class="menu-dl1">
        <dt>
            <input type="checkbox" name="ids[{$k}]" value="{$v['id']}" class="checkbox-ids" lay-skin="primary" title="{$v['name']}"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><span>{$v['name']}</span><i class="layui-icon">&#xe626;</i></div>
            <input type="checkbox" name="status" value="{$v['status']}" {if condition="$v['status'] eq 1"}checked=""{/if} lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" data-href="{:url('status?table=admin_department&ids='.$v['id'])}"><div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>{if condition="$v['status'] eq 1"}正常{else /}关闭{/if}</em><i></i></div>
            <div class="menu-btns">
                <a href="{:url('edit?id='.$v['id'].'&code='.$v['code'])}" title="编辑"><i class="layui-icon">&#xe642;</i></a>
                <a href="{:url('add?pid='.$v['id'].'&code='.$v['code'])}" title="添加子菜单"><i class="layui-icon">&#xe654;</i></a>
                <a href="{:url('del?ids='.$v['id'])}" title="删除"><i class="layui-icon">&#xe640;</i></a>
            </div>
        </dt>
        <dd>
            {volist name="v['child']" id="vv" key="kk"}
            <dl class="menu-dl2">
                <dt>
                    <input type="checkbox" name="ids[{$kk}]" value="{$vv['id']}" class="checkbox-ids" lay-skin="primary" title="{$vv['name']}"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><span>{$vv['name']}</span><i class="layui-icon">&#xe626;</i></div>
                    <input type="checkbox" name="status" value="{$vv['status']}" {if condition="$vv['status'] eq 1"}checked=""{/if} lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" data-href="{:url('status?table=admin_department&ids='.$vv['id'])}"><div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>{if condition="$vv['status'] eq 1"}正常{else /}关闭{/if}</em><i></i></div>
                    <div class="menu-btns">
                        <a href="{:url('edit?id='.$vv['id'].'&code='.$vv['code'])}" title="编辑"><i class="layui-icon">&#xe642;</i></a>
                        <a href="{:url('add?pid='.$vv['id'].'&code='.$vv['code'])}" title="添加子菜单"><i class="layui-icon">&#xe654;</i></a>
                        <a href="{:url('del?ids='.$vv['id'])}" title="删除"><i class="layui-icon">&#xe640;</i></a>
                    </div>
                </dt>
                <dd>
                    {php}
                    $kk++;
                    {/php}
                    {volist name="vv['child']" id="vvv" key="kkk"}
                    {php}
                    if ($vvv['name'] == '预留占位') continue;
                    $kk++;
                    {/php}
                    <dl class="menu-dl2">
                        <dt>
                            <input type="checkbox" name="ids[{$kk}]" value="{$vvv['id']}" class="checkbox-ids" lay-skin="primary" title="{$vvv['name']}"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><span>{$vvv['name']}</span><i class="layui-icon">&#xe626;</i></div>
                            <input type="checkbox" name="status" value="{$vvv['status']}" {if condition="$vvv['status'] eq 1"}checked=""{/if} lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" data-href="{:url('status?table=admin_department&ids='.$vvv['id'])}"><div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>{if condition="$vvv['status'] eq 1"}正常{else /}关闭{/if}</em><i></i></div>
                            <div class="menu-btns">
                                <a href="{:url('edit?id='.$vvv['id'].'&code='.$vvv['code'])}" title="编辑"><i class="layui-icon">&#xe642;</i></a>
                                <a href="{:url('add?pid='.$vvv['id'].'&code='.$vvv['code'])}" title="添加子菜单"><i class="layui-icon">&#xe654;</i></a>
                                <a href="{:url('del?ids='.$vvv['id'])}" title="删除"><i class="layui-icon">&#xe640;</i></a>
                            </div>
                        </dt>
                        {php}
                        $kk++;
                        {/php}
                        {volist name="vvv['child']" id="vvvv" key="kkkk"}
                        {php}
                        $kk++;
                        {/php}
                        <dd>
                            <input type="checkbox" name="ids[{$kk}]" value="{$vvvv['id']}" class="checkbox-ids" lay-skin="primary" title="{$vvvv['name']}"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><span>{$vvvv['name']}</span><i class="layui-icon">&#xe626;</i></div>
                            <input type="checkbox" name="status" value="{$vvvv['status']}" {if condition="$vvvv['status'] eq 1"}checked=""{/if} lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" data-href="{:url('status?table=admin_department&ids='.$vvvv['id'])}"><div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>{if condition="$vvvv['status'] eq 1"}正常{else /}关闭{/if}</em><i></i></div>
                            <div class="menu-btns">
                                <a href="{:url('edit?id='.$vvvv['id'].'&code='.$vvvv['code'])}" title="编辑"><i class="layui-icon">&#xe642;</i></a>
                                <a href="{:url('add?pid='.$vvvv['id'].'&code='.$vvvv['code'])}" title="添加子菜单"><i class="layui-icon">&#xe654;</i></a>
                                <a href="{:url('del?ids='.$vvvv['id'])}" title="删除之后无法恢复，您确定要删除吗？" class="j-ajax"><i class="layui-icon">&#xe640;</i></a>
                            </div>
                        </dd>
                        {/volist}
                    </dl>
                    {/volist}
                </dd>
            </dl>
            {php}
            $kk++;
            {/php}
            {/volist}
        </dd>
    </dl>
    {/volist}
</form>
</div>
{include file="block/layui" /}