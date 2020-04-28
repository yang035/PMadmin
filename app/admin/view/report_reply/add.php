<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">完成情况</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-realper" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" onblur="check_realper(this.value)" name="realper" autocomplete="off" placeholder="请输整数">
        </div>
        <div class="layui-form-mid red">%</div>
        <div class="layui-form-mid">不能超过 <span id="realper">{$row_report['realper']}</span></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-inline">
            <textarea type="text" rows="8" class="layui-textarea field-content" name="content" lay-verify="required" autocomplete="off" placeholder="请输入内容"></textarea>
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">流程同步</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-flow_sys" name="flow_sys" value="1" title="是" lay-filter="flow_sys">
            <input type="radio" class="field-flow_sys" name="flow_sys" value="0" title="否" checked lay-filter="flow_sys">
        </div>
    </div>
    <div class="layui-form-item" style="display: none" id="flow_position">
        <label class="layui-form-label">选择位置</label>
        <div class="layui-input-inline" style="width: 100px;">
            <select name="flow_cat" class="field-flow_cat" type="select" lay-filter="flow_cat">
                {$flow_cat}
            </select>
        </div>
        <div class="layui-input-inline" style="width: 150px;">
            <select name="flow_item" class="field-flow_item" type="select" lay-filter="flow_item" id="c_id">
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-pid" name="pid">
            <input type="hidden" class="field-type" name="type" value="{$Request.param.type|default='1'}">
            <input type="hidden" class="field-report_id" name="report_id">
            <input type="hidden" class="field-project_id" name="project_id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
<!--            <a href="{:url('project/editTask')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>-->
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate', 'form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, form = layui.form;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

        form.on('radio(flow_sys)', function(data){
            if (1 == data.value){
                $('#flow_position').show();
            } else {
                $('#flow_position').hide();
            }
        });

        form.on('select(flow_cat)', function(data){
            select_union("{$Request.param.project_id}",data.value);
        });

        function select_union(project_id,flow_cat){
            $.ajax({
                type: 'POST',
                url: "{:url('flowCat')}",
                data: {project_id:project_id,flow_cat:flow_cat},
                dataType:  'json',
                success: function(data){
                    if (1 == data.code){
                        $('#c_id').html(data.data);
                        form.render('select');
                    }
                }
            });
        }
    });
    function check_realper(v) {
        var r = $('#realper').text();
        if (Number(v) > Number(r)){
            layer.msg('不能超过最大值'+r);
        }
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>