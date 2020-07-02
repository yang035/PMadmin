<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a:hover{
        cursor:pointer
    }
</style>
<div>
    <div>
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
                <label class="layui-form-label">省</label>
                <div class="layui-input-inline">
                    <select name="province" class="layui-input field-province" type="select" lay-filter="province" lay-search>
                        {$province}
                    </select>
                </div>
                <label class="layui-form-label">市</label>
                <div class="layui-input-inline">
                    <select name="city" class="field-city" type="select" lay-filter="city" lay-filter="city" id="city_id">
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" value="{:input('get.title')}" placeholder="关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <input type="hidden" id="p" name="p" value="">
                <input type="hidden" id="c" name="c" value="">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=subject_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=subject_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <!--            <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=subject_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('edit')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
</script>
<script type="text/javascript">
    layui.use(['jquery','table','form'], function() {
        var $ = layui.jquery,table = layui.table,form = layui.form;
        table.render({
            elem: '#dataTable'
            ,height: 'full-200'
            ,url: '{:url()}' //数据接口
            ,page: true //开启分页
            ,limit: 30
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {field: 'xuhao', title: '序号',type: 'numbers'},
                {field: 'title', title: '项目信息',templet:function(d){
                        var str = "<a class='mcolor' onclick='read("+d.id+")'>"+d.title+"</a><br>";
                        str += "省份："+d.districtShow+"&nbsp;&nbsp;&nbsp;&nbsp;来源平台："+d.platformName+"&nbsp;&nbsp;&nbsp;&nbsp;业务类型："+d.classifyShow+"&nbsp;&nbsp;&nbsp;&nbsp;信息类型："+d.stageShow+"&nbsp;&nbsp;&nbsp;&nbsp;行业："+d.tradeShow+"&nbsp;&nbsp;&nbsp;&nbsp;发布时间："+d.timeShow;
                        return str;
                    }},
            ]]
        });

        form.on('select(province)', function(data){
            select_city(data.value);
            console.log(data.othis);
        });

        function select_city(province,type){
            var open_url = "{:url('getCity')}?province="+province+"&type="+type;
            $.ajax({
                type: 'POST',
                url: open_url,
                dataType:  'json',
                success: function(data){
                    $('#city_id').html(data);
                    form.render('select');
                }
            });
        }

        form.on('select(city)', function(data){
            $('#c').val(data.name);
        });
    });

    function read(id){
        var open_url = "{:url('read')}?id="+id+"&atype=0101";
        window.location.href = open_url;
    }
</script>