<style>
    .panel-body{
        padding: 100px;
    }
    .rate-counter-block{
        border: none;
        padding-top: 100px;
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
                                <div class="panel-body" style="text-align: center">
                                <h1>{$data_list['name']}</h1>
                                    <br>
                                时间：{$data_list['update_time']}
                                    <br>
                                </div>
                                <b>谷粒兑换：</b>{$data_list['score']}(斗)<br>
                                <b>等价于：</b>{$data_list['marketprice']}(元)<br>
                                <b>额外支付：</b>{$data_list['other_price']}(元)<br>
                                <b>描述：</b>{$data_list['remark']}<br>
                                <p><a href="{:url('admin.php/shop/shopDetail',['id'=>$data_list['id']])}" class="btn btn-primary btn-sm">兑换</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>