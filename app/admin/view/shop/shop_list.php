<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="cat_id" class="field-cat_id" type="select">
                            {$cat_option}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="名称关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
                <div class="fr">您的可用麦粒：<span style="color: red">{$score}</span>斗</div>
            </div>
        </form>
    </div>
</div>
{notempty name="list"}
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>优惠信息</legend>
</fieldset>
<ul class="layui-timeline">
{volist name="list" id="vo"}
    <li><a class='mcolor' href="{:url('shopDetail',['id'=>$vo['id']])}">{$vo['content']}</a></li>
{/volist}
</ul>
{/notempty}
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>商品展示区</legend>
</fieldset>
<div class="flow-default layui-row layui-col-space2" id="LAY_demo1"></div>

{include file="block/layui" /}
<script>
    layui.use('flow', function(){
        var flow = layui.flow;

        flow.load({
            elem: '#LAY_demo1' //流加载容器
            ,scrollElem: '#LAY_demo1' //滚动条所在元素，一般不用填，此处只是演示需要。
            ,done: function(page, next){ //执行下一页的回调
                //模拟数据插入
                // setTimeout(function(){
                    var lis = [];
                    $.get("{:url('Shop/shopList')}?page="+page, function(res){
                        //假设你的列表返回在data集合中
                        // console.log(res);
                        layui.each(res.data, function(index, item){
                            lis.push("" +
                                "<div class='layui-col-xs6 layui-col-sm6 layui-col-md4 fl' style='text-align: center'><a href='javascript:void(0);' onclick='read("+item.id+")'>" +
                                "<div><img width='100' height='100' alt='"+item.name+"' src='"+item.thumb+"'></div>" +
                                "<div><span><b>"+item.name+"</b></span></div>" +
                                "<div><span style='color: #FF5722'>"+item.score+"斗</span></div>" +
                                "</a></div>" +
                                "");
                        });
                    //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                    //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                    next(lis.join(''), page < res.count); //假设总页数为 10
                // }, 500);
            });
            }
        });
    });

    function read(id){
        var open_url = "{:url('shopDetail')}?id="+id;
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