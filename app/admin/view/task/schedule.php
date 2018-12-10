{include file="block/layui" /}
<div class="layui-tab-item layui-form menu-dl {if condition=" $k eq 1"}layui-show{/if}">
<link rel="stylesheet" href="__ADMIN_JS__/gantt/css/style.css"/>
<link rel="stylesheet" href="__ADMIN_JS__/gantt/css/bootstrap.min.css"/>
<link href="__ADMIN_JS__/gantt/css/prettify.min.css" rel="stylesheet"/>
<style type="text/css">
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 13px;
        padding: 0 0 50px 0;
    }
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a {
        color: #333;
        text-decoration: none;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">任务名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="项目名称关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">开始时间</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-start_time" name="start_time" value="{:input('get.start_time')}" readonly autocomplete="off" placeholder="选择开始时间">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">结束时间</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-end_time" name="end_time" value="{:input('get.end_time')}" autocomplete="off" readonly placeholder="选择结束时间">
                    </div>
                </div>
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
        <div class="gantt"></div>
    </div>
</div>

<script src="__ADMIN_JS__/gantt/js/jquery-2.1.4.min.js"></script>
<script src="__ADMIN_JS__/gantt/js/bootstrap.min.js"></script>
<script src="__ADMIN_JS__/gantt/js/jquery.fn.gantt.js"></script>
<script src="__ADMIN_JS__/gantt/js/prettify.min.js"></script>
<script>
    $(function () {
        "use strict";
        //初始化gantt
        $(".gantt").gantt({
            source: {$data_list},
            itemsPerPage: 10,
            months: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            dow: ['日', '一', '二', '三', '四', '五', '六'],
            navigate: 'scroll',//buttons  scroll
            scale: "days",// months  weeks days  hours
            maxScale: "months",
            minScale: "hours",
            waitText: '加载中',
            scrollToToday:true,
            onItemClick: function (data) {
                layer.tips(data.name, '.s_tip_'+data.id, {
                    tips: [1, '#3595CC'],
                    time: 2000
                });
            },
            onAddClick: function (dt, id) {
                // alert(dt);
                // alert(id);
            },
            onRender: function () {
                if (window.console && typeof console.log === "function") {
                    console.log("chart rendered");
                }
            }
        });
    });

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload;
        laydate.render({
            elem: '.field-start_time',
            type: 'date'
        });
        laydate.render({
            elem: '.field-end_time',
            type: 'date',
        });
    });
</script>
