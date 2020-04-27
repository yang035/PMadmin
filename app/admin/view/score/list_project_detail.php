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
                        <th>进度(%)</th>
                        <th>人员</th>
                        <th>ML(斗)</th>
                        <th>合伙系数</th>
                        <th>协议单价</th>
                        <th>总价</th>
                    </tr>
                    </thead>
                    <tbody>
                    {volist name="data_info['small_major_deal_arr']" id="f"}
                    {volist name="f['child']" id="f1"}
                    <tr>
                        <td>{$f['name']}({$f['value']/100})</td>
                        <td>{$f1['name']}({$f1['value']/100})</td>
                        <td>{$f1['jindu']*100}</td>
                        <td>{$f1['dep_name']|default=''}</td>
                        <td>{$f1['ml']}</td>
                        <td>{$f1['hehuo_name']['name']|default=''}({$f1['hehuo_name']['ratio']|default=''})</td>
                        <td>{$f1['per_price']|default=''}</td>
                        <td>{$f1['ml']*$f1['per_price']}</td>
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