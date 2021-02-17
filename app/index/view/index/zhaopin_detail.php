<style>
    .panel-body{
        padding: 100px;
    }
    .rate-counter-block{
        border: none;
        padding-top: 100px;
        padding-bottom: 20px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="wrapper-content bg-white pinside40">
                <div class="">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="section-title mb30">
                                <div class="panel-body">
                                    <span style="color: #fe9900">
                                    <h2 style="color: #fe9900">{$data_list['title']}&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-xs" style="border: 1px solid grey;" onclick="jianli()"><span style="color: #fe9900">投递简历</span></a></h2>
                                    <h3 style="color: #fe9900">{$data_list['min_money']}-{$data_list['max_money']}元/年</h3>
                                    {$data_list['tags']}<br>
                                    {$data_list['company_name']}<br>
                                    {$data_list['region_name']} | {$data_list['education']} | {$data_list['experience']}<br>
                                    </span>
                                    {$data_list['content']}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<link rel="stylesheet" href="/static/admin/js/layui/css/layui.css?v=">-->
<script src="/static/admin/js/layui/layui.js?v="></script>
<link rel="stylesheet" href="/static/js/layer/skin/default/layer.css?v=">
<script src="/static/js/layer/layer.js?v="></script>
<script>
    function jianli(){
        var id="{$Request.param.id}",cid="{$Request.param.cid}",title="{$data_list['title']}";
        var open_url = "{:url('jianli')}?id="+id+"&cid="+cid+"&title="+title;
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