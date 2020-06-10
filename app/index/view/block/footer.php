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
                    <small class="block">Copyright &copy; 2018.版权所有者   <a href="http://beian.miit.gov.cn/" style="color: #778191">ICP证：鲁ICP备18050320号</a> | 网警</small>
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
<script src="__PUBLIC_JS__/index/js/jquery.waterfall.js"></script>
<!-- Modernizr JS -->
<script src="__PUBLIC_JS__/index/js/modernizr-2.6.2.min.js"></script>
<!-- FOR IE9 below -->
<!--[if lt IE 9]>
<script src="__PUBLIC_JS__/index/js/respond.min.js"></script>
<![endif]-->
<script>
    $("#div1").waterfall({
        itemClass: ".box",
        minColCount: 2,
        spacingHeight: 10,
        resizeable: true,
        ajaxCallback: function(success, end) {
            var data = {"data": [
                    { "src": "img_1.jpg" }, { "src": "img_2.jpg" }, { "src": "lower.jpg" }, { "src": "img_4.jpg" }, { "src": "chan.png" }, { "src": "img_5.jpg" }
                ]};
            var str = "",len = $("#div1").children("div").length;
            var templ = '<div class="box col-md-2 col-xs-3" style="opacity:0;filter:alpha(opacity=0);"><div class="pic"><img style="width: 100%" src="__PUBLIC_JS__/index/images/{{src}}" /></div></div>'
            if (len < 30){
                for(var i = 0; i < data.data.length; i++) {
                    str += templ.replace("{{src}}", data.data[i].src);
                }
                $(str).appendTo($("#div1"));
            }
            success();
            end();
        }
    });

    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b1d7bd674571a9b3ef46862485845cc8";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>

