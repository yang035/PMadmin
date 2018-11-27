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
    <div class="slider-img"><img style="height: 400px" src="{$vo.thumb}" alt="Borrow - Loan Company Website Template" class="">
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
                <div class="mb60 text-center section-title">
                    <!-- section title start-->
                    <h1>工作区介绍</h1>
                    <p>工作区介绍工作区介绍工作区介绍工作区介绍工作区介绍工作区介绍工作区介绍工作区介绍<strong>within 24 hours of application.</strong></p>
                </div>
                <!-- /.section title start-->
            </div>
        </div>
        <div class="row">
            <div class="service" id="service">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="bg-white pinside40 service-block outline mb30">
                        <div class="icon mb40"> <img src="__ADMIN_JS__/home/images/loan.svg" alt="Borrow - Loan Company Website Template" class="icon-svg-2x"> </div>
                        <h2><a href="#" class="title">项目案例</a></h2>
                        <p>项目案例介绍</p>
                        <a href="#" class="btn-link">更多</a> </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="bg-white pinside40 service-block outline mb30">
                        <div class="icon mb40"> <img src="__ADMIN_JS__/home/images/mortgage.svg" alt="Borrow - Loan Company Website Template" class="icon-svg-2x"></div>
                        <h2><a href="#" class="title">项目案例</a></h2>
                        <p>项目案例介绍</p>
                        <a href="#" class="btn-link">更多</a> </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="bg-white pinside40 service-block outline mb30">
                        <div class="icon mb40"> <img src="__ADMIN_JS__/home/images/piggy-bank.svg" alt="Borrow - Loan Company Website Template" class="icon-svg-2x"></div>
                        <h2><a href="#" class="title">项目案例</a></h2>
                        <p>项目案例介绍</p>
                        <a href="#" class="btn-link">更多</a> </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="bg-white pinside40 service-block outline mb30">
                        <div class="icon mb40"> <img src="__ADMIN_JS__/home/images/loan.svg" alt="Borrow - Loan Company Website Template" class="icon-svg-2x"></div>
                        <h2><a href="#" class="title">项目案例</a></h2>
                        <p>项目案例介绍</p>
                        <a href="#" class="btn-link">更多</a> </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="bg-white pinside40 service-block outline mb30">
                        <div class="icon mb40"> <img src="__ADMIN_JS__/home/images/car.svg" alt="Borrow - Loan Company Website Template" class="icon-svg-2x"></div>
                        <h2><a href="#" class="title">项目案例</a></h2>
                        <p>项目案例介绍</p>
                        <a href="#" class="btn-link">更多</a> </div>
                </div>
            </div>
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
<div class="section-space80">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="mb60 text-center section-title">
                    <h1>企业宣传</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="bg-white bg-boxshadow">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 nopadding col-xs-12">
                            <div class="bg-white pinside10 number-block outline">
                                <img src="__ADMIN_JS__/home/images/1.png" data-toggle="modal" data-target=".bs-example-modal-lg"/>
                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <video src="__ADMIN_JS__/home/images/tt.mp4" height="500" controls preload="metadata"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 nopadding col-xs-12">
                            <div class="bg-white pinside10 number-block outline">
                                <img src="__ADMIN_JS__/home/images/1.png" data-toggle="modal" data-target=".bs-example-modal-lg"/>
                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <video src="__ADMIN_JS__/home/images/tt.mp4" height="500" controls preload="metadata"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 nopadding col-xs-12">
                            <div class="bg-white pinside10 number-block outline">
                                <img src="__ADMIN_JS__/home/images/1.png" data-toggle="modal" data-target=".bs-example-modal-lg"/>
                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <video src="__ADMIN_JS__/home/images/tt.mp4" height="500" controls preload="metadata"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <div class="mb60 text-center section-title">
                    <!-- section title start-->
                    <h1>最新播报</h1>
                    <p> 最新播报最新播报最新播报最新播报最新播报.</p>
                </div>
                <!-- /.section title start-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="post-block mb30">
                    <div class="post-img">
                        <a href="blog-single.html" class="imghover"><img src="__ADMIN_JS__/home/images/blog-img.jpg" alt="Borrow - Loan Company Website Template" class="img-responsive"></a>
                    </div>
                    <div class="bg-white pinside40 outline">
                        <h2><a href="blog-single.html" class="title">Couples Buying New Home Loan</a></h2>
                        <p class="meta"><span class="meta-date">Aug 25, 2017</span><span class="meta-author">By<a href="#"> Admin</a></span></p>
                        <p>Fusce sed erat libasellus id orci quis ligula pret do lectus velit, a malesuada urna sodales eu.</p>
                        <a href="blog-single.html" class="btn-link">Read More</a> </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="post-block mb30">
                    <div class="post-img">
                        <a href="blog-single.html" class="imghover"><img src="__ADMIN_JS__/home/images/blog-img-1.jpg" alt="Borrow - Loan Company Website Template" class="img-responsive"></a>
                    </div>
                    <div class="bg-white pinside40 outline">
                        <h2><a href="blog-single.html" class="title">Business Man Thinking for Loan</a></h2>
                        <p class="meta"><span class="meta-date">Aug 24, 2017</span><span class="meta-author">By<a href="#"> Admin</a></span></p>
                        <p>Nulla vehicula nibh vel malesuada dapibus ringilla nunc mi sit amet fbendum sapierttitor nibh. </p>
                        <a href="blog-single.html" class="btn-link">Read More</a> </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="post-block mb30">
                    <div class="post-img">
                        <a href="blog-single.html" class="imghover"><img src="__ADMIN_JS__/home/images/blog-img-2.jpg" alt="Borrow - Loan Company Website Templates" class="img-responsive"></a>
                    </div>
                    <div class="bg-white pinside40 outline">
                        <h2><a href="blog-single.html" class="title">Are you students looking for loan ?</a></h2>
                        <p class="meta"><span class="meta-date">Aug 23, 2017</span><span class="meta-author">By<a href="#"> Admin</a></span></p>
                        <p>Malesuada urna sodales euusce sed erat libasellus id orci quis ligula pretium co ctus velit.</p>
                        <a href="blog-single.html" class="btn-link">Read More</a> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-space40">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
                <div class="mb60 text-center section-title">
                    <!-- section title start-->
                    <h1>合作企业</h1>
                </div>
                <!-- /.section title start-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-4 col-xs-6 mb5"> <img src="__ADMIN_JS__/home/images/logo-1.jpg" alt="Borrow - Loan Company Website Template"> </div>
            <div class="col-md-2 col-sm-4 col-xs-6 mb5"> <img src="__ADMIN_JS__/home/images/logo-2.jpg" alt="Borrow - Loan Company Website Template"> </div>
            <div class="col-md-2 col-sm-4 col-xs-6 mb5"> <img src="__ADMIN_JS__/home/images/logo-3.jpg" alt="Borrow - Loan Company Website Template"> </div>
            <div class="col-md-2 col-sm-4 col-xs-6 mb5"> <img src="__ADMIN_JS__/home/images/logo-4.jpg" alt="Borrow - Loan Company Website Template"> </div>
            <div class="col-md-2 col-sm-4 col-xs-6 mb5"> <img src="__ADMIN_JS__/home/images/logo-5.jpg" alt="Borrow - Loan Company Website Template"> </div>
            <div class="col-md-2 col-sm-4 col-xs-6 mb5"> <img src="__ADMIN_JS__/home/images/logo-1.jpg" alt="Borrow - Loan Company Website Template"> </div>
        </div>
    </div>
</div>
<div class="section-space80">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
                <div class="mb60 text-center section-title">
                    <!-- section title-->
                    <h1>联系我们</h1>
                    <p>联系我们联系我们联系我们联系我们联系我们.</p>
                </div>
                <!-- /.section title-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="bg-white bg-boxshadow pinside40 outline text-center mb30">
                    <div class="mb40"><i class="icon-calendar-3 icon-2x icon-default"></i></div>
                    <h2 class="capital-title">贷款</h2>
                    <p>买车或买房需要用钱.</p>
                    <a href="#" class="btn-link">提供资料</a> </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="bg-white bg-boxshadow pinside40 outline text-center mb30">
                    <div class="mb40"><i class="icon-phone-call icon-2x icon-default"></i></div>
                    <h2 class="capital-title">联系方式</h2>
                    <h1 class="text-big">027-87747658</h1>
                    <p>27665567@qq.com</p>
                    <a href="#" class="btn-link">联系</a> </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="bg-white bg-boxshadow pinside40 outline text-center mb30">
                    <div class="mb40"> <i class="icon-users icon-2x icon-default"></i></div>
                    <h2 class="capital-title">项目合作</h2>
                    <p>项目合作项目合作项目合作项目合作.</p>
                    <a href="#" class="btn-link">项目合作</a> </div>
            </div>
        </div>
    </div>
</div>