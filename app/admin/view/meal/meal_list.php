<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script type="text/javascript">
    var formData = {:json_encode($data_info)};
    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,where: {qu_type: '{$Request.param.qu_type}', }
            ,page: true //开启分页
            ,limit: 50
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                // {field: 'cat_id', title: '分类',width:100, templet:function(d){
                //         return d.cat.name;
                //     }},
                {field: 'name', title: '内容',width:150},
                {field: 'taocan_1', title: "{$taocan_config['taocan_1']} <a class='layui-btn layui-btn-xs layui-btn-normal' onclick=\"read('taocan_1',{$Request.param.qu_type})\">购买</a><br><span class='mcolor' style='font-size: larger'><strong>0</strong></span> 元/天(起)", templet:function(d){
                    if (d.meal_type == 1){
                        return d.taocan_1 == 1 ? '&#10003' : '&#10005';
                    }else {
                        return d.taocan_1;
                    }
                }},
                {field: 'taocan_2', title: "{$taocan_config['taocan_2']} <a class='layui-btn layui-btn-xs layui-btn-normal' onclick=\"read('taocan_2',{$Request.param.qu_type})\">购买</a><br><span class='mcolor' style='font-size: larger'><strong>6.66</strong></span> 元/天(起)", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_2 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_2;
                        }
                    }},
                {field: 'taocan_3', title: "{$taocan_config['taocan_3']} <a class='layui-btn layui-btn-xs layui-btn-normal' onclick=\"read('taocan_3',{$Request.param.qu_type})\">购买</a><br><span class='mcolor' style='font-size: larger'><strong>59.99</strong></span> 元/天(起)", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_3 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_3;
                        }
                    }},
                {field: 'taocan_4', title: "{$taocan_config['taocan_4']} <a class='layui-btn layui-btn-xs layui-btn-normal' onclick=\"read('taocan_4',{$Request.param.qu_type})\">购买</a><br><span class='mcolor' style='font-size: larger'><strong>130.46</strong></span> 元/天(起)", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_4 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_4;
                        }
                    }},
                {field: 'taocan_5', title: "{$taocan_config['taocan_5']} <a class='layui-btn layui-btn-xs layui-btn-normal' onclick=\"read('taocan_5',{$Request.param.qu_type})\">购买</a><br><span class='mcolor' style='font-size: larger'><strong>195.69</strong></span> 元/天(起)", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_5 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_5;
                        }
                    }},
            ]]
        });
    });
    function read(taocan,qu_type){
        var open_url = "{:url('mealDetail')}?p="+taocan+"&qu_type="+qu_type;
        window.location.href = open_url;
    }
</script>