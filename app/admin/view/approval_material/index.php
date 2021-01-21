<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div>
    <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="search_form">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <select name="class_type" class="field-class_type" type="select">
                        <option value="0">全部</option>
                        {volist name="panel_type" id="vo"}
                        <option value="{$key}" {eq name="$Request.param.class_type" value="$key"} selected {/eq}>{$vo['title']}</option>
                        {/volist}
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">日期范围</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="test2" name="search_date" placeholder="选择日期" readonly value="{$d|default=''}">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">审批编号</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="id" placeholder="输入审批编号">
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-primary" id="person_user_id">选择人员</button>
                    <div id="person_select_id"></div>
                    <input type="hidden" name="person_user" id="person_user" value="">
                </div>
            </div>
            <div class="layui-inline">
                <input type="hidden" name="atype" value="{$Request.param.atype}">
                <input type="hidden" name="export" value="">
                <button type="submit" class="layui-btn layui-btn-normal normal_btn">搜索</button>
                <input type="button" class="layui-btn layui-btn-primary layui-icon export_btn" value="导出">
            </div>
        </div>
    </form>
    {if condition="($Request.param.atype eq 3) && ($Request.param.class_type > 0) "}
    <div class="layui-btn-group fl">
<!--            <a href="{:url('addItem')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>-->
        <a data-href="{:url('batch',['table'=>'approval','val'=>2,'class_type'=>$Request.param.class_type])}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">同意</a>
        <a data-href="{:url('batch',['table'=>'approval','val'=>4,'class_type'=>$Request.param.class_type])}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">驳回</a>
<!--            <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
    {/if}
</div>

<div class="layui-form">
    <table id="dataTable" class="layui-table mt10" lay-even="" lay-skin="row">
        <colgroup>
            <col width="50">
        </colgroup>
        <thead>
        <tr>
            <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
            <th>编号</th>
            <th>姓名</th>
            <th>类型</th>
            <th>归属于</th>
            <th>时间段</th>
            <th>项目名称</th>
            <th>金额(元)</th>
            {notin name="$Request.param.atype" value="8,9"}
            <th>审批人</th>
            <th>同行人</th>
            <th>添加时间</th>
            <th>状态</th>
            <th>审批意见</th>
            <th>审批时间</th>
            <th>财务审核</th>
            {else/}
            <th>财务审核</th>
            <th>审核状态</th>
            <th>审核意见</th>
            <th>审核时间</th>
            {/notin}
            <th style="width: 100px">支付结果</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {volist name="data_list" id="vo"}
        <tr>
            <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
            <td class="font12">{$vo['id']}</td>
            <td class="font12">
                <strong class="mcolor">{$vo['realname']}</strong>
            </td>
            <td class="font12">{$panel_type[$vo['class_type']]['title']}</td>
            <td class="font12">{$vo['leave_type']}</td>
            <td class="font12">始:{$vo['start_time']}<br>止:{$vo['end_time']}</td>
            <td class="font12">{$vo['project_name']}</td>
            <td class="font12">{$vo['money']}</td>
            {notin name="$Request.param.atype" value="8,9"}
            <td class="font12">{$vo['send_user']}</td>
            <td class="font12">{$vo['fellow_user']}</td>
            <td class="font12">{$vo['create_time']}</td>
            <td class="font12 mcolor">{$approval_status[$vo['status']]}</td>
            <td class="font12">{$vo['mark']}</td>
            {if condition="$vo['create_time'] neq $vo['update_time']"}
            <td class="font12">{$vo['update_time']}</td>
            {else/}
            <td class="font12">-</td>
            {/if}
            <td class="font12">{$finance_status1[$vo['finance_status']]}</td>
            {else/}
            <td class="font12">{$vo['finance_user']}</td>
            <td class="font12">{$finance_status1[$vo['finance_status']]}</td>
            <td class="font12">{$vo['finance_mark']}</td>
            {if condition="$vo['finance_time'] neq $vo['finance_time']"}
            <td class="font12">{$vo['finance_time']}</td>
            {else/}
            <td class="font12">-</td>
            {/if}
            {/notin}

            <td class="font12">{$vo['deal_mark']}</td>
            <td>
                <div class="layui-btn-group" onclick="approval_read({$vo['id']},{$atype},{$vo['class_type']},'{$panel_type[$vo['class_type']]['title']}')">
                    <a class="layui-btn layui-btn-normal layui-btn-xs">
                        {if condition="($vo['status'] eq 1) && ($Request.param.atype eq 3) "}
                        批示
                        {else/}
                        查看处理
                        {/if}
                    </a>
                </div>
                {if condition="($vo['status'] eq 1) && ($Request.param.atype eq 2) "}
                <div class="layui-btn-group" onclick="approval_back({$vo['id']})">
                    <a class="layui-btn layui-btn-normal layui-btn-xs">撤销</a>
                </div>
                {/if}
                {if condition="($vo['status'] eq 2) && ($Request.param.atype == 2 or $Request.param.atype == 7) && ($vo['h'] eq 1)"}
                {in name="vo['class_type']" value="3,4,6,7"}
                <div class="layui-btn-group" onclick="expense({$vo['id']},'{$vo['class_type']}')">
                    <a class="layui-btn layui-btn-danger layui-btn-xs">报销</a>
                </div>
                {/in}
                {/if}
                {if condition="($vo['status'] eq 2) && ($Request.param.atype == 2 or $Request.param.atype == 7) && ($vo['class_type'] == 20 or $vo['class_type'] == 21)"}
                <div class="layui-btn-group" onclick="applyPay({$vo['id']},'{$vo['class_type']}')">
                    <a class="layui-btn layui-btn-danger layui-btn-xs">申请支付</a>
                </div>
                {/if}
                {if condition="$vo['class_type'] eq 4 "}
                <div class="layui-btn-group" onclick="approval_report({$vo['id']},{$atype},{$vo['class_type']},'{$panel_type[$vo['class_type']]['title']}')">
                    <a class="layui-btn layui-btn-normal layui-btn-xs">出差报告</a>
                </div>
                {/if}
                {if condition="($vo['class_type'] eq 18) && ($vo['status'] eq 2)"}
                <div class="layui-btn-group" onclick="certificate({$vo['id']})">
                    <a class="layui-btn layui-btn-normal layui-btn-xs">推荐信</a>
                </div>
                <div class="layui-btn-group" onclick="leave_file({$vo['user_id']},{$vo['id']})">
                    <a class="layui-btn layui-btn-normal layui-btn-xs">离职归档</a>
                </div>
                {/if}
            </td>
        </tr>
        {/volist}
        </tbody>
    </table>
    {$pages}
</div>

{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate','form'], function() {
        var $ = layui.jquery,laydate = layui.laydate;
        //年选择器
        laydate.render({
            elem: '#test2',
            range: true
        });

        $('.export_btn').click(function () {
            if ($(this).val() == '导出'){
                $('input[name=export]').val(1);
                $('#search_form').submit();
            }
        });

        $('.normal_btn').click(function () {
            if ($(this).val() != '导出'){
                $('input[name=export]').val('');
                $('#search_form').submit();
            }
        });

        $('#person_user_id').on('click', function(){
            var person_user = $('#person_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=person&u="+person_user;
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });
    });

    function approval_read(id,atype,class_type,cls_name){
        var open_url = "{:url('ApprovalMaterial/read')}?id="+id+"&atype="+atype+"&class_type="+class_type;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :cls_name,
            maxmin: true,
            area: ['800px', '600px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function approval_report(id,atype,class_type,cls_name){
        var open_url = "{:url('ApprovalMaterial/read')}?id="+id+"&atype="+atype+"&class_type="+class_type;
        window.location.href = open_url;
    }

    function certificate(id){
        var open_url = "{:url('ApprovalMaterial/certificate')}?id="+id;
        window.location.href = open_url;
    }

    function leave_file(user_id,id){
        var open_url = "{:url('ApprovalMaterial/leaveFile')}?user="+user_id+"&approval_id="+id;
        window.location.href = open_url;
    }

    function approval_back(id) {
        var open_url = "{:url('approvalBack')}?id="+id;
        layer.confirm('确定撤销？', {
            btn: ['是','否'] //按钮
        }, function(){
            $.post(open_url, function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                }else {
                    layer.msg(res.msg);
                }
                location.reload();
            });
        });

    }

    function expense(id,ct){
        var open_url = "{:url('ApprovalMaterial/expense')}?id="+id+"&class_type=2&ct="+ct;
        window.location.href = open_url;
    }

    function applyPay(id,ct){
        var open_url = "{:url('ApprovalMaterial/applyPay')}?id="+id+"&class_type=21&ct="+ct;
        window.location.href = open_url;
    }
</script>