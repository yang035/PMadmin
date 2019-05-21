<style type="text/css">
    #allmap {width: 100%;height: 500px;margin:0;overflow: hidden;font-family:"微软雅黑";}
</style>
<div id="daka" style="display: none">
<div style=" display:block" id="allmap"></div>
    <a href="javascript:void(0)" class="layui-btn layui-btn-danger layui-btn-radius clockin" style="width: 150px;height: 80px;font-size: xx-large;padding-top: 20px">打卡</a>
</div>
<hr>
<div id="container" style="height: 500px"></div>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=toiMZBRlpONvNLxNqv8xYrq95ly6x1Z1"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/bmap.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/simplex.js"></script>
<script type="text/javascript">
    var dom = document.getElementById("container");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    app.title = '数据汇总';

    option = {
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        legend: {
            data: {$x}
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis:  {
            type: 'value'
        },
        yAxis: {
            type: 'category',
            data: {$y}
        },
        series: {$data}
    };
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
    myChart.on('click', function (params) {
        var bar_id = params.seriesIndex;
        // console.log(bar_id);
        // 控制台打印数据的名称
        if (0 == params.dataIndex){
            switch (bar_id) {
                case 0:
                    bar_id = 3;
                    break;
                case 1:
                    bar_id = 4;
                    break;
                case 2:
                    bar_id = 5;
                    break;
                case 3:
                    bar_id = 2;
                    break;
                case 4:
                    bar_id = 1;
                    break;
                default:
                    bar_id = 1;
                    break;
            }
            window.open("{:url('Project/mytask')}?type="+ encodeURIComponent(bar_id));
        } else if (1 == params.dataIndex) {
            switch (bar_id) {
                case 0:
                    bar_id = 3;
                    break;
                case 1:
                    bar_id = 4;
                    break;
                case 2:
                    bar_id = 5;
                    break;
                case 3:
                    bar_id = 2;
                    break;
                case 4:
                    bar_id = 1;
                    break;
                default:
                    bar_id = 1;
                    break;
            }
            window.open("{:url('task/mytask')}?type="+ encodeURIComponent(bar_id));
        } else if (2 == params.dataIndex) {
            switch (bar_id) {
                case 0:
                    bar_id = 3;
                    break;
                case 1:
                    bar_id = 4;
                    break;
                case 2:
                    bar_id = 6;
                    break;
                case 4:
                    bar_id = 5;
                    break;
                case 5:
                    bar_id = 2;
                    break;
                default:
                    bar_id = 1;
                    break;
            }
            window.open("{:url('Approval/index')}?atype="+ encodeURIComponent(bar_id));
        } else if (3 == params.dataIndex) {
            switch (bar_id) {
                case 0:
                    bar_id = 3;
                    break;
                case 1:
                    bar_id = 4;
                    break;
                case 2:
                    bar_id = 5;
                    break;
                case 5:
                    bar_id = 2;
                    break;
                default:
                    bar_id = 1;
                    break;
            }
            window.open("{:url('DailyReport/index')}?atype="+ encodeURIComponent(bar_id));
        }
    });
</script>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;

        $(function(){
            if(navigator.userAgent.match(/mobile/i)) {
                $('#daka').show();
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

    });
</script>
