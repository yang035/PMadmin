<style>
    .layui-form-pane .layui-form-label {
        width: 150px;
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
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">目标值比例</label>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="text" name="min_num" placeholder="" autocomplete="off" class="layui-input field-min_num">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="text" name="max_num" placeholder="" autocomplete="off" class="layui-input field-max_num">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">奖励百分比</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-prize_ratio" name="prize_ratio" lay-verify="required" autocomplete="off" placeholder="请输入奖励百份比">
        </div>
        <div class="layui-form-mid" style="color: red">%&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">带薪年假</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-annual_leave" name="annual_leave" autocomplete="off" placeholder="请输入天数">
        </div>
        <div class="layui-form-mid" style="color: red">天&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">旅游金</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-travel_money" name="travel_money" autocomplete="off" placeholder="请输入金额">
        </div>
        <div class="layui-form-mid" style="color: red">元&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">设备折旧比例</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-davice_use" name="davice_use" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">技能培训比例</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-train_per" name="train_per" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">就业指导比例</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-job_train" name="job_train" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">损差补偿比例</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-compensation" name="compensation" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">社保</label>
        <div class="layui-input-block">
            <input type="checkbox" checked="" class="field-social" name="social" lay-skin="switch" value="1" lay-filter="switchTest" lay-text="有|无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公积金</label>
        <div class="layui-input-block">
            <input type="checkbox" checked="" class="field-accumulation" name="accumulation" lay-skin="switch" value="1" lay-filter="switchTest" lay-text="有|无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">个人意外险</label>
        <div class="layui-input-block">
            <input type="checkbox" checked="" class="field-accident_insurance" name="accident_insurance" lay-skin="switch" value="1" lay-filter="switchTest" lay-text="有|无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">家人幸福险</label>
        <div class="layui-input-block">
            <input type="checkbox" checked="" class="field-happy_insurance" name="happy_insurance" lay-skin="switch" value="1" lay-filter="switchTest" lay-text="有|无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状&nbsp;&nbsp;&nbsp;&nbsp;态</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
            <input type="radio" class="field-status" name="status" value="0" title="禁用">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
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
    //获取设备信息
    // var device = layui.device();
    // alert(JSON.stringify(device));
});
</script>
<script src="__ADMIN_JS__/footer.js"></script>