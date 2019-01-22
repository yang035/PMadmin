$(function() {
    // 公告滚动
    $(".notice-content").textScroll();
});

/**
 * 从右往左滚动文字
 * @returns {undefined}
 */
$.fn.textScroll = function() {
    // 滚动步长(步长越大速度越快)
    var step_len = 60;
    var this_obj = $(this);
    var child = this_obj.children();
    var this_width = this_obj.width();
    var child_width = child.width();
    var continue_speed = undefined;// 暂停后恢复动画速度
    // 初始文字位置
    child.css({
        left: this_width
    });

    // 初始动画速度speed
    var init_speed = (child_width + this_width) / step_len * 1000;

    // 滚动动画
    function scroll_run(continue_speed) {
        var speed = (continue_speed == undefined ? init_speed : continue_speed);
        child.animate({
            left: -child_width
        }, speed, "linear", function() {
            $(this).css({
                left: this_width
            });
            scroll_run();
        });
    }

    // 鼠标动作
    child.on({
        mouseenter: function() {
            var current_left = $(this).position().left;
            $(this).stop();
            continue_speed = (-(-child_width - current_left) / step_len) * 1000;
        },
        mouseleave: function() {
            scroll_run(continue_speed);
            continue_speed = undefined;
        }
    });

    // 启动滚动
    scroll_run();
};