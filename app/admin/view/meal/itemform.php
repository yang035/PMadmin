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
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入名称">
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">上级分类</label>
            <div class="layui-input-inline">
                <select name="cat_id" class="field-cat_id" type="select">
                    {$cat_option}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所属区域</label>
            <div class="layui-input-inline">
                <select name="qu_type" class="field-qu_type" type="select">
                    {$qu_type}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所属类型</label>
            <div class="layui-input-inline">
                <select name="meal_type" class="field-meal_type" type="select" lay-filter="meal_type">
                    {$meal_type}
                </select>
            </div>
            <div class="layui-form-mid">默认按钮，如果输入数字请选择"输入框"</div>
        </div>
        <div id="anniu">
            <div class="layui-form-item">
                <label class="layui-form-label">套餐A</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-taocan_1" name="taocan_1" value="1" title="有" checked>
                    <input type="radio" class="field-taocan_1" name="taocan_1" value="0" title="无">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐B</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-taocan_2" name="taocan_2" value="1" title="有" checked>
                    <input type="radio" class="field-taocan_2" name="taocan_2" value="0" title="无">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐C</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-taocan_3" name="taocan_3" value="1" title="有" checked>
                    <input type="radio" class="field-taocan_3" name="taocan_3" value="0" title="无">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐D</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-taocan_4" name="taocan_4" value="1" title="有" checked>
                    <input type="radio" class="field-taocan_4" name="taocan_4" value="0" title="无">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐E</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-taocan_5" name="taocan_5" value="1" title="有" checked>
                    <input type="radio" class="field-taocan_5" name="taocan_5" value="0" title="无">
                </div>
            </div>
        </div>
        <div id="shuru" class="hide">
            <div class="layui-form-item">
                <label class="layui-form-label">套餐A</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-taocan_1" name="taocan_1" autocomplete="off" placeholder="优惠百分比" value="1">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐B</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-taocan_2" name="taocan_2" autocomplete="off" placeholder="优惠百分比" value="1">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐C</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-taocan_3" name="taocan_3" autocomplete="off" placeholder="优惠百分比" value="1">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐D</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-taocan_4" name="taocan_4" autocomplete="off" placeholder="优惠百分比" value="1">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">套餐E</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-taocan_5" name="taocan_5" autocomplete="off" placeholder="优惠百分比" value="1">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="[选填]分类简介"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
                <input type="radio" class="field-status" name="status" value="0" title="禁用">
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

    layui.use(['jquery', 'laydate','upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form;
        if (2 != formData.meal_type){
            $("#shuru").find(":input").attr("disabled", true);
        } else {
            $('#anniu').hide();
            $('#shuru').show();
            $("#shuru").find(":input").attr("disabled", false);
        }

        form.on('select(meal_type)', function(data){
            if(1 == data.value){
                $('#anniu').show();
                $('#shuru').hide();
                $("#shuru").find(":input").attr("disabled", true);
            }else {
                $('#anniu').hide();
                $('#shuru').show();
                $("#shuru").find(":input").attr("disabled", false);
            }
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>