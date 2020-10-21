<style>
    .layui-form-pane .layui-form-label {
        width: 120px;
        padding: 8px 15px;
        height: 38px;
        line-height: 20px;
        border-width: 1px;
        border-style: solid;
        border-radius: 2px 0 0 2px;
        text-align: center;
        background-color: #FBFBFB;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        box-sizing: border-box;
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
        {notempty name="data_info['small_major_deal_arr']"}
        {volist name="data_info['small_major_deal_arr']" id="vo"}
        <fieldset class="layui-elem-field site-demo-button">
            <legend>{$vo['name']}[{$vo['value']}%]</legend>
            <div>
            {volist name="vo['child']" id="v"}
            <div class="layui-form-item">
                <label class="layui-form-label">{$v['name']}</label>
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-normal" id="{$v['id']}_user_id" onclick="open_div1({$v['id']})">选择人员</button>[{$v['value']}%]
                    <div id="{$v['id']}_select_id">{$v['dep_name']|default=''}</div>
                    <input type="hidden" name="{$v['id']}_user" id="{$v['id']}_user" value="{$v['dep']|default=''}">
                </div>
            </div>
            {/volist}
            </div>
        </fieldset>
        {/volist}
        {/notempty}

        <div class="layui-form-item">
            <label class="layui-form-label">总负责人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="leader_user_id" onclick="open_div1('leader')">选择总负责人</button>
                <div id="leader_select_id">{$data_info['leader_user_id']|default=''}</div>
                <input type="hidden" name="leader_user" id="leader_user" value="{$data_info['leader_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">审批人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="send_user_id" onclick="open_div1('send')">选择审批人</button>
                <div id="send_select_id">{$data_info['send_user_id']|default=''}</div>
                <input type="hidden" name="send_user" id="send_user" value="{$data_info['send_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">抄送人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="copy_user_id" onclick="open_div1('copy')">选择抄送人</button>
                <div id="copy_select_id">{$data_info['copy_user_id']|default=''}</div>
                <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['copy_user']|default=''}">
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
    });

    function open_div(flag) {
        var flag_user = $('#'+flag+'_user').val();
        var open_url = "{:url('Tool/getTreeDepartment')}?m="+flag+"&u="+flag_user;
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
    }

    function open_div1(flag) {
        var flag_user = $('#'+flag+'_user').val();
        var open_url = "{:url('Tool/getTreeUser')}?m="+flag+"&u="+flag_user;
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
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>