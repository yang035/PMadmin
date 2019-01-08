<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            合同名称：{$data_info['name']}<br>
            项目名称：{$Request.param.subject_name}<br>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">跟踪记录</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="content" class="field-content"></textarea>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <input type="hidden" class="field-subject_id" name="subject_id">
            <input type="hidden" class="field-id" name="contract_id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
<hr>
<div class="layui-card">
    <div class="layui-card-header">跟踪记录</div>
    <ul class="layui-timeline">
        {volist name="contractLog" id="vo"}
        <li class="layui-timeline-item">
            <i class="layui-icon layui-timeline-axis"></i>
            <div class="layui-timeline-content layui-text">
                <div class="layui-timeline-title">
                    <span style="color: red">[{$vo['create_time']}]</span>记录人[{$vo['realname']}]
                    <br>
                    {$vo['content']}
                </div>
            </div>
        </li>
        {/volist}
    </ul>
</div>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, form = layui.form;

    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>