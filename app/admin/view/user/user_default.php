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
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">默认负责人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="manager_user_id">选择负责人员</button>
            <div id="manager_select_id">{$data_info['manager_user_id']|default=''}</div>
            <input type="hidden" name="manager_user" id="manager_user" value="{$data_info['manager_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认参与人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="deal_user_id">选择参与人员</button>
            <div id="deal_select_id">{$data_info['deal_user_id']|default=''}</div>
            <input type="hidden" name="deal_user" id="deal_user" value="{$data_info['deal_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认审批人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="send_user_id">选择审批人员</button>
            <div id="send_select_id">{$data_info['send_user_id']|default=''}</div>
            <input type="hidden" name="send_user" id="send_user" value="{$data_info['send_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认抄送人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="copy_user_id">选择抄送人员</button>
            <div id="copy_select_id">{$data_info['copy_user_id']|default=''}</div>
            <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['copy_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认财务</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="finance_user_id">选择财务人员</button>
            <div id="finance_select_id">{$data_info['finance_user_id']|default=''}</div>
            <input type="hidden" name="finance_user" id="finance_user" value="{$data_info['finance_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认HR</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="hr_user_id">选择HR人员</button>
            <div id="hr_select_id">{$data_info['hr_user_id']|default=''}</div>
            <input type="hidden" name="hr_user" id="hr_user" value="{$data_info['hr_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认HR和财务</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="hr_finance_user_id">选择HR和财务</button>
            <div id="hr_finance_select_id">{$data_info['hr_finance_user_id']|default=''}</div>
            <input type="hidden" name="hr_finance_user" id="hr_finance_user" value="{$data_info['hr_finance_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认存储人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="own_user_id">选择存储人</button>
            <div id="own_select_id">{$data_info['own_user_id']|default=''}</div>
            <input type="hidden" name="own_user" id="own_user" value="{$data_info['own_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">任务单默认</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="assignment_user_id">选择</button>
            <div id="assignment_select_id">{$data_info['assignment_user_id']|default=''}</div>
            <input type="hidden" name="assignment_user" id="assignment_user" value="{$data_info['assignment_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload;

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

        $('#send_user_id').on('click', function(){
            var send_user = $('#send_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=send&u="+send_user;
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

        $('#copy_user_id').on('click', function(){
            var copy_user = $('#copy_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=copy&u="+copy_user;
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

        $('#finance_user_id').on('click', function(){
            var finance_user = $('#finance_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=finance&u="+finance_user;
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

        $('#hr_user_id').on('click', function(){
            var hr_user = $('#hr_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=hr&u="+hr_user;
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

        $('#hr_finance_user_id').on('click', function(){
            var hr_finance_user = $('#hr_finance_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=hr_finance&u="+hr_finance_user;
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

        $('#own_user_id').on('click', function(){
            var own_user = $('#own_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=own&u="+own_user;
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

        $('#assignment_user_id').on('click', function(){
            var assignment_user = $('#assignment_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=assignment&u="+assignment_user;
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