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
                                <div class="panel-body">
                                <h1>{$data_list['title']}</h1>
                                    <br>
<!--                                作者：{$data_list['author']}-->
                                时间：{$data_list['sub_date']}
                                    <br>
<!--                                摘要：{$data_list['summarize']}-->
<!--                                    <br>-->
<!--                                标签：{$data_list['tags']}-->
<!--                                    <br>-->
                                </div>
                                {$data_list['content']}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>