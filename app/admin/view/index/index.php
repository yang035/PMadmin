<div id="daka" class="layui-btn" style="display:none;">打卡</div>
<div id="container" style="height: 500px"></div>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
<!--<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=ZUONbpqGBsYGXNIYHicvbAbM"></script>-->
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
            }
        });

        $('#daka').click(function () {
            getLocation();
        });

        function getLocation()
        {
            if (navigator.geolocation)
            {
                navigator.geolocation.getCurrentPosition(showPosition);
            }else{
                layer.alert('用户拒绝了获取定位请求！');
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

    });
</script>
