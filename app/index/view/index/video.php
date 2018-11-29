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
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="post-block">
                                <div class="bg-white bg-boxshadow">
                                    <div class="row">
                                        {volist name="data_list" id="vo"}
                                        <div class="col-md-4 col-sm-6 nopadding col-xs-12">
                                            <div class="bg-white pinside20 number-block outline">
                                                <video src="{$vo['attachment']}" poster="{$vo['thumb']}"  width="280" height="180" controls="controls" preload="metadata">
                                                    您的浏览器不支持 video 标签
                                                </video>
                                                <div>
                                                    <span>{$vo['title']}</span>
                                                </div>

                                            </div>
                                        </div>
                                        {/volist}
                                    </div>
                                </div>
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
