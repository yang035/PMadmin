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
            <label class="layui-form-label">合同联系人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="contract_b_user_id">选择合同联系人</button>
                <div id="contract_b_select_id">{$data_info['own_user_id']|default=''}</div>
                <input type="hidden" name="contract_b_user" id="contract_b_user" value="{$data_info['own_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">财务联系人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="finance_b_user_id">选择财务联系人</button>
                <div id="finance_b_select_id">{$data_info['own_user_id']|default=''}</div>
                <input type="hidden" name="finance_b_user" id="finance_b_user" value="{$data_info['own_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目联系人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="subject_b_user_id">选择项目联系人</button>
                <div id="subject_b_select_id">{$data_info['self_user_id']|default=''}</div>
                <input type="hidden" name="subject_b_user" id="subject_b_user" value="{$data_info['self_user']|default=''}">
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

        $('#contract_b_user_id').on('click', function(){
            var contract_b_user = $('#contract_b_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=contract_b&u="+contract_b_user;
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

        $('#finance_b_user_id').on('click', function(){
            var finance_b_user = $('#finance_b_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=finance_b&u="+finance_b_user;
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

        $('#subject_b_user_id').on('click', function(){
            var subject_b_user = $('#subject_b_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=subject_b&u="+subject_b_user;
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