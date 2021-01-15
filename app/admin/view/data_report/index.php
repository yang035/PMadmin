<div id="total_company_count" style="height: 300px;width: 33%;float: left"></div>
<div id="company_count" style="height: 300px;width: 33%;float: left"></div>
<div id="user_count" style="height: 300px;width: 33%;float: left"></div>
<div id="total_person_count" style="height: 300px;width: 33%;float: left"></div>
<div id="person_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_login_count" style="height: 300px;width: 33%;float: left"></div>
<div id="login_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_shoporder_count" style="height: 300px;width: 33%;float: left"></div>
<div id="shoporder_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_mealorder_count" style="height: 300px;width: 33%;float: left"></div>
<div id="mealorder_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_menu_count" style="height: 300px;width: 33%;float: left"></div>
<div id="menu_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_pv_count" style="height: 300px;width: 33%;float: left"></div>
<div id="pv_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_uv_count" style="height: 300px;width: 33%;float: left"></div>
<div id="uv_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_vv_count" style="height: 300px;width: 33%;float: left"></div>
<div id="vv_count" style="height: 300px;width: 33%;float: left"></div>
<hr>
<div id="total_ip_count" style="height: 300px;width: 33%;float: left"></div>
<div id="ip_count" style="height: 300px;width: 33%;float: left"></div>

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
    pie_chart('user_count','用户对比',"{:url('admin/DataReport/getUserCount')}",'pie');
    bar_waterfall('total_company_count','累计公司数',"{:url('admin/DataReport/getCompanyCount')}",'line',1);
    waterfall('company_count','每月新增公司',"{:url('admin/DataReport/getCompanyCount')}",'bar');
    bar_waterfall('total_person_count','累计人员数',"{:url('admin/DataReport/getPersonCount')}",'line',1);
    waterfall('person_count','每月新增人员',"{:url('admin/DataReport/getPersonCount')}",'bar');
    bar_waterfall('total_login_count','累计登录数',"{:url('admin/DataReport/getLoginCount')}",'line',1);
    waterfall('login_count','每月登录次数',"{:url('admin/DataReport/getLoginCount')}",'bar');
    bar_waterfall('total_shoporder_count','累计商品订单量',"{:url('admin/DataReport/getShopOrderCount')}",'line',1);
    waterfall('shoporder_count','每月商品订单',"{:url('admin/DataReport/getShopOrderCount')}",'bar');
    bar_waterfall('total_mealorder_count','累计套餐订单量',"{:url('admin/DataReport/getMealOrderCount')}",'line',1);
    waterfall('mealorder_count','每天套餐订单',"{:url('admin/DataReport/getMealOrderCount')}",'bar');
    bar_waterfall('total_menu_count','累计菜单访问前10',"{:url('admin/DataReport/getMenuCount')}",'line',1);
    setTimeout("waterfall('menu_count','每月菜单访问前10',\"{:url('admin/DataReport/getMenuCount')}\",'bar')",1000);
    bar_waterfall('total_pv_count','累计PV',"{:url('admin/DataReport/getPvCount')}",'line',1);
    setTimeout("waterfall('pv_count','PV',\"{:url('admin/DataReport/getPvCount')}\",'line')",1000);
    bar_waterfall('total_uv_count','累计UV',"{:url('admin/DataReport/getUvCount')}",'line',1);
    setTimeout("waterfall('uv_count','UV',\"{:url('admin/DataReport/getUvCount')}\",'line')",1000);
    bar_waterfall('total_vv_count','累计VV',"{:url('admin/DataReport/getVvCount')}",'line',1);
    setTimeout("waterfall('vv_count','VV',\"{:url('admin/DataReport/getVvCount')}\",'line')",1000);
    bar_waterfall('total_ip_count','累计IP',"{:url('admin/DataReport/getIpCount')}",'line',1);
    setTimeout("waterfall('ip_count','IP',\"{:url('admin/DataReport/getIpCount')}\",'line')",1000);



    function pie_chart(id,title,_url,type) {
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
                    type: type,
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

    function waterfall(id,title,_url,type) {
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
                data: [title],
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
                    name: title,
                    type: type,
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

    function bar_waterfall(id,title,_url,type,p=0) {
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
                data: [title],
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
                    name: title,
                    type: type,
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
            data: {p:p},
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
