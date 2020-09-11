<style media="print">
    @page {
        size: auto;  /* auto is the initial value */
        margin: 0mm; /* this affects the margin in the printer settings */
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body" id="printArea">
            <style type="text/css" media="print">
                pre{font-size: 30px;font-weight: bolder}
            </style>
            <pre>



                                                     推  荐  信



                 ___________公司：


                            诚以养德，信以立身；

                            倾情力荐，详情扫现。

                            衷心感谢贵公司大力支持！



                                                                （盖章）

                                                             年    月    日


        请扫描二维码查看具体内容（登录后需要再次扫码）
        <img src="/{$qcode_png}" style="height: 200px;width: 200px">
            </pre>

        </div>
        <div><button id="btnPrint" type="button" class="layui-btn layui-btn-normal">打印</button></div>
        <div>
            <pre style="font-size:larger">
                {$data_info['realname']} 同志于 {$data_info['start_date']} 至 {$data_info['end_date']} 在我司从事 {$data_info['job_name']} 工作。因个人原因提出离职，现已完备离职手续，
            即日起与我公司解除劳动关系。特此证明。

            在职期间工作情况如下：
            参与项目： {$score['num']} 个
            获得ML： {$score['ml_add_sum']} 斗
            获得GL： {$score['gl_add_sum']} 斗
            </pre>
        </div>
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