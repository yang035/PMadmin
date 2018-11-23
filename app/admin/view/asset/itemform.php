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
            <label class="layui-form-label">资产类型</label>
            <div class="layui-input-inline">
                <select name="cat_id" class="field-cat_id" type="select">
                    {$cat_option}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称<span style="color: red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入检查项">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">说明</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="[选填]备注"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">数量<span style="color: red">*</span></label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-amount" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="amount" lay-verify="required" autocomplete="off" placeholder="请输入数量">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">存储人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="manager_user_id">选择存储人</button>(办公器材由谁管理)
                <div id="manager_select_id"></div>
                <input type="hidden" name="manager_user" id="manager_user" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">使用人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="deal_user_id">选择使用人</button>(办公器材由谁使用)
                <div id="deal_select_id"></div>
                <input type="hidden" name="deal_user" id="deal_user" value="">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('role')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
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