<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form">
        <table class="layui-table mt10" lay-even="" lay-skin="row" lay-size="sm">
            <colgroup>
                <col width="50">
            </colgroup>
            <thead>
            <tr>
                <th width="100px">名称</th>
                <th>得分</th>
                <th>建议</th>
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
            </tr>
            {volist name="vo['data']" id="v"}
            <tr>
                <td>{$v['name']}</td>
                <td>
                    <input type="radio" name="score[{$v['id']}]" class="layui-checkbox checkbox-ids" checked value="10" lay-skin="primary">10分
                    <input type="radio" name="score[{$v['id']}]" class="layui-checkbox checkbox-ids" value="5" lay-skin="primary">5分
                    <input type="radio" name="score[{$v['id']}]" class="layui-checkbox checkbox-ids" value="0" lay-skin="primary">0分
                </td>
                <td><input class="layui-input" name="mark[{$v['id']}]"></td>
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

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>