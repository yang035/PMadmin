<div id="gtco-hero" class="js-fullheight"  data-section="home">
    <div class="flexslider js-fullheight">
        <ul class="slides">
            {volist name="ispush" id="vo"}
            <li style="background-image: url({$vo.tuijian});">
                <div class="overlay"></div>
                <div class="container">
                    <div class="col-md-10 col-md-offset-1 text-center js-fullheight slider-text">
                        <div class="slider-text-inner">
                            <h2>联合 协作 创新 发展</h2>
                            <p><a href="{:url('detail',['id'=>$vo['id']])}" class="btn btn-primary btn-lg">{$vo.title}</a></p>
                        </div>
                    </div>
                </div>
            </li>
            {/volist}
        </ul>
    </div>
</div>

<div class="gtco-section-overflow">

    <div class="gtco-section" id="gtco-services" data-section="services">
        <div class="gtco-container">
            <div class="row">
                <div class="col-md-6">
                    <div class="gtco-heading">
                        <h2 class="gtco-left">IMLGL宣言</h2>
                        <p>在未来中我们憧憬着美好生活，理想在奋斗中一步步的实现，越来越近，直到有一天我们的学习、工作、娱乐都会变成一种美好的生活！未来已来！设计良友相聚，共同期待！</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-paper-clip"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良友的工作方式</h3>
                                    <p>一种不同于传统的协作模式就突然间发生在你的生活中。</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-monitor"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良友的精创空间</h3>
                                    <p>精品创意已不再是遥不可及的梦想，希望在此一切皆有可能。</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-toggle"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良友成就美好</h3>
                                    <p>不在不敢会更加勇敢，不在不能会更加不凡，美好的瞬间变得更加简单。</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-layout"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良好升级平台</h3>
                                    <p>阶梯有限，良友协助无限，一生的美好追求顺来便到手边。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 animate-box" data-animate-effect="fadeIn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-pencil"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良友的学习方法</h3>
                                    <p>那些新、潮、深、厚的圈层知识变得不需要识别便可接纳。</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-cog"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良友的协作共赢</h3>
                                    <p>共筑你我他的力量无限的，每一天的每一天，我们都在。</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-layers"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良友圈层空间</h3>
                                    <p>良友圈层、品质、品味，臻享生活中每一天的不平凡。</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="feature-left">
									<span class="icon">
										<i class="icon-search"></i>
									</span>
                                <div class="feature-copy">
                                    <h3>良友在身边</h3>
                                    <p>起点、路上、终点，都有伴在身边，随遇可安。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gtco-section" id="gtco-portfolio" data-section="portfolio">
        <div class="gtco-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
                    <h2>设计良友一起，工作更快乐</h2>
                    <p>伟大的思想和创意需要在有灵感的设计圈层碰撞和摩擦，产生火花变成风景！<br>因为有你风景才变得更加美丽！</p>
                </div>
            </div>
            <div class="row">
                {volist name="data_project" id="vo"}
                <div class="col-md-4">
                    <a href="{$vo['thumb']}" class="gtco-card-item image-popup" title="{$vo['title']}">
                        <figure>
                            <div class="overlay"><i class="ti-plus"></i></div>
                            <img src="{$vo['thumb']}" alt="Image" class="img-responsive">
                        </figure>
                    </a>
                </div>
                {/volist}
            </div>
        </div>
    </div>

    <div class="gtco-section" id="gtco-faq" data-section="faq">
        <div class="gtco-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
                    <h2>和良友一起学习更快乐</h2>
                    <p>在人生的每一步都有你——良师益友，信仰、思维互长，信心、耐心倍增，终有一天圈层的良友会一起共同站在精彩的云端。</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <div class="gtco-accordion">
                        <div class="gtco-accordion-heading">
                            <div class="icon"><i class="icon-cross"></i></div>
                            <h3>工匠精神</h3>
                        </div>
                        <div class="gtco-accordion-content">
                            <div class="inner">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                            </div>
                        </div>
                    </div>
                    <div class="gtco-accordion">
                        <div class="gtco-accordion-heading">
                            <div class="icon"><i class="icon-cross"></i></div>
                            <h3>创意审美</h3>
                        </div>
                        <div class="gtco-accordion-content">
                            <div class="inner">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                            </div>
                        </div>
                    </div>
                    <div class="gtco-accordion">
                        <div class="gtco-accordion-heading">
                            <div class="icon"><i class="icon-cross"></i></div>
                            <h3>设计建造</h3>
                        </div>
                        <div class="gtco-accordion-content">
                            <div class="inner">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">

                    <div class="gtco-accordion">
                        <div class="gtco-accordion-heading">
                            <div class="icon"><i class="icon-cross"></i></div>
                            <h3>心学梦想</h3>
                        </div>
                        <div class="gtco-accordion-content">
                            <div class="inner">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                            </div>
                        </div>
                    </div>
                    <div class="gtco-accordion">
                        <div class="gtco-accordion-heading">
                            <div class="icon"><i class="icon-cross"></i></div>
                            <h3>玩转设计</h3>
                        </div>
                        <div class="gtco-accordion-content">
                            <div class="inner">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                            </div>
                        </div>
                    </div>
                    <div class="gtco-accordion">
                        <div class="gtco-accordion-heading">
                            <div class="icon"><i class="icon-cross"></i></div>
                            <h3>创意生活</h3>
                        </div>
                        <div class="gtco-accordion-content">
                            <div class="inner">
                                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<div id="gtco-blog" data-section="blog">
    <div class="gtco-container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
                <h2>和良友在一起奋斗更快乐</h2>
                <p>轻松前行的背后，有你我的良友，为我夯实了再一次前行的信心，让我们勇气倍增，一步不停。</p>
            </div>
        </div>
        <div class="row">
            {volist name="data_tpo" id="vo"}
            <div class="col-md-4">
                <a href="{:url('detail',['id'=>$vo['id']])}" target="_blank" class="gtco-card-item has-text">
                    <figure>
                        <div class="overlay"><i class="ti-plus"></i></div>
                        <img src="{$vo['thumb']}" alt="Image" class="img-responsive">
                    </figure>
                    <div class="gtco-text text-left">
                        <h2>{$vo['title']}</h2>
                        <p>{$vo['summarize']}</p>
<!--                        <p class="gtco-category">{$vo['create_time']}</p>-->
                    </div>
                </a>
            </div>
            {/volist}
            <div class="clearfix visible-lg-block visible-md-block"></div>
            <div class="clearfix visible-sm-block"></div>

        </div>
    </div>
</div>

<div class="gtco-section-overflow">
    <div id="gtco-counter" class="gtco-section">
        <div class="gtco-container">

            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
                    <h2>和良友一起享受卓越更快乐</h2>
                    <p>就这样！简单告诉了你我，简单的描述了生活，简单的不再忧虑！就这样！设计良友的专属圈层！</p>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3 col-sm-6 animate-box" data-animate-effect="fadeInLeft">
                    <div class="feature-center">
							<span class="icon">
								<i class="ti-download"></i>
							</span>
                        <span class="counter js-counter" data-from="0" data-to="2122070" data-speed="5000" data-refresh-interval="50">1</span>
                        <span class="counter-label">队伍</span>

                    </div>
                </div>
                <div class="col-md-3 col-sm-6 animate-box" data-animate-effect="fadeInLeft">
                    <div class="feature-center">
							<span class="icon">
								<i class="ti-face-smile"></i>
							</span>
                        <span class="counter js-counter" data-from="0" data-to="402002" data-speed="5000" data-refresh-interval="50">1</span>
                        <span class="counter-label">人群</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 animate-box" data-animate-effect="fadeInLeft">
                    <div class="feature-center">
							<span class="icon">
								<i class="ti-briefcase"></i>
							</span>
                        <span class="counter js-counter" data-from="0" data-to="402" data-speed="5000" data-refresh-interval="50">1</span>
                        <span class="counter-label">价值</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 animate-box" data-animate-effect="fadeInLeft">
                    <div class="feature-center">
							<span class="icon">
								<i class="ti-time"></i>
							</span>
                        <span class="counter js-counter" data-from="0" data-to="212023" data-speed="5000" data-refresh-interval="50">1</span>
                        <span class="counter-label">高效</span>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="gtco-products" data-section="products">
        <div class="gtco-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
                    <h2>和良友一起娱乐更快乐</h2>
                    <p>在良友圈层，每一个都是娱乐沸点的引爆者！更多娱乐的空间、时间和花样，总有一款适合你！圈层有边界，娱乐无极限，一起嗨起来！</p>
                </div>
            </div>
            <div class="row">
                <div class="owl-carousel owl-carousel-carousel">
                    {volist name="data_live" id="vo"}
                    <div class="item">
                        <a href="{:url('detail',['id'=>$vo['id']])}"></a><img src="{$vo['thumb']}" alt="Free HTML5 Bootstrap Template by GetTemplates.co">
                    </div>
                    {/volist}
                </div>
            </div>
        </div>
    </div>

</div>


<div id="gtco-subscribe">
    <div class="gtco-container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center gtco-heading">
                <h2>期待加入</h2>
                <p>如果希望你身边的一切都变成美好的生活，请加入我们良友大家庭，让我们互为良师益友！</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline">
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label for="email" class="sr-only">电话</label>
                            <input type="email" class="form-control" id="email" placeholder="你的电话">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label for="name" class="sr-only">姓名</label>
                            <input type="text" class="form-control" id="name" placeholder="你的姓名">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <button type="submit" class="btn btn-danger btn-block">加入</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="gtco-contact" data-section="contact" class="gtco-cover gtco-cover-xs" style="background-image:url(__PUBLIC_JS__/index/images/lower.jpg);">
    <div class="overlay"></div>
    <div class="gtco-container">
        <div class="row text-center">
            <div class="display-t">
                <div class="display-tc">
                    <div class="col-md-12">
                        <h3>如果想更加快乐工作请加入我们，一起工作，一起嗨！</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>