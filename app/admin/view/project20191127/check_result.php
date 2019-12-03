<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form">
        <table class="layui-table mt10" lay-even="" lay-skin="row" lay-size="sm">
            <colgroup>
                <col width="50">
            </colgroup>
            <thead>
            <tr>
                <th width="150px">审核项</th>
                <th width="160px">是否有问题</th>
                <th>责任人</th>
                <th>ML(斗)</th>
                <th>GL(斗)</th>
                <th>意见</th>
            </tr>
            </thead>
            <tbody>
            {volist name="data_list" id="vo"}
            <tr>
                <td class="font12">
                    <strong class="mcolor">{$cat_option[$key]}</strong>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            {volist name="vo['data']" id="v"}
            {empty name="check_log"}
            <tr>
                <td>{$v['name']}({$v['ml']})</td>
                <td>
                    <input type="radio" name="flag[{$v['id']}]" class="layui-checkbox checkbox-ids" value="1" title="有">
                    <input type="radio" name="flag[{$v['id']}]" class="layui-checkbox checkbox-ids" checked value="0" title="无">
                </td>
                <td>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <button type="button" class="layui-btn layui-btn-xs" id="person_user_id[{$v['id']}]" onclick="check_user({$v['id']})">选择人员</button>
                            <div id="person_select_id[{$v['id']}]"></div>
                            <input type="hidden" name="person_user[{$v['id']}]" id="person_user[{$v['id']}]" value="">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="number" class="layui-input field-ml" name="ml[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="">
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="number" class="layui-input field-gl" name="gl[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="">
                    </div>
                </td>
                <td>
                    <input class="layui-input" name="mark[{$v['id']}]">
                    <input type="hidden" class="field-check_id" name="check_id[{$v['id']}]" value="{$v['id']}">
                    <input type="hidden" class="field-check_name" name="check_name[{$v['id']}]" value="{$v['name']}">
                    <input type="hidden" class="field-check_ml" name="check_ml[{$v['id']}]" value="{$v['ml']}">
                </td>
            </tr>
            {else/}
            {notempty name="check_log[0]['content'][$v['id']]"}
            <tr>
                <td>{$check_log[0]['content'][$v['id']]['check_name']}({$v['ml']})</td>
                <td>
                    <input type="radio" name="flag[{$v['id']}]" class="layui-checkbox checkbox-ids" {eq name="check_log[0]['content'][$v['id']]['flag']" value="1"}checked{/eq} value="1" title="有">
                    <input type="radio" name="flag[{$v['id']}]" class="layui-checkbox checkbox-ids" {eq name="check_log[0]['content'][$v['id']]['flag']" value="0"}checked{/eq} value="0" title="无">
                </td>
                <td>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <button type="button" class="layui-btn layui-btn-xs" id="person_user_id[{$v['id']}]" onclick="check_user({$v['id']})">选择人员</button>
                            <div id="person_select_id[{$v['id']}]">{$check_log[0]['content'][$v['id']]['person_select_id']|default=''}</div>
                            <input type="hidden" name="person_user[{$v['id']}]" id="person_user[{$v['id']}]" value="{$check_log[0]['content'][$v['id']]['person_user']|default=''}">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="number" class="layui-input field-ml" name="ml[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="" value="{$check_log[0]['content'][$v['id']]['ml']}">
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="number" class="layui-input field-gl" name="gl[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="" value="{$check_log[0]['content'][$v['id']]['gl']}">
                    </div>
                </td>
                <td>
                    <input class="layui-input" name="mark[{$v['id']}]" value="{$check_log[0]['content'][$v['id']]['mark']}">
                    <input type="hidden" class="field-check_id" name="check_id[{$v['id']}]" value="{$v['id']}">
                    <input type="hidden" class="field-check_name" name="check_name[{$v['id']}]" value="{$v['name']}">
                    <input type="hidden" class="field-check_ml" name="check_ml[{$v['id']}]" value="{$v['ml']}">
                </td>
            </tr>
            {else/}
            <tr>
                <td>{$v['name']}({$v['ml']})</td>
                <td>
                    <input type="radio" name="flag[{$v['id']}]" class="layui-checkbox checkbox-ids" value="1" title="有">
                    <input type="radio" name="flag[{$v['id']}]" class="layui-checkbox checkbox-ids" checked value="0" title="无">
                </td>
                <td>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <button type="button" class="layui-btn layui-btn-xs" id="person_user_id[{$v['id']}]" onclick="check_user({$v['id']})">选择人员</button>
                            <div id="person_select_id[{$v['id']}]"></div>
                            <input type="hidden" name="person_user[{$v['id']}]" id="person_user[{$v['id']}]" value="">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="number" class="layui-input field-ml" name="ml[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="">
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="number" class="layui-input field-gl" name="gl[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="">
                    </div>
                </td>
                <td>
                    <input class="layui-input" name="mark[{$v['id']}]">
                    <input type="hidden" class="field-check_id" name="check_id[{$v['id']}]" value="{$v['id']}">
                    <input type="hidden" class="field-check_name" name="check_name[{$v['id']}]" value="{$v['name']}">
                    <input type="hidden" class="field-check_ml" name="check_ml[{$v['id']}]" value="{$v['ml']}">
                </td>
            </tr>
            {/notempty}
            {/empty}
            {/volist}
            {/volist}
            </tbody>
        </table>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <input type="hidden" class="field-project_id" name="project_id" value="{$Request.param.project_id}">
            <input type="hidden" class="field-report_id" name="report_id" value="{$Request.param.report_id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
    <hr>
    <div class="layui-form">
        {notempty name="check_log"}
        <div class="layui-card">
            <div class="layui-card-body">
                <table class="layui-table mt10" lay-even="" lay-skin="row" lay-size="sm">
                    <thead>
                    <tr>
                        <th width="150px">审核项</th>
                        <th width="160px">是否有问题</th>
                        <th>责任人</th>
                        <th>ML(斗)</th>
                        <th>GL(斗)</th>
                        <th>意见</th>
                    </tr>
                    </thead>
                    <tbody>
                    {volist name="check_log" id="vo"}
                    <tr>
                        <td class="font12">
                            <strong class="mcolor">审核人：{$vo['user_name']}</strong>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                        {volist name="vo['content']" id="v"}
                            <tr>
                                <td>{$v['check_name']}({$v['check_ml']})</td>
                                <td>{$v['flag_name']}</td>
                                <td>{$v['person_select_id']}</td>
                                <td>{$v['ml']}</td>
                                <td>{$v['gl']}</td>
                                <td>{$v['mark']}</td>
                            </tr>
                        {/volist}
                    {/volist}
                    </tbody>
                </table>
            </div>
        </div>
        {/notempty}
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, form = layui.form;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

    });

    function check_user(i) {
        var person_user = document.getElementById('person_user['+i+']').value;
        var open_url = "{:url('Tool/getTreeUser')}?m=person&u="+person_user+"&i="+i;
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