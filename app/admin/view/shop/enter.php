<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div style="padding: 20px; background-color: #F2F2F2;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">福利销购</div>
                <div class="layui-card-body">
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
                </div>
            </div>
        </div>
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">个人业绩</div>
                <div class="layui-card-body">
                    {notempty name="tmp"}
                    {volist name="tmp" id="vo"}
                    <b>员工编号：</b>{$user[$vo['uid']]['id_card']}<br>
                    <b>累计ML：</b>{$vo['ml']}<br>
                    <b>已完成ML：</b>{$vo['finish_ml']}<br>
                    <b>未完成ML：</b>{$vo['finish_ml_no']}<br>
                    <b>已发放ML：</b>{$vo['finish_ml_fafang']}<br>
                    <b>累计GL：</b>{$gl[$vo['uid']]['gl_add_sum']}<br>
                    <b>GL排名：</b>{$vo['rank']}<br>
                    {/volist}
                    {else/}
                    暂无发放ML
                    {/notempty}
                </div>
            </div>
        </div>
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">我的任务</div>
                <div class="layui-card-body">
<!--                    结合 layui 的栅格系统<br>-->
<!--                    轻松实现响应式布局-->
                </div>
            </div>
        </div>
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">文件库</div>
                <div class="layui-card-body">
<!--                    结合 layui 的栅格系统<br>-->
<!--                    轻松实现响应式布局-->
                </div>
            </div>
        </div>
    </div>
</div>
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
        window.location.href = open_url;
    }
    
    function getYeji() {
        
    }
</script>