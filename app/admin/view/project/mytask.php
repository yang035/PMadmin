<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">选择项目</label>
                    <div class="layui-input-inline box box1">
                    </div>
                    <input id="project_name" type="hidden" name="project_name" value="{$Request.param.project_name}">
                    <input id="project_id" type="hidden" name="project_id" value="{$Request.param.project_id}">
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">任务名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
<!--                <div class="layui-inline">-->
<!--                    <label class="layui-form-label">是否完成</label>-->
<!--                    <div class="layui-input-inline">-->
<!--                        <select name="status">-->
<!--                            <option value="0" {if condition="$Request.param.status eq 0"}selected{/if} >进行中</option>-->
<!--                            <option value="1" {if condition="$Request.param.status eq '1' "}selected{/if} >已完成</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
                <input type="hidden" name="type" value="{$Request.param.type}">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-form">
        <table class="layui-table mt10" lay-even="" lay-skin="row">
            <colgroup>
                <col width="50">
            </colgroup>
            <thead>
            <tr>
                <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
                <th>项目名称</th>
                <th>任务主题</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>计划产量(斗)</th>
                <th>实际产量(斗)</th>
                <th>紧急度</th>
                <th>参与人</th>
                <th>负责人</th>
                <th>审批人</th>
                <th>完成情况</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            {volist name="data_list" id="vo"}
            <tr>
                <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                <td class="font12">{$vo['project_name']}</td>
                <td class="font12" title="{$vo['remark']}">
                    <a href="{:url('editTask',['id'=>$vo['id'],'pid'=>$vo['pid'],'type'=>$type,'project_name'=>$vo['project_name']])}"><strong class="mcolor">{$vo['name']}</strong></a>
                </td>
                <td class="font12">{$vo['start_time']}</td>
                <td class="font12">{$vo['end_time']}</td>
                <td class="font12">{$vo['score']}</td>
                <td class="font12 red">{$vo['real_score']}</td>
                <td class="font12">{$vo['grade']}</td>
                <td class="font12">{$vo['deal_user']}</td>
                <td class="font12">{$vo['manager_user']}</td>
                <td class="font12">{$vo['send_user']}</td>
                <td class="font12" title="昨日计划完成{$vo['per']}%">
                    <div class="layui-progress" lay-showpercent="true">
                        {if condition="$vo['realper'] > $vo['per']"}
                        <div class="layui-progress-bar" lay-percent="{$vo['realper']}%"></div>
                        {else/}
                        <div class="layui-progress-bar layui-bg-red" lay-percent="{$vo['realper']}%"></div>
                        {/if}
                    </div>
                </td>
                <td>
                    <!--                    暂时屏蔽此功能-->
                    <div class="layui-btn-group">
                        <a href="{:url('editTask',['id'=>$vo['id'],'pid'=>$vo['pid'],'type'=>$type,'project_name'=>$vo['project_name']])}" class="layui-btn layui-btn-normal layui-btn-xs">
                            {if condition="($vo['status'] eq 0) && ($Request.param.type eq 1) "}
                            汇报
                            {elseif condition="($vo['status'] eq 0) && ($Request.param.type eq 2) "}
                            查看汇报
                            {else/}
                            查看汇报
                            {/if}
                        </a>
                    </div>
                    {if condition="$vo['u_res'] eq 'a'"}
                    <span style="color: red;">已确认</span>
                    {else/}
                    <div class="layui-btn-group" onclick="accept_task({$vo['id']},{$Request.param.type})">
                        <a class="layui-btn layui-btn-normal layui-btn-xs">确认</a>
                    </div>
                    {/if}
                    {if condition="($vo['status'] eq 0) && ($Request.param.type eq 2) "}
                    <!--                    <div class="layui-btn-group" onclick="check_result({$vo['id']},'{$vo['name']}')">-->
                    <!--                        <a class="layui-btn layui-btn-normal layui-btn-xs">审核</a>-->
                    <!--                    </div>-->
                    {if condition="($vo['realper'] egt 100) && ($vo['real_score'] eq 0) "}
                    <div class="layui-btn-group" onclick="add_score({$vo['id']},'{$vo['code']}','{$vo['name']}')">
                        <a class="layui-btn layui-btn-normal layui-btn-xs">评分</a>
                    </div>
                    {elseif condition="($vo['realper'] lt 100)"/}
                    <span style="color: green;">待完成</span>
                    {else/}
                    <span style="color: red;">已评定</span>
                    {/if}
                    {/if}
                    <!--                    {if condition="($vo['status'] eq 0) && ($Request.param.type eq 2) "}-->
                    <!--                    <div class="layui-btn-group" onclick="finish_task({$vo['id']},{$Request.param.type})">-->
                    <!--                        <a class="layui-btn layui-btn-normal layui-btn-xs">完结</a>-->
                    <!--                    </div>-->
                    <!--                    {elseif condition="$vo['status'] eq 1"}-->
                    <!--                        <span style="color: red;">已完结</span>-->
                    <!--                    {else/}-->
                    <!--                        <span>进行中</span>-->
                    <!--                    {/if}-->
                </td>
            </tr>
            {/volist}
            </tbody>
        </table>
        {$pages}
    </div>
</div>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });


    });

    function add_score(id,code,pname){
        var open_url = "{:url('addScore')}?id="+id+"&code="+code+"&pname="+pname;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'评分',
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
    function accept_task(id,type) {
        var open_url = "{:url('setConfirm')}?id="+id+"&type="+type;
        $.post(open_url, function(res) {
            if (res.code == 1) {
                layer.msg(res.msg);
                location.reload();
            }else {
                layer.msg(res.msg);
                location.reload();
            }
        });
    }

    function finish_task(id,type) {
        var open_url = "{:url('setStatus')}?id="+id+"&type="+type;
        $.post(open_url, function(res) {
            if (res.code == 1) {
                layer.alert(res.msg);
                location.reload();
            }else {
                layer.alert(res.msg);
            }
        });
    }

    function check_result(id,pname){
        var open_url = "{:url('Project/checkResult')}?id="+id+"&pname="+pname;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :pname,
            maxmin: true,
            area: ['900px', '700px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    new SelectBox($('.box1'),{$project_select},function(result){
        if ('' != result.id){
            $('#project_name').val(result.name);
            $('#project_id').val(result.id);
        }
    },{
        dataName:'name',//option的html
        dataId:'id',//option的value
        fontSize:'14',//字体大小
        optionFontSize:'14',//下拉框字体大小
        textIndent:4,//字体缩进
        color:'#000',//输入框字体颜色
        optionColor:'#000',//下拉框字体颜色
        arrowColor:'#D2D2D2',//箭头颜色
        backgroundColor:'#fff',//背景色颜色
        borderColor:'#D2D2D2',//边线颜色
        hoverColor:'#009688',//下拉框HOVER颜色
        borderWidth:1,//边线宽度
        arrowBorderWidth:0,//箭头左侧分割线宽度。如果为0则不显示
        // borderRadius:5,//边线圆角
        placeholder:'输入关键字搜索',//默认提示
        defalut:'{$Request.param.project_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:300,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>