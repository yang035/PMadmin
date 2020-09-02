<style media="print">
    @page {
        size: auto;  /* auto is the initial value */
        margin: 0mm; /* this affects the margin in the printer settings */
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body" id="printArea">
            <pre style="font-size:larger">


                               员工离职证明

        {$data_info['realname']} 同志于 {$data_info['start_date']} 至 {$data_info['end_date']} 在我们公司从事 {$data_info['job_name']} 工作。因个人原因提出离职，现已完备离职手续，与我公司解除劳动关系。
        特此证明。



                                                                （盖章）

                                                             年    月    日


        请扫描二维码查看具体内容（需要注册和登录系统）
        <img src="/{$qcode_png}" style="height: 100px;width: 100px">
            </pre>

        </div>
        <div><button id="btnPrint" type="button" class="layui-btn layui-btn-normal">打印</button></div>
    </div>
</form>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery-migrate-1.2.1.min.js"></script>
<script src="__PUBLIC_JS__/jquery.jqprint-0.3.js"></script>
<script>
    var formData = {:json_encode($data_info)};
    $("#btnPrint").click(function(){
        $("#printArea").jqprint();
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>