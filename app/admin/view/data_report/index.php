<div id="user_count" style="height: 300px;width: 50%;float: left"></div>
<div id="company_count" style="height: 300px;width: 50%;float: left"></div>
<div id="person_count" style="height: 300px;width: 50%;float: left"></div>
<div id="login_count" style="height: 300px;width: 50%;float: left"></div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@4/dist/echarts.min.js"></script>
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts-gl@1/dist/echarts-gl.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts-stat@1/dist/ecStat.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@4/dist/extension/dataTool.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@4/map/js/china.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@4/map/js/world.js"></script>-->
<!--<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=xfhhaTThl11qYVrqLZii6w8qE5ggnhrY&__ec_v__=20190126"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@4/dist/extension/bmap.min.js"></script>-->
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
    });
    // var _url = "{:url('admin/Index/getApprovalCount')}";
    pie_chart('user_count','用户对比',"{:url('admin/DataReport/getUserCount')}");
    waterfall('company_count','每月新增公司',"{:url('admin/DataReport/getCompanyCount')}");
    waterfall('person_count','每月新增人员',"{:url('admin/DataReport/getPersonCount')}");
    waterfall('login_count','每月登录次数',"{:url('admin/DataReport/getLoginCount')}");
    // pie_chart('ribao','日报统计',"{:url('admin/DataReport/getReportCount')}");


    function pie_chart(id,title,_url) {
        var dom = document.getElementById(id);
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        myChart.setOption({
            title: {
                text: title,
                left: 'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{b} : {c} ({d}%)"
            },
            legend: {
                // orient: 'vertical',
                // top: 'middle',
                bottom: 10,
                left: 'center',
                data: []
            },
            series : [
                {
                    type: 'pie',
                    radius : '65%',
                    center: ['50%', '50%'],
                    selectedMode: 'single',
                    label: {
                        normal: {
                            formatter: '{b} {c}',
                            position: 'inside'
                        }
                    },
                    data:[],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        });
        myChart.showLoading();    //数据加载完之前先显示一段简单的loading动画

        $.ajax({
            type: "post",
            async: true,            //异步请求（同步请求将会锁住浏览器，用户其他操作必须等待请求完成才可以执行）
            url: _url,    //请求发送到TestServlet处
            data: {},
            dataType: "json",        //返回数据形式为json
            success: function (result) {
                //请求成功时执行该函数内容，result即为服务器返回的json对象
                if (result) {
                    myChart.hideLoading();    //隐藏加载动画
                    myChart.setOption({        //加载数据图表
                        legend: {
                            data: result.leg
                        },
                        series: [{
                            data: result.ser
                        }]
                    });

                }
            },
            error: function (errorMsg) {
                //请求失败时执行该函数
                alert("图表请求数据失败!");
                myChart.hideLoading();
            }
        });

        myChart.on('click', function (params) {
            var bar_id = params.dataIndex;
            // console.log(bar_id);
            // 控制台打印数据的名称
            if ('jihua' == id){
                // switch (bar_id) {
                //     case 0:
                //         bar_id = 1;
                //         break;
                //     case 1:
                //         bar_id = 4;
                //         break;
                //     case 2:
                //         bar_id = 5;
                //         break;
                //     case 3:
                //         bar_id = 2;
                //         break;
                //     case 4:
                //         bar_id = 1;
                //         break;
                //     default:
                //         bar_id = 1;
                //         break;
                // }
                window.open("{:url('Project/mytask')}?type="+ encodeURIComponent(bar_id+1));
            } else if ('linshi' == id) {
                // switch (bar_id) {
                //     case 0:
                //         bar_id = 3;
                //         break;
                //     case 1:
                //         bar_id = 4;
                //         break;
                //     case 2:
                //         bar_id = 5;
                //         break;
                //     case 3:
                //         bar_id = 2;
                //         break;
                //     case 4:
                //         bar_id = 1;
                //         break;
                //     default:
                //         bar_id = 1;
                //         break;
                // }
                window.open("{:url('task/mytask')}?type="+ encodeURIComponent(bar_id+1));
            } else if ('shenpi' == id) {
                switch (bar_id) {
                    case 0:
                        bar_id = 3;
                        break;
                    case 1:
                        bar_id = 2;
                        break;
                    case 2:
                        bar_id = 4;
                        break;
                    case 4:
                        bar_id = 5;
                        break;
                    case 5:
                        bar_id = 6;
                        break;
                    case 6:
                        bar_id = 7;
                        break;
                    default:
                        bar_id = 1;
                        break;
                }
                window.open("{:url('Approval/index')}?atype="+ encodeURIComponent(bar_id));
            } else if ('ribao' == id) {
                switch (bar_id) {
                    case 0:
                        bar_id = 3;
                        break;
                    case 1:
                        bar_id = 2;
                        break;
                    case 2:
                        bar_id = 4;
                        break;
                    case 5:
                        bar_id = 5;
                        break;
                    default:
                        bar_id = 1;
                        break;
                }
                window.open("{:url('DailyReport/index')}?atype="+ encodeURIComponent(bar_id));
            }
        });
    }

    function waterfall(id,title,_url) {
        var dom = document.getElementById(id);
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        myChart.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow',
                        label: {
                            show: true
                        }
                    }
                },
                toolbox: {
                    show: true,
                    feature: {
                        magicType: {show: true, type: ['line', 'bar']},
                        saveAsImage: {show: true}
                    }
                },
                calculable: true,
                legend: {
                    data: ['新增公司'],
                    itemGap: 5
                },
                grid: {
                    top: '12%',
                    left: '1%',
                    right: '10%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: []
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        name: title,
                        axisLabel: {
                            formatter: function (a) {
                                a = +a;
                                return isFinite(a)
                                    ? echarts.format.addCommas(+a / 1)
                                    : '';
                            }
                        }
                    }
                ],
                series: [
                    {
                        name: '新增公司',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'inside'
                        },
                        data: []
                    }
                ]
        });
        myChart.showLoading();    //数据加载完之前先显示一段简单的loading动画

        $.ajax({
            type: "post",
            async: true,            //异步请求（同步请求将会锁住浏览器，用户其他操作必须等待请求完成才可以执行）
            url: _url,    //请求发送到TestServlet处
            data: {},
            dataType: "json",        //返回数据形式为json
            success: function (result) {
                //请求成功时执行该函数内容，result即为服务器返回的json对象
                if (result) {
                    myChart.hideLoading();    //隐藏加载动画
                    myChart.setOption({        //加载数据图表
                        xAxis: {
                            data: result.leg
                        },
                        series: [{
                            data: result.ser
                        }]
                    });

                }
            },
            error: function (errorMsg) {
                //请求失败时执行该函数
                alert("图表请求数据失败!");
                myChart.hideLoading();
            }
        });
    }

</script>
