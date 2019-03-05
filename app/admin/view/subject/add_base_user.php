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
        <div class="layui-form-item">
            <label class="layui-form-label">项目负责人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="manager_user_id" onclick="open_div('manager')">选择项目负责人</button>
                <div id="manager_select_id">{$data_info['manager_user_id']|default=''}</div>
                <input type="hidden" name="manager_user" id="manager_user" value="{$data_info['manager_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">主创设计师</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="designer_user_id" onclick="open_div('designer')">选择主创设计师</button>
                <div id="designer_select_id">{$data_info['designer_user_id']|default=''}</div>
                <input type="hidden" name="designer_user" id="designer_user" value="{$data_info['designer_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">方案负责人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="fangan_user_id" onclick="open_div('fangan')">选择方案负责人</button>
                <div id="fangan_select_id">{$data_info['fangan_user_id']|default=''}</div>
                <input type="hidden" name="fangan_user" id="fangan_user" value="{$data_info['fangan_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">方案校对人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="fangan_jd_user_id" onclick="open_div('fangan_jd')">选择方案校对人</button>
                <div id="fangan_jd_select_id">{$data_info['fangan_jd_user_id']|default=''}</div>
                <input type="hidden" name="fangan_jd_user" id="fangan_jd_user" value="{$data_info['fangan_jd_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">方案审核人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="fangan_sh_user_id" onclick="open_div('fangan_sh')">选择方案审核人</button>
                <div id="fangan_sh_select_id">{$data_info['fangan_sh_user_id']|default=''}</div>
                <input type="hidden" name="fangan_sh_user" id="fangan_sh_user" value="{$data_info['fangan_sh_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">方案终审人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="fangan_zs_user_id" onclick="open_div('fangan_zs')">选择方案终审人</button>
                <div id="fangan_zs_select_id">{$data_info['fangan_zs_user_id']|default=''}</div>
                <input type="hidden" name="fangan_zs_user" id="fangan_zs_user" value="{$data_info['fangan_zs_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">施工图负责人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="shigong_user_id" onclick="open_div('shigong')">选择施工图负责人</button>
                <div id="shigong_select_id">{$data_info['shigong_user_id']|default=''}</div>
                <input type="hidden" name="shigong_user" id="shigong_user" value="{$data_info['shigong_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图建校对人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="yuanjian_jd_user_id" onclick="open_div('yuanjian_jd')">选择图建校对人</button>
                <div id="yuanjian_jd_select_id">{$data_info['yuanjian_jd_user_id']|default=''}</div>
                <input type="hidden" name="yuanjian_jd_user" id="yuanjian_jd_user" value="{$data_info['yuanjian_jd_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图建审核人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="yuanjian_sh_user_id" onclick="open_div('yuanjian_sh')">选择图建审核人</button>
                <div id="yuanjian_sh_select_id">{$data_info['yuanjian_sh_user_id']|default=''}</div>
                <input type="hidden" name="yuanjian_sh_user" id="yuanjian_sh_user" value="{$data_info['yuanjian_sh_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结构校对人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="jiegou_jd_user_id" onclick="open_div('jiegou_jd')">选择结构校对人</button>
                <div id="jiegou_jd_select_id">{$data_info['jiegou_jd_user_id']|default=''}</div>
                <input type="hidden" name="jiegou_jd_user" id="jiegou_jd_user" value="{$data_info['jiegou_jd_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结构审核人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="jiegou_sh_user_id" onclick="open_div('jiegou_sh')">选择结构审核人</button>
                <div id="jiegou_sh_select_id">{$data_info['jiegou_sh_user_id']|default=''}</div>
                <input type="hidden" name="jiegou_sh_user" id="jiegou_sh_user" value="{$data_info['jiegou_sh_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">绿化校对人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="lvhua_jd_user_id" onclick="open_div('lvhua_jd')">选择绿化校对人</button>
                <div id="lvhua_jd_select_id">{$data_info['lvhua_jd_user_id']|default=''}</div>
                <input type="hidden" name="lvhua_jd_user" id="lvhua_jd_user" value="{$data_info['lvhua_jd_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">绿化审核人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="lvhua_sh_user_id" onclick="open_div('lvhua_sh')">选择绿化审核人</button>
                <div id="lvhua_sh_select_id">{$data_info['lvhua_sh_user_id']|default=''}</div>
                <input type="hidden" name="lvhua_sh_user" id="lvhua_sh_user" value="{$data_info['lvhua_sh_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排水校对人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="paishui_jd_user_id" onclick="open_div('paishui_jd')">选择排水校对人</button>
                <div id="paishui_jd_select_id">{$data_info['paishui_jd_user_id']|default=''}</div>
                <input type="hidden" name="paishui_jd_user" id="paishui_jd_user" value="{$data_info['paishui_jd_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排水审核人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="paishui_sh_user_id" onclick="open_div('paishui_sh')">选择排水审核人</button>
                <div id="paishui_sh_select_id">{$data_info['paishui_sh_user_id']|default=''}</div>
                <input type="hidden" name="paishui_sh_user" id="paishui_sh_user" value="{$data_info['paishui_sh_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">强弱电校对人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="dian_jd_user_id" onclick="open_div('dian_jd')">选择强弱电校对人</button>
                <div id="dian_jd_select_id">{$data_info['dian_jd_user_id']|default=''}</div>
                <input type="hidden" name="dian_jd_user" id="dian_jd_user" value="{$data_info['dian_jd_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">强弱电审核人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="dian_sh_user_id" onclick="open_div('dian_sh')">选择强弱电审核人</button>
                <div id="dian_sh_select_id">{$data_info['dian_sh_user_id']|default=''}</div>
                <input type="hidden" name="dian_sh_user" id="dian_sh_user" value="{$data_info['dian_sh_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">服务负责人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="fuwu_user_id" onclick="open_div('fuwu')">选择服务负责人</button>
                <div id="fuwu_select_id">{$data_info['fuwu_user_id']|default=''}</div>
                <input type="hidden" name="fuwu_user" id="fuwu_user" value="{$data_info['fuwu_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">参与人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="deal_user_id" onclick="open_div('deal')">选择项目参与人</button>
                <div id="deal_select_id">{$data_info['deal_user_id']|default=''}</div>
                <input type="hidden" name="deal_user" id="deal_user" value="{$data_info['deal_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">审批人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="send_user_id" onclick="open_div('send')">选择审批人</button>
                <div id="send_select_id">{$data_info['send_user_id']|default=''}</div>
                <input type="hidden" name="send_user" id="send_user" value="{$data_info['send_user']|default=''}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">抄送人</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="copy_user_id" onclick="open_div('copy')">选择抄送人</button>
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