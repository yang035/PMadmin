<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div style="padding: 20px; background-color: #F2F2F2;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12" id="div_1">
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
                        <legend>MLGL特惠</legend>
                    </fieldset>
                    <div class="flow-default layui-row layui-col-space2" id="LAY_demo1"></div>
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
    // getActivity();
    
    function getYeji() {
        var h_t_1 = "<div class=\"layui-col-md3\">\n" +
            "            <div class=\"layui-card\">\n" +
            "                <div class=\"layui-card-header\">我的IP</div>\n" +
            "                <div class=\"layui-card-body\">";
        var h_t_2 = "</div>\n" +
            "            </div>\n" +
            "        </div>";
        $.ajax({
            type: 'POST',
            url: "{:url('score/listpeople')}",
            data: {p:1},
            dataType:  'json',
            success: function(data){
                if (data){
                    var h_t = "<b>员工编号：</b>"+data.id_card+"<br>\n" +
                        "            <b>累计ML：</b>"+data.ml+"<br>\n" +
                        "            <b>累计GL：</b>"+data.gl+"<br>\n" +
                        "            <b>排名：</b>"+data.rank+"<br>";
                    $("#div_1").append(h_t_1+h_t+h_t_2);
                }
            }
        });
    }

    function getFile() {
        var h_t_1 = "<div class=\"layui-col-md3\">\n" +
            "            <div class=\"layui-card\">\n" +
            "                <div class=\"layui-card-header\">提取共享文件</div>\n" +
            "                <div class=\"layui-card-body\">";
        var h_t_2 = "</div>\n" +
            "            </div>\n" +
            "        </div>";
        var _url = "{:url('Subject/chengguo')}?pram=2";
        $.ajax({
            type: 'POST',
            url: _url,
            dataType:  'json',
            success: function(data){
                if (data.data){
                    var h_t = "<a class='mcolor' href='"+_url+"'>提取项目资料</a>";
                    $("#div_1").append(h_t_1+h_t+h_t_2);
                }
            }
        });
    }

    function getWork() {
        var h_t_1 = "<div class=\"layui-col-md3\">\n" +
            "            <div class=\"layui-card\">\n" +
            "                <div class=\"layui-card-header\">我的工作</div>\n" +
            "                <div class=\"layui-card-body\">";
        var h_t_2 = "</div>\n" +
            "            </div>\n" +
            "        </div>";
        var _url = "{:url('Index/getWork')}",h_t='';
        var u1 = "{:url('approval/index',['atype'=>3])}",u2 = "{:url('daily_report/index',['atype'=>3])}",
            u3 = "{:url('score_deal/index',['atype'=>2])}",u4 = "{:url('project/mytask',['type'=>1])}",
            u5 = "{:url('project/mytask',['type'=>2])}";
        $.ajax({
            type: 'POST',
            url: _url,
            dataType:  'json',
            success: function(data){
                if (data.approval_daishen){
                    h_t += "<b>审批待处理：</b><a class='mcolor' style='font-size: x-large;' href='"+u1+"'>"+data.approval_daishen+"</a><br>";
                }
                if (data.report_daishen){
                    h_t += "<b>汇报待处理：</b><a class='mcolor' style='font-size: x-large;' href='"+u2+"'>"+data.report_daishen+"</a><br>";
                }
                if (data.jiangkou_daishen){
                    h_t += "<b>奖扣待处理：</b><a class='mcolor' style='font-size: x-large;' href='"+u3+"'>"+data.jiangkou_daishen+"</a><br>";
                }
                if (data.project_deal){
                    h_t += "<b>任务待完成：</b><a class='mcolor' style='font-size: x-large;' href='"+u4+"'>"+data.project_deal+"</a><br>";
                }
                if (data.project_manager){
                    h_t += "<b>任务待处理：</b><a class='mcolor' style='font-size: x-large;' href='"+u5+"'>"+data.project_manager+"</a><br>";
                }
                if (h_t){
                    $("#div_1").append(h_t_1+h_t+h_t_2);
                }
            }
        });
    }

    function getActivity() {
        var h_t_1 = "<div class=\"layui-col-md3\">\n" +
            "            <div class=\"layui-card\">\n" +
            "                <div class=\"layui-card-header\">我的活动</div>\n" +
            "                <div class=\"layui-card-body\">";
        var h_t_2 = "</div>\n" +
            "            </div>\n" +
            "        </div>";
        $("#div_1").append(h_t_1+h_t_2);
    }

    getFile();
    getWork();
    getYeji();
    // getActivity();
</script>