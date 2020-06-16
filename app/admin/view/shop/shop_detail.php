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
            <label class="layui-form-label">可用谷粒</label>
            <div class="layui-form-mid" style="color: red;"><b>{$score}</b> 斗</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">兑换数量</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-num" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="num" value="1" lay-verify="required" autocomplete="off" readonly placeholder="请输入数量">
            </div>
            <div class="layui-form-mid">份</div>
            <div class="layui-form-mid" style="color: red" id="cost"></div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">额外支付</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-other_price" name="other_price" value="{$data_list['other_price']}" readonly lay-verify="required" autocomplete="off" placeholder="">
            </div>
            <div class="layui-form-mid">元</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" class="field-unit_score" name="unit_score" value="{$data_list['score']}">
                <input type="hidden" class="field-item_id" name="item_id" value="{$data_list['id']}">
                <button type="submit" class="layui-btn layui-btn-normal btn_sub" lay-submit="" lay-filter="formSubmit">提交</button>
                <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
    </div>
    <hr>
    <div class="layui-card">
        <div class="layui-card-body">
            <b>名称：</b>{$data_list['name']}<br>
            <b>促销信息：</b>{$data_list['content']}<br>
            <b>谷粒兑换：</b>{$data_list['score']}(斗)<br>
            <b>等价于：</b>{$data_list['marketprice']}(元)<br>
            <b>额外支付：</b>{$data_list['other_price']}(元)<br>
            <b>描述：</b>{$data_list['remark']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate','upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload, form = layui.form;
        var score = "{$score}",unit_score = "{$data_list['score']}",other_price = "{$data_list['other_price']}";
        $('#cost').text('消耗谷粒：'+unit_score*1+'斗');
        if (unit_score*1 > score){
            layer.alert('谷粒不够兑换');
            $('.btn_sub').hide();
        }else {
            $('.btn_sub').show();
        }

        $('.field-num').keyup(function () {
            var num = $('.field-num').val();
            var total_score = unit_score*num;
            if (total_score > score){
                layer.alert('谷粒不够兑换');
                $('.btn_sub').hide();
            }else {
                $('.btn_sub').show();
            }
            $('#cost').text('消耗谷粒：'+total_score+'斗');
            $("input[name='other_price']").val(other_price*num);
        })
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>