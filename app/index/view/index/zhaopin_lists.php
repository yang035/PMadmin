<style>
    .rate-counter-block{
        border: none;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
<div class="container">
    <div class="row" style="margin-top: 120px;margin-bottom: 250px">
        <div class="col-md-12">
            <div class="wrapper-content bg-white pinside40">
                <div class="">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 st-accordion col-xs-12">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                {volist name="data_list" id="vo"}
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div style="float: left;width: 200px">
                                            <a href="{:url('zhaopin_detail',['id'=>$vo['id'],'cid'=>$vo['cid']])}">
                                                <span style="font-size: 20px;color:#fe9900;">{$vo['title']}</span><br>
                                                <span>{$vo['min_money']}-{$vo['max_money']} 万元/年</span><br>
                                            </a>
                                            <span>{$vo['tags']}</span>
                                        </div>
                                        <div style="float: left;margin-left: 200px">
                                            <a href="{:url('zhaopin_detail',['id'=>$vo['id'],'cid'=>$vo['cid']])}">
                                                <span style="font-size: 20px;color:#fe9900;">{$vo['company_name']}</span><br>
                                            </a>
                                            <span>{$vo['region_name']} | {$vo['education']} | {$vo['experience']}</span>
                                        </div>
                                        <div style="float: right">
                                            <a href="{:url('zhaopin_detail',['id'=>$vo['id'],'cid'=>$vo['cid']])}">
                                                <span style="font-size: 20px;color:#fe9900;">申请</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                {/volist}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center">
                {$page}
            </div>
        </div>
    </div>
</div>
