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
                <input type="hidden" name="user" value="{$Request.param.user}">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
        <div class="layui-form">
            <table class="layui-table mt10" lay-even="" lay-skin="row">
                <colgroup>
                    <col width="50">
                </colgroup>
                <thead>
                <tr>
                    <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
                    <th>员工</th>
                    <th>项目名称</th>
                    <th>任务名</th>
                    <th>ML+</th>
                    <th>ML-</th>
                    <th>GL+</th>
                    <th>GL-</th>
                    <th>备注</th>
                    <th>添加时间</th>
                </tr>
                </thead>
                <tbody>
                {volist name="data_list" id="vo"}
                <tr>
                    <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                    <td class="font12">
                        <strong class="mcolor">{$vo['realname']}</strong>
                    </td>
                    <td class="font12">{$vo['subject_name']}</td>
                    <td class="font12">{$vo['pname']}</td>
                    <td class="font12">{$vo['ml_add_score']}</td>
                    <td class="font12">{$vo['ml_sub_score']}</td>
                    <td class="font12">{$vo['gl_add_score']}</td>
                    <td class="font12">{$vo['gl_sub_score']}</td>
                    <td class="font12">{$vo['remark']}</td>
                    <td class="font12">{$vo['create_time']}</td>
                </tr>
                {/volist}
                </tbody>
            </table>
            {$pages}
        </div>
    </div>
</div>

{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate'], function() {

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