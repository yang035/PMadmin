<style type="text/css">
    #allmap {width: 500px;height: 500px;margin:0;overflow: hidden;font-family:"微软雅黑";}
</style>
<div style=" display:block" id="allmap"></div>
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

    layui.use(['jquery', 'laydate'], function () {
        var $ = layui.jquery, laydate = layui.laydate;

        var map = new BMap.Map("allmap");
        var pointA = new BMap.Point(114.411705,30.484671);
        map.centerAndZoom(pointA, 12);
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function (r) {
            if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                var mk = new BMap.Marker(r.point);
                map.addOverlay(mk);
                map.panTo(r.point);//r.point.lng    获取x坐标      r.point.lat获取y坐标
                alert('您的位置：'+r.point.lng+','+r.point.lat);
                var pointB = new BMap.Point(r.point.lng,r.point.lat);
                var polyline = new BMap.Polyline([pointA,pointB], {strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5});  //定义折线
                map.addOverlay(polyline);     //添加折线到地图上
                // showPosition(r.point.lng, r.point.lat);//调用ajax方法获取地理位置
            } else {
                // showPosition('120.57991', '27.997864');
            }
        }, {enableHighAccuracy: true});

        function showPosition(x, y) {
            var postData = {"subtype": "dingwei", "x": x, "y": y};// 提交表单数据到后台处理
            var url = "{:url('daKa')}";
            $.ajax({
                type: "post",
                dataType: "text",
                data: postData,
                url: url,
                success: function (json) { //执行成功
                    // alert(json);} });}
                }
            });
        }
    });
</script>
