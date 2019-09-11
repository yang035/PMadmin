<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
    }

    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
    .layui-form-mid {
        font-size: 15px!important;
        float: left;
        display: block;
        padding: 9px 0!important;
        line-height: 20px;
        margin-left: 10px;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">姓名</label>
        <div class="layui-form-mid">{$data_info['realname']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">事由</label>
        <div class="layui-form-mid">{$data_info['reason']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">审批人</label>
        <div class="layui-form-mid">{$data_info['send_user1']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-form-mid">{$data_info['status']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">申请时间</label>
        <div class="layui-form-mid">{$data_info['create_time']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">物品明细</label>
        {volist name="$data_info['goods']" id="vo"}
        <div class="layui-form-mid">{$vo['name']}(<span class="free{$i}" style="color: red">{$vo['free']|default=0}</span>)</div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="hidden" class="layui-input field-good_id" name="good_id[]" lay-verify="required" value="{$vo['id']}">
            <input type="number" class="layui-input field-number num{$i}" onblur="blur_fun({$i})" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="number[]" value="{$vo['number']}" lay-verify="required" autocomplete="off" placeholder="数量">
        </div>
        {/volist}
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">存储人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="manager_user_id">选择存储人</button>(办公器材由谁管理)
            <div id="manager_select_id">{$data_info['own_user_id']|default=''}</div>
            <input type="hidden" name="manager_user" id="manager_user" value="{$data_info['own_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">使用人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="deal_user_id">选择使用人</button>(办公器材由谁使用)
            <div id="deal_select_id">{$data_info['realname']|default=''}</div>
            <input type="hidden" name="deal_user" id="deal_user" value="{$data_info['user_id']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-aid" name="aid" value="{$data_info['aid']}">
            <input type="hidden" class="field-id" name="agid" value="{$data_info['id']}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">确认</button>
            <a class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['form'], function() {
        var $ = layui.jquery, form = layui.form;
        if (formData) {
            $('.ass-level').val(parseInt($('.field-pid option:selected').attr('level'))+1);
        }
        $('.layui-btn-primary').click(function () {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        });

        $('#manager_user_id').on('click', function(){
            var manager_user = $('#manager_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=manager&u="+manager_user;
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#deal_user_id').on('click', function(){
            var deal_user = $('#deal_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=deal&u="+deal_user;
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });
    });
    function blur_fun(k) {
        var iv = $('.num'+k).val(),sv = $('.free'+k).text();
        if (iv > sv){
            $('.num'+k).attr('value',sv);
        }
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>