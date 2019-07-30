{include file="block/header" /}
{switch name="$tab_type"}
    {case value="1"}
    {/* 分组切换[有链接] */}
        <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
                {empty name="isparams"}
                {volist name="tab_data['menu']" id="vo"}
                    {if condition="$vo['url'] eq $_admin_menu_current['url'] or (url($vo['url']) eq $tab_data['current'])"}
                    <li class="layui-this">
                    {else /}
                    <li>
                    {/if}
                    {if condition="substr($vo['url'], 0, 4) eq 'http'"}
                        <a href="{$vo['url']}" target="_blank">{$vo['title']}</a>
                    {else /}
                        <a href="{:url($vo['url'])}">{$vo['title']}</a>
                    {/if}
                    </li>
                {/volist}
                {else /}
                {volist name="tab_data['menu']" id="vo"}
                {if condition="(url($vo['url'],$vo['params']) eq $tab_url)"}
                <li class="layui-this">
                    {else /}
                <li>
                    {/if}
                    {if condition="substr($vo['url'], 0, 4) eq 'http'"}
                    <a href="{$vo['url']}" target="_blank">{$vo['title']}</a>
                    {else /}
                    <a href="{:url($vo['url'],$vo['params'])}">{$vo['title']}</a>
                    {/if}
                </li>
                {/volist}
                {/empty}
                <div class="tool-btns">
                    <a href="javascript:location.reload();" title="刷新当前页面" class="aicon ai-shuaxin2 font18"></a>
                    <a href="javascript:;" class="aicon ai-quanping1 font18" id="fullscreen-btn" title="打开/关闭全屏"></a>
                </div>
            </ul>
            <div class="layui-tab-content page-tab-content">
                <div class="layui-tab-item layui-show">
                    {__CONTENT__}
                </div>
            </div>
        </div>
    {/case}
    {case value="2"}
    {/* 分组切换[无链接] */}
        <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
                {volist name="tab_data['menu']" id="vo" key="k"}
                    {if condition="$k eq 1"}
                    <li class="layui-this">
                    {else /}
                    <li>
                    {/if}
                    <a href="javascript:;">{$vo['title']}</a>
                    </li>
                {/volist}
                <div class="tool-btns">
                    <a href="javascript:location.reload();" title="刷新当前页面" class="aicon ai-shuaxin2 font18"></a>
                    <a href="javascript:;" class="aicon ai-quanping1 font18" id="fullscreen-btn" title="打开/关闭全屏"></a>
                </div>
            </ul>
            <div class="layui-tab-content page-tab-content">
                {__CONTENT__}
            </div>
        </div>
    {/case}
    {case value="3"}
    {/* 无需分组切换 */}
        {__CONTENT__}
    {/case}
    {default /}
    {/* 单个分组[无链接] */}
        <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
                <li class="layui-this">
                    <a href="javascript:;" id="curTitle">{$_admin_menu_current['title']}</a>
                </li>
                <div class="tool-btns">
                    <a href="javascript:location.reload();" title="刷新当前页面" class="aicon ai-shuaxin2 font18"></a>
                    <a href="javascript:;" class="aicon ai-quanping1 font18" id="fullscreen-btn" title="打开/关闭全屏"></a>
                </div>
            </ul>
            <div class="layui-tab-content page-tab-content">
                <div class="layui-tab-item layui-show">
                    {__CONTENT__}
                </div>
            </div>
        </div>
{/switch}
<script src="__PUBLIC_JS__/layer/layer.js?v="></script>
<script>
    Date.prototype.Format = function (fmt) { //author: meizz
        var o = {
            "M+": this.getMonth() + 1, //月份
            "d+": this.getDate(), //日
            "H+": this.getHours(), //小时
            "m+": this.getMinutes(), //分
            "s+": this.getSeconds(), //秒
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度
            "S": this.getMilliseconds() //毫秒
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
            if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    };

    function startRequest() {
        $("#date_clock").text(new Date().Format("yyyy-MM-dd HH:mm:ss"));
    }

    $(document).ready(function () {
        setInterval("startRequest()", 1000);
        setRegular(18);//设置每天12点整提醒
    });

    function setRegular(targetHour) {
        var timeInterval, nowTime, nowSeconds, targetSeconds
        nowTime = new Date()
        // 计算当前时间的秒数
        nowSeconds = nowTime.getHours() * 3600 + nowTime.getMinutes() * 60 + nowTime.getSeconds()
        // 计算目标时间对应的秒数
        targetSeconds = targetHour * 3600
        //  判断是否已超过今日目标小时，若超过，时间间隔设置为距离明天目标小时的距离
        timeInterval = targetSeconds > nowSeconds ? targetSeconds - nowSeconds : targetSeconds + 24 * 3600 - nowSeconds
        setTimeout(l_open, timeInterval * 1000)
    }

    function l_open() {
        layer.open({
            type: 1,
            offset: 'auto', //具体配置参考：offset参数项
            content: '<div style="padding: 20px 80px;">美好的一天马上结束了，看看你今天满满的收获吧</div>',
            btn: '关闭',
            btnAlign: 'c', //按钮居中
            shade: 0, //不显示遮罩
            // time: 3000,
            area:['300','200'],
            yes: function () {
                layer.closeAll();
            }
        });
        setTimeout(l_open, 24 * 3600 * 1000)//之后每天调用一次
    }
</script>
{include file="block/footer" /}