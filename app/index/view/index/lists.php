<style>
    .rate-counter-block{
        border: none;
        padding-top: 20px;
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
                                <h1>{$index_tab[$Request.param.id]['title']}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 st-accordion col-xs-12">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                {volist name="data_list" id="vo"}
                                <div class="panel panel-default">
                                    <div class="col-md-2 col-sm-2 st-accordion col-xs-2"><img class="col-md-12 col-sm-12 col-xs-12" src="{$vo['thumb']}" alt="" /></div>
                                    <div class="panel-body">
                                        <span style="font-size: 20px"><a href="{:url('detail',['id'=>$vo['id']])}">{$vo['title']}</a></span>
                                        <br>
                                        <span>作者：{$vo['author']}</span>
                                        <span>发布时间：{$vo['update_time']}</span>
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
