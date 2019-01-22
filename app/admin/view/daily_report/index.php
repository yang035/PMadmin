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
                    <label class="layui-form-label">项目名称</label>
                    <div class="layui-input-inline box box1">
                    </div>
                    <input id="project_name" type="hidden" name="project_name" value="{$Request.param.project_name}">
                    <input id="project_id" type="hidden" name="project_id" value="{$Request.param.project_id}">
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">真实姓名</label>
                    <div class="layui-input-inline box box2">
                    </div>
                    <input id="real_name" type="hidden" name="real_name" value="{$Request.param.real_name}">
                    <input id="user_id" type="hidden" name="user_id" value="{$Request.param.user_id}">
                </div>
                <input type="hidden" name="type" value="{$Request.param.type}">
                <input type="hidden" name="atype" value="{$Request.param.atype}">
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
                    <th>名称</th>
                    <th>姓名</th>
                    <th>汇报人</th>
                    <th>计划完成(%)</th>
                    <th>实际完成(%)</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {volist name="data_list" id="vo"}
                <tr>
                    <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                    <td class="font12">
                        <a href="{:url('read',['id'=>$vo['id'],'atype'=>$atype])}"><strong class="mcolor">{$vo['project_name']}</strong></a>
                    </td>
                    <td class="font12">{$vo['user_id']}</td>
                    <td class="font12">{$vo['send_user']}</td>
                    <td class="font12">{$vo['per']}</td>
                    <td class="font12">{$vo['real_per']}</td>
                    <td class="font12">{$vo['create_time']}</td>
                    <td>
                        <div class="layui-btn-group">
                            <a href="{:url('read',['id'=>$vo['id'],'atype'=>$atype])}" class="layui-btn layui-btn-normal layui-btn-xs">查看</a>
                        </div>
                    </td>
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
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });


    });

    function add_score(id,pname){
        var open_url = "{:url('ProjectScore/add')}?id="+id+"&pname="+pname;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加',
            maxmin: true,
            area: ['800px', '500px'],
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
        placeholder:'输入关键字',//默认提示
        defalut:'{$Request.param.project_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:200,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
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