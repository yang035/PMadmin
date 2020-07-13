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
                <div class="layui-input-inline">
                    <input type="text" name="realname" value="{:input('get.realname')}" placeholder="真实姓名" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">日期范围</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="test2" name="search_date" placeholder="选择日期" readonly>
                </div>
            </div>
            <input type="hidden" name="type" value="{$Request.param.type}">
            <input type="hidden" name="export" value="">
            <button type="submit" class="layui-btn layui-btn-normal normal_btn">搜索</button>
            <input type="button" class="layui-btn layui-btn-primary layui-icon export_btn" value="导出">
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
            <th width="30">序号</th>
            <th>姓名</th>
            <th>累计ML</th>
            <th>已完成ML</th>
            <th>可发放ML</th>
<!--            <th>未发放ML</th>-->
            <th>ML排名</th>
            <th>GL排名</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {volist name="tmp" id="vo"}
        <tr>
            <td><input type="checkbox" name="uid[]" class="layui-checkbox checkbox-ids" value="{$vo['uid']}" lay-skin="primary"></td>
            <td>{$i}</td>
            <td class="font12">
                <strong class="mcolor">{$user[$vo['uid']]['realname']}</strong>
            </td>
            <td class="font12">{$vo['ml']}</td>
            <td class="font12">{$vo['finish_ml_month']}</td>
            <td class="font12">{$vo['finish_ml_month_fafang']}</td>
<!--            <td class="font12">{$vo['finish_ml_month_nofafang']}</td>-->
            <td class="font12">{$key+1}</td>
            <td class="font12">{$vo['rank']}</td>
            <td>
                <div class="layui-btn-group">
                    <div class="layui-btn-group">
                        <a href="{:url('listPeoplePM',['user'=>$vo['uid']])}" class="layui-btn layui-btn-primary layui-btn-sm">项目明细</a>
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