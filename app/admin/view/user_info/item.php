<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a:hover{
        cursor:pointer
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">真实姓名</label>
                    <div class="layui-input-inline box box2">
                    </div>
                    <input id="real_name" type="hidden" name="real_name" value="{$Request.param.real_name}">
                    <input id="user_id" type="hidden" name="user_id" value="{$Request.param.user_id}">
                </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="#" onclick="add_user1('addItem',{$Request.param.user_id|default=0})" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=user_info&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=user_info&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <!--            <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script type="text/html" id="statusTpl1">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=user_info&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a onclick="read1({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">查看</a>
    <a href="#" onclick="add_user1('editItem',{{ d.user_id }},{{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
<!--    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl2">
    {{#  if(d.check_status == 1){ }}
    <span style="color: green">已审核({{ d.check_name }})</span>
    {{#  }else if(d.check_status == 2){ }}
    <a onclick="read2({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">审核</a>
    <span style="color: red">驳回({{ d.check_name }}_{{ d.remark }})</span>
    {{#  }else{ }}
    <a onclick="read2({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">审核</a>
    {{#  } }}
    {{#  if(d.approval_id){ }}
    <a href="{:url('approval/leavefile')}?user={{ d.user_id }}&approval_id={{ d.approval_id }}&read=1" class="layui-btn layui-btn-xs layui-btn-normal">数据档案</a>
    <a href="{:url('approval/leavelist')}?user={{ d.user_id }}&approval_id={{ d.approval_id }}&read=1" class="layui-btn layui-btn-xs layui-btn-normal">离职清单</a>
    {{#  } }}
</script>
<script type="text/javascript">
    layui.use(['jquery','table'], function() {
        var $ = layui.jquery,table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,where: {subject_id: '{$Request.param.subject_id}', }
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'xuhao', title: '序号',type: 'numbers'},
                {field: 'real_name', title: '姓名',sort: true},
                {field: 'birthday', title: '生日',sort: true},
                {field: 'start_date', title: '入职时间',sort: true},
                {field: 'operator_name', title: '录入员'},
                {field: 'status', title: '状态', templet: '#statusTpl1'},
                {title: '操作', templet: '#buttonTpl'},
                {title: '审核信息', templet: '#buttonTpl2'},
            ]]
        });
    });

    function add_user(url,user_id,id) {
        var open_url = "{:url('"+url+"')}?user_id="+user_id+"&id="+id+"&real_name={$Request.param.real_name}";
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加/编辑记录',
            maxmin: true,
            area: ['1000px', '800px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

    function add_user1(url,user_id,id) {
        var open_url = "{:url('"+url+"')}?user_id="+user_id+"&id="+id+"&real_name={$Request.param.real_name}";
        window.location.href = open_url;
    }

    function read(id){
        var open_url = "{:url('read')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'详情',
            maxmin: true,
            area: ['800px', '600px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function read1(id){
        var open_url = "{:url('read')}?id="+id;
        window.location.href = open_url;
    }

    function read2(id){
        var open_url = "{:url('read')}?id="+id+"&p=1";
        window.location.href = open_url;
    }

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