<style>
    .header-logo1{
        width: 200px;
        height: 40px;
        text-indent: 30px;
        color: #c2c2c2;
        font-size: 20px;
    }
    .layui-elem-field1{
        margin-bottom: 2px;
        padding: 0;
        border-width: 1px 0px 0px 0px;
        border-style: solid;
    }
</style>
<div class="layui-header" style="z-index:999!important;">
    <div style="height:38px;">
        <div style="text-align: left;margin-left: 265px">
            <span style="color: rgba(255,255,255,.7);font-size: 25px">{$admin_user['company']}</span>
        </div>
    </div>
    <div class="fl header-logo1"><a href="{:url('Assignment/addItem')}">
            <div id="date_clock" style="font-size: 21px;color: rgba(255,255,255,.7);"></div>
            <div id="date_clock_date" style="font-size: 15px;color: rgba(255,255,255,.7);"></div>
        </a></div>
    <div class="fl header-fold"><a href="javascript:;" title="打开/关闭左侧导航" class="aicon ai-caidan" id="foldSwitch"></a></div>
    <ul class="layui-nav fl nobg main-nav">
        {volist name="_admin_menu" id="vo"}
            {if condition="($_admin_menu_parents['pid'] eq $vo['id'] and $ca neq 'plugins/run') or ($ca eq 'plugins/run' and $vo['id'] eq 3)"}
           <li class="layui-nav-item layui-this">
            {else /}
            <li class="layui-nav-item">
            {/if} 
            <a href="javascript:;">{$vo['title']}</a></li>
        {/volist}
    </ul>
    <ul class="layui-nav fr nobg head-info">
        <li class="layui-nav-item"><a href="/" target="_blank" class="aicon ai-ai-home" title="前台"></a></li>
        <li class="layui-nav-item"><a href="http://study.imlgl.com?lg=user-login&&email={$Think.session.admin_user.username}" target="_blank" class="aicon ai-huiyuandengji" title="社区"></a></li>
        <li class="layui-nav-item"><a href="{:url('admin/index/clear')}" class="j-ajax aicon ai-qingchu" refresh="yes" title="清缓存"></a></li>
        <li class="layui-nav-item"><a href="javascript:void(0);" class="aicon ai-suo" id="lockScreen" title="锁屏"></a></li>
        <li class="layui-nav-item">
            <a href="{:url('admin/user/setTheme')}" id="admin-theme-setting" class="aicon ai-theme" title="切换主题颜色"></a>
        </li>
        <li class="layui-nav-item">
            <a href="javascript:void(0);">{$admin_user['username']}&nbsp;&nbsp;</a>
            <dl class="layui-nav-child">
                <dd><a data-id="00" class="admin-nav-item top-nav-item" href="{:url('admin/user/info')}">个人设置</a></dd>
<!--                <dd><a data-id="00" class="admin-nav-item top-nav-item" href="{:url('admin/Score/listPeople',['p'=>1])}">ML发放</a></dd>-->
<!--                <dd><a href="{:url('admin/user/iframe')}" class="j-ajax" refresh="yes">{:input('cookie.hisi_iframe') ? '单页布局' : '框架布局' }</a></dd>-->
                {volist name="languages" id="vo"}
                    {if condition="$vo['pack']"}
                    <dd><a href="{:url('admin/index/index')}?lang={$vo['code']}">{$vo['name']}</a></dd>
                    {/if}
                {/volist}
                <dd><a data-id="000" class="admin-nav-item top-nav-item" href="{:url('admin/language/index')}">语言包管理</a></dd>
                <dd><a href="{:url('admin/publics/logout')}">退出登陆</a></dd>
            </dl>
        </li>
    </ul>
</div>
<div class="layui-side layui-bg-black" id="switchNav">
    <div class="layui-side-scroll">
        {volist name="_admin_menu" id="v"}
        {if condition="($_admin_menu_parents['pid'] eq $v['id'] and $ca neq 'plugins/run') or ($ca eq 'plugins/run' and $v['id'] eq 3)"}
        <ul class="layui-nav layui-nav-tree">
        {else /}
        <ul class="layui-nav layui-nav-tree" style="display:none;">
        {/if}
            {volist name="v['childs']" id="vv" key="kk"}
            <li class="layui-nav-item {if condition="$kk eq 1"}layui-nav-itemed{/if}">
                <a href="javascript:;"><i class="{$vv['icon']}"></i>{$vv['title']}<span class="layui-nav-more"></span></a>
                <dl class="layui-nav-child">
                    {if condition="$vv['title'] eq '快捷菜单'"}
                        <dd><a class="admin-nav-item" data-id="0" href="{:input('cookie.hisi_iframe') ? url('admin/index/welcome') : url('admin/index/index')}"><i class="aicon ai-shouye"></i> 后台首页</a></dd>
                        {volist name="vv['childs']" id="vvv"}
                        <dd><a class="admin-nav-item" data-id="{$vvv['id']}" href="{if condition="strpos('http', $vvv['url']) heq false"}{:url($vvv['url'], $vvv['param'])}{else /}{$vvv['url']}{/if}">{if condition="file_exists('.'.$vvv['icon'])"}<img src="{$vvv['icon']}" width="16" height="16" />{else /}<i class="{$vvv['icon']}"></i>{/if} {$vvv['title']}</a><i data-href="{:url('menu/del?ids='.$vvv['id'])}" class="layui-icon j-del-menu">&#xe640;</i></dd>
                        {/volist}
                    {else /}
                        {volist name="vv['childs']" id="vvv"}
                        <dd><a class="admin-nav-item" data-id="{$vvv['id']}" href="{if condition="strpos('http', $vvv['url']) heq false"}{:url($vvv['url'], $vvv['param'])}{else /}{$vvv['url']}{/if}">{if condition="file_exists('.'.$vvv['icon'])"}<img src="{$vvv['icon']}" width="16" height="16" />{else /}<i class="{$vvv['icon']}"></i>{/if} {$vvv['title']}</a></dd>
                        {/volist}
                    {/if}
                </dl>
            </li>
            {/volist}
        </ul>
        {/volist}
    </div>
    <fieldset class="layui-elem-field1">
        <legend style="color: #48576a;font-size: 10px;">空间使用情况(G)</legend>
    </fieldset>
    <div class="layui-progress layui-progress-big" lay-showPercent="true" style="text-align: right;">
        <div class="layui-progress-bar layui-bg-orange" lay-percent="{$per_space}"></div>
    </div>
</div>
<script type="text/html" id="hisi-theme-tpl">
    <ul class="hisi-themes">
        {volist name=":session('hisi_admin_themes')" id="vo"}
        <li data-theme="{$vo}" class="hisi-theme-item-{$vo}"></li>
        {/volist}
    </ul>
</script>