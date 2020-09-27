<style type="text/css">
    #allmap {width: 100%;height: 500px;margin:0;overflow: hidden;font-family:"微软雅黑";}
</style>
<div id="daka" style="display: none">
<div style=" display:block" id="allmap"></div>
    <a href="javascript:void(0)" class="layui-btn layui-btn-danger layui-btn-radius clockin" style="width: 150px;height: 80px;font-size: xx-large;padding-top: 20px">打卡</a>
</div>
<hr>
<div id="jihua" style="height: 300px;width: 50%;float: left"></div>
<div id="linshi" style="height: 300px;width: 50%;float: left"></div>
<hr>
<div id="shenpi" style="height: 300px;width: 50%;float: left"></div>
<div id="ribao" style="height: 300px;width: 50%;float: left"></div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts-gl/dist/echarts-gl.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts-stat/dist/ecStat.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts/dist/extension/dataTool.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts/map/js/china.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts/map/js/world.js"></script>-->
<!--<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=toiMZBRlpONvNLxNqv8xYrq95ly6x1Z1"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts/dist/extension/bmap.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/simplex.js"></script>-->
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;

        $(function(){
            if(navigator.userAgent.match(/mobile/i)) {
                // $('#daka').show();
                setTimeout(function(){
                    navigator.geolocation.getCurrentPosition(showPosition1,showError);
                }, 1000);
            }
        });

        $('.clockin').click(function () {
            getLocation();
        });
        $('.clockout').click(function () {
            getLocation();
        });

        function showPosition1(position)
        {
            //公司坐标
            var map = new BMap.Map("allmap");
            var point = new BMap.Point(114.411705,30.484671);
            var marker = new BMap.Marker(point);  // 创建标注
            map.addOverlay(marker);// 将标注添加到地图中
            var label = new BMap.Label("公司",{offset:new BMap.Size(20,-10)});
            marker.setLabel(label);
            // var pointB = new BMap.Point(114.411505,30.484671);
            //我的坐标
            var pointB = new BMap.Point(position.coords.longitude,position.coords.latitude);
            var convertor = new BMap.Convertor();//GPS转百度坐标
            var pointArr = [];
            pointArr.push(pointB);
            convertor.translate(pointArr, 3, 5, function (data){
                if(data.status === 0) {
                    var person = new BMap.Marker(data.points[0]);
                    map.addOverlay(person);
                    var label = new BMap.Label("我",{offset:new BMap.Size(20,-10)});
                    person.setLabel(label); //添加百度label
                    // map.setCenter(data.points[0]);
                    var polyline = new BMap.Polyline([point,data.points[0]], {strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5});  //定义折线
                    map.addOverlay(polyline);     //添加折线到地图上
                }
            });
            map.centerAndZoom(point, 20);
        }

        function getLocation()
        {
            if (navigator.geolocation)
            {
                navigator.geolocation.getCurrentPosition(showPosition,showError);
            }else{
                layer.alert('浏览器不支持地理定位');
            }
        }
        function showPosition(position)
        {
            var lat = position.coords.latitude,lon = position.coords.longitude;
            var open_url = "{:url('daKa')}?&lat="+lat+"&lon="+lon;
            $.post(open_url, function(res) {
                if (res.code == 1) {
                    layer.alert(res.msg);
                }else {
                    layer.alert(res.msg);
                }
            });
        }



        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    layer.alert("定位失败,用户拒绝请求地理定位");
                    break;
                case error.POSITION_UNAVAILABLE:
                    layer.alert("定位失败,位置信息是不可用");
                    break;
                case error.TIMEOUT:
                    layer.alert("定位失败,请求获取用户位置超时");
                    break;
                case error.UNKNOWN_ERR:
                    layer.alert("定位失败,定位系统失效");
                    break;
            }
        }

        var role_id = "{$Think.session.admin_user.role_id}";
        if (role_id >= 3 ){
            msg_open();
        }

        function msg_open() {
            var tmp='',open_url = "{:url('UserInfo/getWarningList')}";
            $.post(open_url, function(res) {
                if (res) {
                    $.each(res,function (i,v) {
                        tmp += v['real_name'] +',入职时间:'+ v['start_date']+'<br>'
                    });
                    layer.open({
                        title:'员工转正提醒',
                        type: 0,
                        offset: 'rb', //具体配置参考：offset参数项
                        content: '<div>'+tmp+'</div>',
                        btn: '关闭',
                        btnAlign: 'c', //按钮居中
                        shade: 0, //不显示遮罩
                        time: 3000,
                        area:['300','200'],
                        yes: function () {
                            layer.closeAll();
                        }
                    });
                }
            });
        }

    });
    // var _url = "{:url('admin/Index/getApprovalCount')}";
    pie_chart('jihua','计划统计',"{:url('admin/Index/getProjectCount')}");
    pie_chart('linshi','临时任务统计',"{:url('admin/Index/getTaskCount')}");
    pie_chart('shenpi','审批统计',"{:url('admin/Index/getApprovalCount')}");
    pie_chart('ribao','日报统计',"{:url('admin/Index/getReportCount')}");

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
                formatter: "{a} <br/>{b} : {c} ({d}%)"
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

</script>
