<style>
    #gtco-counter .counter {
        font-size: 40px;
        margin-bottom: 10px;
        color: #FF5126;
        font-weight: 100;
        display: block;
    }
</style>
<div class="gtco-section-overflow">
    <div id="gtco-counter" class="gtco-section">
        <div class="gtco-container">
            <div class="row" style="margin-top: 100px">
                <div class="col-md-12 col-xs-12 text-center gtco-heading">
                    <span style="font-size: 70px;font-weight: bold;font-family:'Microsoft YaHei';color: #fe6908;">IMLGL</span>
                    <span style="font-size: 30px;font-weight: bold;font-family:'Microsoft YaHei';color: #fe6908;">.com</span>
                </div>
            </div>
            <div class="row">
                {for start="0" end="6"}
                <div class="col-md-2 col-xs-4" data-animate-effect="fadeInLeft">
                    <div style="background: grey;">
                        <span class="counter" style="color:#ccc;">待定</span>
                        <!--                        <span style="float: left"><a href="{:url('admin.php/publics/register',['r'=>1])}" class="btn-warning btn-sm">注册</a></span>-->
                        <!--                        <span style="float: right"><a href="{:url('admin.php/publics/index',['r'=>1])}" class="btn-warning btn-sm">登录</a></span>-->
                    </div>
                </div>
                {/for}
            </div>
        </div>
    </div>
</div>