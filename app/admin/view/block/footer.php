{if condition="input('param.hisi_iframe') || cookie('hisi_iframe')"}
{else /}
        </div>
    </div>
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
{/if}
<script>
    $(document).ready(function () {
        var open_url = "{:url('Score/getStaData')}";
        $.post(open_url, function(res) {
            var data = res.data;
            // console.log(data);
            if (res.code == 1) {
                $('.yd').html('昨日：'+data[0]['ml_sum']+'/'+data[0]['gl_sum']);
                $('.td').html('今日：'+data[1]['ml_sum']+'/'+data[1]['gl_sum']);
                $('.ym').html('上月：'+data[2]['ml_sum']+'/'+data[2]['gl_sum']);
                $('.tm').html('本月：'+data[3]['ml_sum']+'/'+data[3]['gl_sum']);
            }
        });

        var open_url = "{:url('Score/getScoreList')}";
        $.post(open_url, function(res) {
            var data = res.data;
            // console.log(data);
            if (res.code == 1) {
                $('.mlgl-text').html(data);
            }
        });
    });

    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b1d7bd674571a9b3ef46862485845cc8";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>