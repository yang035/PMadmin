<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="search_form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">真实姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="realname" value="{:input('get.realname')}" placeholder="真实姓名" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">选择项目</label>
                    <div class="layui-input-inline box box1">
                    </div>
                    <input id="project_name" type="hidden" name="project_name" value="{$Request.param.project_name}">
                    <input id="project_id" type="hidden" name="project_id" value="{$Request.param.project_id}">
                </div>
<!--                <div class="layui-inline">-->
<!--                    <label class="layui-form-label">任务代码</label>-->
<!--                    <div class="layui-input-inline">-->
<!--                        <input type="text" name="project_code" value="{:input('get.project_code')}" placeholder="任务代码" autocomplete="off" class="layui-input">-->
<!--                    </div>-->
<!--                </div>-->
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="test2" name="search_date" placeholder="选择日期" readonly value="{$d|default=''}">
                    </div>
                </div>
                <input type="hidden" name="type" value="{$Request.param.type}">
                <input type="hidden" name="export" value="">
                <button type="submit" class="layui-btn layui-btn-normal normal_btn">搜索</button>
                <input type="button" class="layui-btn layui-btn-primary layui-icon export_btn" value="导出">
            </div>
        </form>
    </div>
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
            <th>ML+</th>
            <th>ML-</th>
            <th>GL+</th>
            <th>GL-</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {volist name="data_list" id="vo"}
        <tr>
            <td><input type="checkbox" name="subject_id[]" class="layui-checkbox checkbox-ids" value="{$vo['subject_id']}" lay-skin="primary"></td>
            <td class="font12">
                <strong class="mcolor">{$vo['subject_name']}</strong>
            </td>
            <td class="font12">{$vo['ml_add_sum']}</td>
            <td class="font12">{$vo['ml_sub_sum']}</td>
            <td class="font12">{$vo['gl_add_sum']}</td>
            <td class="font12">{$vo['gl_sub_sum']}</td>
            <td>
                <div class="layui-btn-group">
                    <div class="layui-btn-group">
                        <a href="{:url('listByPeople',['project_name'=>$vo['subject_name'],'project_id'=>$vo['subject_id']])}" class="layui-btn layui-btn-primary layui-btn-sm">项目明细</a>
                    </div>
                </div>
            </td>
        </tr>
        {/volist}
        </tbody>
    </table>
    {$pages}
</div>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
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

    });

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