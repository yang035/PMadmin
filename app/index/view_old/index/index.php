<style>
    .rate-counter-block{
        border: none;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
<div class="slider" id="slider">
    <!-- slider -->
    {volist name="ispush" id="vo"}
    <div class="slider-img"><img style="height: 400px" src="{$vo.tuijian}" alt="Borrow - Loan Company Website Template" class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="slider-captions">
                        <!-- slider-captions -->
                        <h1 class="slider-title">{$vo.title}</h1>
                        <p class="slider-text hidden-xs">
                            {$vo.title}<br>
                            <strong class="text-highlight">{$vo.title}</strong>
                        </p>
                        <a href="{:url('detail',['id'=>$vo['id']])}" class="btn btn-default hidden-xs">{$vo.title}</a>
                    </div>
                    <!-- /.slider-captions -->
                </div>
            </div>
        </div>
    </div>
    {/volist}
</div>
<div class="rate-table">
    <div class="container">
        <div class="row">
            {volist name="index_tab" id="vo"}
            <div class="col-md-3 col-sm-3 col-xs-3">
                <a href="{$vo.href}">
                <div class="rate-counter-block">
                    <div class="icon rate-icon  "> <img src="__ADMIN_JS__/home/images/{$vo.img}" alt="Borrow - Loan Company Website Template" class="icon-svg-1x"></div>
                    <div class="rate-box">
                        <h1 class="loan-rate">{$vo.title}</h1>
<!--                        <small class="rate-title">1000人</small>-->
                    </div>
                </div>
                </a>
            </div>
            {/volist}
        </div>
    </div>
</div>
<div class="section-space80">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
                <div class="mb30 text-center section-title">
                    <h1>项目案例</h1>
                </div>
            </div>
            <div class="mb10 col-md-offset-2 col-md-9 col-sm-12 col-xs-12">
                <span style="float: right"><a href="{:url('lists',['id'=>10])}"> >>更多 </a></span>
            </div>
        </div>
        <div class="row">
            {volist name="data_project" id="vo"}
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="post-block">
                    <div class="post-img">
                        <a href="{:url('detail',['id'=>$vo['id']])}" class="imghover"><img style="height: 200px" src="{$vo['thumb']}" alt="{$vo['title']}" class="img-responsive"></a>
                    </div>
                    <div class="bg-white pinside20 outline">
                        <h2><a href="{:url('detail',['id'=>$vo['id']])}" class="title">{$vo['title']}</a></h2>
                        <p>{$vo['summarize']}</p>
                    </div>
                </div>
            </div>
            {/volist}
        </div>
    </div>
</div>
<!--<div class="section-space80">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-md-offset-2 col-md-8 col-xs-12">-->
<!--                <div class="mb100 text-center section-title">-->
<!--                     section title start-->
<!--                    <h1>生活区介绍</h1>-->
<!--                    <p>生活区介绍生活区介绍生活区介绍生活区介绍生活区介绍</p>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-md-4 col-sm-4 col-xs-12">-->
<!--                <div class="bg-white pinside40 number-block outline mb60 bg-boxshadow">-->
<!--                    <div class="circle"><span class="number">1</span></div>-->
<!--                    <h3 class="number-title">生活区</h3>-->
<!--                    <p>生活区生活区生活区生活区生活区生活区</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 col-xs-12">-->
<!--                <div class="bg-white pinside40 number-block outline mb60 bg-boxshadow">-->
<!--                    <div class="circle"><span class="number">2</span></div>-->
<!--                    <h3 class="number-title">生活区</h3>-->
<!--                    <p>生活区生活区生活区生活区生活区生活区.</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 col-xs-12">-->
<!--                <div class="bg-white pinside40 number-block outline mb60 bg-boxshadow">-->
<!--                    <div class="circle"><span class="number">3</span></div>-->
<!--                    <h3 class="number-title">生活区</h3>-->
<!--                    <p>生活区生活区生活区生活区生活区生活区.</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 col-xs-12">-->
<!--                <div class="bg-white pinside40 number-block outline mb60 bg-boxshadow">-->
<!--                    <div class="circle"><span class="number">4</span></div>-->
<!--                    <h3 class="number-title">生活区</h3>-->
<!--                    <p>生活区生活区生活区生活区生活区生活区</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 col-xs-12">-->
<!--                <div class="bg-white pinside40 number-block outline mb60 bg-boxshadow">-->
<!--                    <div class="circle"><span class="number">5</span></div>-->
<!--                    <h3 class="number-title">生活区</h3>-->
<!--                    <p>生活区生活区生活区生活区生活区生活区.</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 col-xs-12">-->
<!--                <div class="bg-white pinside40 number-block outline mb60 bg-boxshadow">-->
<!--                    <div class="circle"><span class="number">6</span></div>-->
<!--                    <h3 class="number-title">生活区</h3>-->
<!--                    <p>生活区生活区生活区生活区生活区生活区.</p>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8 text-center"> <a href="#" class="btn btn-default">更多</a> </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<div class="section-space50">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="mb30 text-center section-title">
                    <h1>视频宣传</h1>
                </div>
            </div>
            <div class="mb10 col-md-offset-2 col-md-9 col-sm-12 col-xs-12">
                <span style="float: right"><a href="{:url('lists',['id'=>9])}"> >>更多 </a></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="post-block">
                <div class="bg-white bg-boxshadow">
                    <div class="row">
                        {volist name="data_video" id="vo"}
                        <div class="col-md-4 col-sm-6 nopadding col-xs-12">
                            <div class="bg-white pinside20 number-block outline">
                                <video src="{$vo['attachment']}" poster="{$vo['thumb']}"  width="280" height="180" controls="controls" preload="metadata">
                                    您的浏览器不支持 video 标签
                                </video>
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
<!--<div class="section-space80 bg-default">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">-->
<!--                <div class="mb60 text-center section-title">-->
                    <!-- section title start-->
<!--                    <h1 class="title-white">专业人才</h1>-->
<!--                    <p> 专业人才</p>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-md-4 col-sm-4 clearfix col-xs-12">-->
<!--                <div class="testimonial-block mb30">-->
<!--                    <div class="bg-white pinside30 mb5">-->
<!--                        <p class="testimonial-text"> “人员简介”</p>-->
<!--                    </div>-->
<!--                    <div class="testimonial-autor-box mb90">-->
<!--                        <div class="testimonial-img pull-left"> <img src="__ADMIN_JS__/home/images/testimonial-img.jpg" alt="Borrow - Loan Company Website Template"> </div>-->
<!--                        <div class="testimonial-autor pull-left">-->
<!--                            <h4 class="testimonial-name">张三</h4>-->
<!--                            <span class="testimonial-meta">职称</span> </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 clearfix col-xs-12">-->
<!--                <div class="testimonial-block mb30">-->
<!--                    <div class="bg-white pinside30 mb5">-->
<!--                        <p class="testimonial-text"> “人员简介”</p>-->
<!--                    </div>-->
<!--                    <div class="testimonial-autor-box mb90">-->
<!--                        <div class="testimonial-img pull-left"> <img src="__ADMIN_JS__/home/images/testimonial-img.jpg" alt="Borrow - Loan Company Website Template"> </div>-->
<!--                        <div class="testimonial-autor pull-left">-->
<!--                            <h4 class="testimonial-name">张三</h4>-->
<!--                            <span class="testimonial-meta">职称</span> </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 clearfix col-xs-12">-->
<!--                <div class="testimonial-block mb30">-->
<!--                    <div class="bg-white pinside30 mb5">-->
<!--                        <p class="testimonial-text"> “人员简介”</p>-->
<!--                    </div>-->
<!--                    <div class="testimonial-autor-box mb90">-->
<!--                        <div class="testimonial-img pull-left"> <img src="__ADMIN_JS__/home/images/testimonial-img.jpg" alt="Borrow - Loan Company Website Template"> </div>-->
<!--                        <div class="testimonial-autor pull-left">-->
<!--                            <h4 class="testimonial-name">张三</h4>-->
<!--                            <span class="testimonial-meta">职称</span> </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 clearfix col-xs-12">-->
<!--                <div class="testimonial-block mb30">-->
<!--                    <div class="bg-white pinside30 mb5">-->
<!--                        <p class="testimonial-text"> “人员简介”</p>-->
<!--                    </div>-->
<!--                    <div class="testimonial-autor-box mb90">-->
<!--                        <div class="testimonial-img pull-left"> <img src="__ADMIN_JS__/home/images/testimonial-img.jpg" alt="Borrow - Loan Company Website Template"> </div>-->
<!--                        <div class="testimonial-autor pull-left">-->
<!--                            <h4 class="testimonial-name">张三</h4>-->
<!--                            <span class="testimonial-meta">职称</span> </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 clearfix col-xs-12">-->
<!--                <div class="testimonial-block mb30">-->
<!--                    <div class="bg-white pinside30 mb5">-->
<!--                        <p class="testimonial-text"> “人员简介”</p>-->
<!--                    </div>-->
<!--                    <div class="testimonial-autor-box mb90">-->
<!--                        <div class="testimonial-img pull-left"> <img src="__ADMIN_JS__/home/images/testimonial-img.jpg" alt="Borrow - Loan Company Website Template"> </div>-->
<!--                        <div class="testimonial-autor pull-left">-->
<!--                            <h4 class="testimonial-name">张三</h4>-->
<!--                            <span class="testimonial-meta">职称</span> </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4 col-sm-4 clearfix col-xs-12">-->
<!--                <div class="testimonial-block mb30">-->
<!--                    <div class="bg-white pinside30 mb5">-->
<!--                        <p class="testimonial-text"> “人员简介”</p>-->
<!--                    </div>-->
<!--                    <div class="testimonial-autor-box mb90">-->
<!--                        <div class="testimonial-img pull-left"> <img src="__ADMIN_JS__/home/images/testimonial-img.jpg" alt="Borrow - Loan Company Website Template"> </div>-->
<!--                        <div class="testimonial-autor pull-left">-->
<!--                            <h4 class="testimonial-name">张三</h4>-->
<!--                            <span class="testimonial-meta">职称</span> </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<div class="section-space80">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
                <div class="mb30 text-center section-title">
                    <h1>新材料</h1>
                </div>
            </div>
            <div class="mb10 col-md-offset-2 col-md-9 col-sm-12 col-xs-12">
                <span style="float: right"><a href="{:url('lists',['id'=>11])}"> >>更多 </a></span>
            </div>
        </div>
        <div class="row">
            {volist name="data_tpo" id="vo"}
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="post-block">
                    <div class="post-img">
                        <a href="{:url('detail',['id'=>$vo['id']])}" class="imghover"><img src="{$vo['thumb']}" alt="{$vo['title']}" class="img-responsive"></a>
                    </div>
                    <div class="bg-white pinside20 outline" style="height: 140px">
                        <h2><a href="{:url('detail',['id'=>$vo['id']])}" class="title">{$vo['title']}</a></h2>
                        <p>{$vo['summarize']}</p>
                    </div>
                </div>
            </div>
            {/volist}
        </div>
    </div>
</div>
<div class="section-space10">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
                <div class="mb30 text-center section-title">
                    <h1>合作企业</h1>
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-md-3 col-sm-4 col-xs-6 mb5"><a href="/" title="大成易景"><img style="width: 110px;height: 45px;" src="__ADMIN_JS__/home/images/dc.png" alt="大成易景"></a></div>
            <div class="col-md-3 col-sm-4 col-xs-6 mb5"><a href="/" title="集智圈点"><img style="width: 110px;height: 45px;" src="__ADMIN_JS__/home/images/jz.png" alt="集智圈点"></a></div>
            <div class="col-md-3 col-sm-4 col-xs-6 mb5"><a href="/" title="深圳建筑设计院"><img style="width: 110px;height: 45px;" src="__ADMIN_JS__/home/images/sz.png" alt="深圳建筑设计院"></a></div>
            <div class="col-md-3 col-sm-4 col-xs-6 mb5"><a href="/" title="天成易创"><img style="width: 110px;height: 45px;" src="__ADMIN_JS__/home/images/tc.png" alt="天成易创"></a></div>
        </div>
    </div>
</div>