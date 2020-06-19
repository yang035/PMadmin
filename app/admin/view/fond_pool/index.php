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
                <label class="layui-form-label">真实姓名</label>
                <div class="layui-input-inline box box2">
                </div>
                <input id="real_name" type="hidden" name="real_name" value="{$Request.param.real_name}">
                <input id="user_id" type="hidden" name="user_id" value="{$Request.param.user_id}">
            </div>
            <input type="hidden" name="type" value="{$Request.param.type}">
            <input type="hidden" name="export" value="">
            <button type="submit" class="layui-btn layui-btn-normal normal_btn">搜索</button>
<!--            <input type="button" class="layui-btn layui-btn-primary layui-icon export_btn" value="导出">-->
        </div>
    </form>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    layui.use(['jquery', 'laydate','table'], function() {
        var $ = layui.jquery,laydate = layui.laydate,table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,where: {
                user_id: '{$Request.param.user_id}',
                real_name: '{$Request.param.real_name}'}
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'xuhao', title: '序号',type: 'numbers'},
                {field: 'user_name', title: '姓名'},
                {field: 'add_fond', title: '增加'},
                {field: 'sub_fond', title: '提现扣除'},
                {field: 'remark', title: '备注'},
                {field: 'user_id', title: '操作员'},
                {field: 'update_time', title: '操作时间'},
            ]]
        });
    });

    new SelectBox($('.box2'),{$user_select},function(result){
        if ('' != result.id){
            $('#real_name').val(result.name);
            $('#user_id').val(result.id);
        }
    },{
        dataName:'realname',//option的html
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
        placeholder:'输入关键字',//默认提示
        defalut:'{$Request.param.real_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:200,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>