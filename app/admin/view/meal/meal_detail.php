<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-form-item">
            <label class="layui-form-label">金额</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-other_price" name="other_price" value="{$taocan_money}" readonly lay-verify="required" autocomplete="off" placeholder="">
            </div>
            <div class="layui-form-mid">元</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" class="field-qu_type" name="qu_type" value="{$Request.param.qu_type}">
                <input type="hidden" class="field-p" name="p" value="{$Request.param.p}">
                <button type="submit" class="layui-btn layui-btn-normal btn_sub" lay-submit="" lay-filter="formSubmit">确认</button>
                <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
    </div>
    <hr>
    <div class="layui-card">
        <div class="layui-card-body">
            您购买的套餐为：{$qu}【{$taocan}】<br><br>
            包含以下内容项：<br>
            {volist name="data_list" id="vo"}
            {$vo['name']}[
            {eq name="vo['meal_type']" value="1"}
                {eq name="vo[$p]" value="1"}
            &#10003
                {else/}
            &#10005
                {/eq}
            {else/}
            {$vo[$p]}
            {/eq}
            ]<br>
            {/volist}
        </div>
    </div>
</form>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>