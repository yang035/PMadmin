<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: 400px;
        margin-right: 10px;
    }

</style>
<form class="layui-form" action="{:url()}" method="post" name="loginForm">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="show_div">
            <div class="layui-form-item">
                <label class="layui-form-label">机器名称</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-name" name="name" readonly
                           autocomplete="off" placeholder="机器名称">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">CPU</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-cpu" name="cpu" readonly
                           autocomplete="off" placeholder="CPU">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">内存总量</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-total_mem" name="total_mem" readonly
                           autocomplete="off" placeholder="机器名称">
                </div>
                <div class="layui-form-mid">M</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">可用内存</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-unuse_mem" name="unuse_mem" readonly
                           autocomplete="off" placeholder="可用内存">
                </div>
                <div class="layui-form-mid">M</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">主板</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-zhuban" name="zhuban" readonly
                           autocomplete="off" placeholder="主板">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">BIOS</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-bios" name="bios" readonly
                           autocomplete="off" placeholder="BIOS">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">主硬盘</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-yingpan" name="yingpan" readonly
                           autocomplete="off" placeholder="硬盘">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">主硬盘序列号</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-serial_number" name="serial_number" readonly
                           autocomplete="off" placeholder="主硬盘序列号">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显卡</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-xianka" name="xianka" readonly
                           autocomplete="off" placeholder="显卡">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">网卡</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-wangka" name="wangka" readonly
                           autocomplete="off" placeholder="网卡">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">网卡MAC</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-mac" name="mac" readonly
                           autocomplete="off" placeholder="网卡MAC">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">IP</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-ip" name="ip" readonly
                           autocomplete="off" placeholder="IP">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显示器</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-xianshiqi" name="xianshiqi" readonly
                           autocomplete="off" placeholder="显示器">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">声卡</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-shengka" name="shengka" readonly
                           autocomplete="off" placeholder="声卡">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">操作系统</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-os_info" name="os_info" readonly
                           autocomplete="off" placeholder="操作系统">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-computer_type" name="computer_type" value="0" title="台式机" checked>
                    <input type="radio" class="field-computer_type" name="computer_type" value="1" title="笔记本">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" class="field-id" name="id">
                    <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                    <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                </div>
            </div>
        </div>
        <div class="layui-form-mid red hide_div"></div>
    </div>

</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','element', 'upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,element = layui.element,upload = layui.upload,form = layui.form;
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>