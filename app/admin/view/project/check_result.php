<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form">
        <table class="layui-table mt10" lay-even="" lay-skin="row" lay-size="sm">
            <colgroup>
                <col width="50">
            </colgroup>
            <thead>
            <tr>
                <th width="100px">审核项</th>
                <th width="160px">是否有问题</th>
                <th>责任人</th>
                <th>ML</th>
                <th>GL</th>
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
            <tr>
                <td>{$v['name']}</td>
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
                        <input type="number" class="layui-input field-ml" name="ml[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="ML">
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="number" class="layui-input field-gl" name="gl[{$v['id']}]" autocomplete="off" lay-verify="" placeholder="GL">
                    </div>
                </td>
                <td>
                    <input class="layui-input" name="mark[{$v['id']}]">
                    <input type="hidden" class="field-check_id" name="check_id[{$v['id']}]" value="{$v['id']}">
                </td>
            </tr>
            {/volist}
            {/volist}
            </tbody>
        </table>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
    <div class="layui-form">
        {volist name="score_log" id="vo"}
        <div class="layui-card">
            <div class="layui-card-body">
                <span style="color: red">{$vo['create_time']}</span>(合计:{$vo['total_score']}分)<br>
                {volist name="vo['score']" id="v"}
                {$item_option[$key]} : {$v}分&nbsp;&nbsp;&nbsp;&nbsp;{$vo['mark'][$key]}<br>
                {/volist}
            </div>
        </div>
        {/volist}
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