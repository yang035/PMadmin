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
                    <label class="layui-form-label" style="width: 20px">省</label>
                    <div class="layui-input-inline" style="width: 150px">
                        <select name="province" class="layui-input field-province" type="select" lay-filter="province" id="province_id">
                            {$province}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label" style="width: 20px">市</label>
                    <div class="layui-input-inline" style="width: 150px">
                        <select name="city" class="field-city" type="select" lay-filter="city" lay-filter="city" id="city_id">
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="cat_id" class="field-cat_id" type="select">
                            {$cat_option}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">岗位</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" value="{:input('get.title')}" placeholder="岗位关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('addItem')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=zhaopin_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=zhaopin_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
<!--        <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="user_table"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=zhaopin_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a onclick="read({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">查看</a>
    <a href="{:url('editItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
<!--    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/javascript">
    layui.use(['table','jquery', 'laydate', 'upload', 'form'], function() {
        var table = layui.table;$ = layui.jquery, laydate = layui.laydate, upload = layui.upload, form = layui.form;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'title', title: '岗位',width:200,templet:function(d){
                        return "<a class='mcolor' onclick='read("+d.id+")'>"+d.title+"</a>";
                    }},
                {field: 'sort', title: '排序',width:80,edit:true},
                {field: 'money', title: '月薪(元)',width:150,templet:function(d){
                        return d.min_money+"~"+d.max_money;
                    }},
                {field: 'region_name', title: '工作城市',width:100},
                {field: 'education', title: '学历',width:100},
                {field: 'experience', title: '工作经验',width:100},
                {field: 'tags', title: '标签'},
                // {field: 'attachment', title: '简历附件',width:90, templet:function(d){
                //         return "<a target='_blank' class='mcolor' href='"+d.attachment+"' >附件</a>";
                //     }},
                {field: 'status', title: '状态',width:120, templet: '#statusTpl'},
                {field: 'user_name', title: '操作员',width:80},
                {field: 'create_time', title: '添加时间',width:160},
                {title: '操作', templet: '#buttonTpl',width:160}
            ]]
        });

        //监听单元格编辑
        table.on('edit(user_table)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            // layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
            var open_url = "{:url('setKV')}";
            $.post(open_url, {
                t:'resume_item',
                id:data.id,
                k:field,
                v:value,
            },function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                    // location.reload();
                }else {
                    layer.msg(res.msg);
                    // location.reload();
                }
            });
        });

        form.on('select(province)', function(data){
            select_city(data.value);
        });

        select_city({$ip_region['p']},{$ip_region['c']});

        function select_city(province,type){
            var open_url = "{:url('Resources/getCity')}?province="+province+"&type="+type;
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

        //监听单元格编辑
        table.on('edit(user_table)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            // layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
            var open_url = "{:url('setKV')}";
            $.post(open_url, {
                t:'zhaopin_item',
                id:data.id,
                k:field,
                v:value,
            },function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                    // location.reload();
                }else {
                    layer.msg(res.msg);
                    // location.reload();
                }
            });
        });
    });

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
</script>