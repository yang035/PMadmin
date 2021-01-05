<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<h3>员工姓名：{$realname}</h3>
<fieldset class="layui-elem-field layui-field-title">
    <legend>项目归属</legend>
</fieldset>
<div class="layui-form">
    <table class="layui-table mt10" lay-even="" lay-skin="row">
        <colgroup>
            <col width="50">
        </colgroup>
        <thead>
        <tr>
            <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
            <th width="30">序号</th>
            <th>项目名</th>
            <th>累计ML</th>
            <th>已完成ML</th>
            <th>可发放ML</th>
<!--            <th>未发放ML</th>-->
            <th>合伙</th>
            <th>GL排名</th>
        </tr>
        </thead>
        <tbody>
        {volist name="tmp" id="vo"}
        <tr>
            <td><input type="checkbox" name="pid[]" class="layui-checkbox checkbox-ids" value="{$key}" lay-skin="primary"></td>
            <td>{$i}</td>
            <td class="font12">
                <strong class="mcolor">{$vo['name']}</strong>
            </td>
            <td class="font12">{$vo['ml']}</td>
            <td class="font12">{$vo['finish_ml_month']}</td>
            <td class="font12">{$vo['finish_ml_month_fafang']}</td>
<!--            <td class="font12">{$vo['finish_ml_month_nofafang']}</td>-->
            <td class="font12">{$vo['hehuo_name']}</td>
            <td class="font12">{$vo['rank']}</td>
        </tr>
        {/volist}
        </tbody>
    </table>
    {$pages}
    <fieldset class="layui-elem-field layui-field-title">
        <legend>资产归属</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">电脑配置</label>
        <div class="layui-form-mid"><a class="mcolor" href="#" onclick="computer_read({$Request.param.user})">查看</a></div>
    </div>
</div>
<hr>
{neq name="$Request.param.read" value="1"}
<div class="layui-form">
    <form class="layui-form" action="{:url()}" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="[选填]备注说明"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" name="user" value="{$Request.param.user}">
                <input type="hidden" name="approval_id" value="{$Request.param.approval_id}">
                <button type="submit" class="layui-btn layui-btn-normal normal_btn">归档提交(下一步)</button>
                <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
    </form>
</div>
{/neq}
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

    function computer_read(user) {
        var open_url = "{:url('Computer/read')}?user="+user;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'电脑配置',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

    function fafang(user,subject_id){
        var open_url = "{:url('Sendml/add')}?user="+user+'&subject_id='+subject_id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'详情',
            maxmin: true,
            area: ['500px', '400px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function edit(user,subject_id){
        var open_url = "{:url('Sendml/edit')}?user="+user+'&subject_id='+subject_id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'详情',
            maxmin: true,
            area: ['700px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function status(user,subject_id,benci_fafang){
        var open_url = "{:url('Sendml/setStatus')}?user="+user+'&subject_id='+subject_id;
        layer.confirm('本次发放 '+benci_fafang+' M', {icon: 3, title:'提示'}, function(index){
            $.post(open_url, function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                    location.reload();
                }else {
                    layer.msg(res.msg);
                    location.reload();
                }
            });
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