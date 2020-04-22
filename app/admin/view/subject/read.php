<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>项目编号：</b>{$data_info['idcard']}<br>
            <b>项目规模：</b>{$data_info['name']}({$data_info['score']}平方米)<br>
            <b>类别系数：</b>{$subject_cat[$data_info['cat_id']]['name']}({$subject_cat[$data_info['cat_id']]['ratio']})<br>
            <div class="layui-form">
                <table class="layui-table mt10" lay-even="" lay-skin="row">
                    <thead>
                    <tr>
                        <th>专业类型</th>
                        <th>专业</th>
                        <th>阶段系数</th>
                        <th>ML(斗)</th>
                    </tr>
                    </thead>
                    <tbody>
                    {volist name="data_info['small_major_deal']" id="f"}
                    {volist name="f['child']" id="f1"}
                    <tr>
                        <td>{$f['name']}({$f['value']/100})</td>
                        <td>{$f1['name']}({$f1['value']/100})</td>
                        <td>1.00</td>
                        <td>{$data_info['score'] * $subject_cat[$data_info['cat_id']]['ratio'] * $f['value']/100 * $f1['value']/100 * 1.00}</td>
                    </tr>
                    {/volist}
                    {/volist}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>