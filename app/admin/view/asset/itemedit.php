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
</style>
<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-title" name="title" lay-verify="required" value="" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">数量</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-number" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="number" value="" lay-verify="required" autocomplete="off" placeholder="数量">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">存储人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="manager_user_id">选择存储人</button>(办公器材由谁管理)
                <div id="manager_select_id">{$data_info['manager_user_id']|default=''}</div>
                <input type="hidden" name="manager_user" id="manager_user" value="{$data_info['manager_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">使用人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="deal_user_id">选择使用人</button>(办公器材由谁使用)
                <div id="deal_select_id">{$data_info['deal_user_id']|default=''}</div>
                <input type="hidden" name="deal_user" id="deal_user" value="{$data_info['deal_user']|default=''}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

        $('#reset_expire').on('click', function(){
            $('input[name="expire_time"]').val(0);
        });

        $('#good_cat_id').on('click', function(){
            var good_cat = $('#good_cat').val();
            var open_url = "{:url('Tool/getTreeGood')}?m=good&u="+good_cat;
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'物品列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
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
</script>
<script src="__ADMIN_JS__/footer.js"></script>