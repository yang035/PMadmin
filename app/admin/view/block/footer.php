{if condition="input('param.hisi_iframe') || cookie('hisi_iframe')"}
</body>
</html>
{else /}
        </div>
    </div>
<link rel="stylesheet" href="__ADMIN_JS__/roll/roll.css">
    <div class="layui-footer footer">
        <div class="notice-title">公告：</div>
        <div class="notice-content">
            <div class="notice-text">
                {notempty name="notice"}
                    {volist name="notice" id="vo"}
                <a href="{:url('Notice/read',['id'=>$key])}"><span style="color: red">{$vo}</span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {/volist}
                {else/}
                    暂无公告
                {/notempty}
            </div>
        </div>
    </div>
<script src="__ADMIN_JS__/roll/roll.js"></script>
</div>
</body>
</html>
{/if}