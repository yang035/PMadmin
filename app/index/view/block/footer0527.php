<footer id="gtco-footer" role="contentinfo">
    <div class="gtco-container">

        <div class="row copyright">
            <div class="col-md-12">
                <p class="pull-left">
<!--                <ul class="gtco-social-icons pull-left">-->
<!--                    <li><a href="#"><img style="width: 110px;height: 45px;" src="__ADMIN_JS__/home/images/dc.png" alt="大成易景"></a></li>-->
<!--                    <li><a href="#"><img style="width: 110px;height: 45px;" src="__ADMIN_JS__/home/images/sz.png" alt="深圳建筑设计院"></a></li>-->
<!--                    <li><a href="#"><img style="width: 110px;height: 45px;" src="__ADMIN_JS__/home/images/tc.png" alt="天成易创"></a></li>-->
<!--                </ul>-->
<!--                </p>-->
                <p class="pull-right">
                    <small class="block">联系方式：027-87747658           邮箱：27665567@qq.com</small>
                    <small class="block">Copyright &copy; 2018.版权所有者   <a href="http://beian.miit.gov.cn/" style="color: #778191">ICP证：鄂ICP备2020015253号</a> | 网警</small>
                </p>
            </div>
        </div>

    </div>
</footer>
</div>

<div class="gototop js-top">
    <a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
</div>

<!-- jQuery -->
<script src="__PUBLIC_JS__/index/js/jquery.min.js"></script>
<!-- jQuery Easing -->
<script src="__PUBLIC_JS__/index/js/jquery.easing.1.3.js"></script>
<!-- Bootstrap -->
<script src="__PUBLIC_JS__/index/js/bootstrap.min.js"></script>
<!-- Waypoints -->
<script src="__PUBLIC_JS__/index/js/jquery.waypoints.min.js"></script>
<!-- Carousel -->
<script src="__PUBLIC_JS__/index/js/owl.carousel.min.js"></script>
<!-- countTo -->
<script src="__PUBLIC_JS__/index/js/jquery.countTo.js"></script>
<!-- Flexslider -->
<script src="__PUBLIC_JS__/index/js/jquery.flexslider-min.js"></script>
<!-- Magnific Popup -->
<script src="__PUBLIC_JS__/index/js/jquery.magnific-popup.min.js"></script>
<script src="__PUBLIC_JS__/index/js/magnific-popup-options.js"></script>
<!-- Main -->
<script src="__PUBLIC_JS__/index/js/main.js"></script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?91aa4b01d4016656ecc777bbdfd93e0e";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();

    function loadMeinv(){
        for(var i=0;i<5;i++){
            var html = "";
            html = '<div class="col-md-2 col-xs-4" style="margin-bottom:20px;"><img style="margin: 5px;width: 100%;height: 100%;" src = "__PUBLIC_JS__/index/images/chan.png"></div>';
            $minUl = getMinUl();
            if ($minUl){
                $minUl.append(html);
            }
        }
    }
    loadMeinv();
    $(window).on("scroll",function(){
        $minUl = getMinUl();
        if($minUl && $minUl.height() <= $(window).scrollTop()+$(window).height()){
            //当最短的ul的高度比窗口滚出去的高度+浏览器高度大时加载新图片
            loadMeinv();
        }
    });
    function getMinUl(){
        var $arrUl = $("#container");
        var $minUl =$arrUl.eq(0);
        var len = $arrUl.children("div").length;
        // console.log($minUl);
        if(len < 48) {
            $arrUl.each(function (index, elem) {
                if ($(elem).height() < $minUl.height()) {
                    $minUl = $(elem);
                }
            });
            return $minUl;
        }
    }
</script>
</body>
</html>

