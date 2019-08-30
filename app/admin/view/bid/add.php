<link rel="stylesheet" href="__ADMIN_JS__/pictureViewer/css/pictureViewer.css">
<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
    }

    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
    .layui-input,.layui-form-selected dl {
        width: 500px;
    }
    .layui-form-select .layui-edge {
        right: -200px;
    }
    span{
        line-height:35px;
        height:50px;
        font-size: 15px;
    }
</style>

<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            发布时间：{$data_list['create_time']}<br>
            项目名：{$data_list['project_name']}<br>
            附件说明：
            {notempty name="data_list['attachment'][0]"}
            <!--            <div class="image-list">-->
            <ul>
                {volist name="data_list['attachment']" id="vo"}
                <!--                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>-->
                <li>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{$vo}" style="color: #5c90d2">附件{$i}</a></li>
                {/volist}
            </ul>
            <!--            </div>-->
            {else/}
            <span>无</span>
            {/notempty}
            <br>
            {notempty name="data_list['detail']"}
            {volist name="data_list['detail']" id="vo"}
            条件{$i}：{$vo['content']}
            <div class="layui-form-item">
                <div class="layui-input-inline" style="width: 450px">
                    <textarea type="text" class="layui-textarea field-content" name="content[{$key}]" value="" autocomplete="off" placeholder="描述"></textarea>
                </div>
            </div>
            <div class="layui-form-item upload">
                <button type="button" class="layui-btn demoMore"><i class="layui-icon"></i>上传文件</button>
                <input class="layui-input field-attachment" type="hidden" name="attachment[{$key}]" value="">
            </div>
            {/volist}
            {/notempty}
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script src="__ADMIN_JS__/pictureViewer/js/pictureViewer.js"></script>
<script src="__ADMIN_JS__/pictureViewer/js/jquery.mousewheel.min.js"></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload;

        upload.render({
            elem: '.demoMore',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file',
            size:"{:config('upload.upload_file_size')}",
            method: 'post',
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                obj.parents('.upload').append("<a href='"+res.data.file+"'>"+res.data.name+"</a>");
                obj.parents('.upload').find('.field-attachment').val(res.data.file);
            }
        });

        $('#manager_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=manager";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#deal_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=deal";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#send_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=send"+'&path=1';
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#copy_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=copy";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });
    });

    function open_reply(pid,report_id,project_id) {
        var open_url = "{:url('ReportReply/add1')}?id="+report_id+"&project_id="+project_id+"&type=1";
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            maxmin: true,
            title :'回复',
            area: ['600px', '400px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                body.contents().find(".field-pid").val(pid);
                body.contents().find(".field-report_id").val(report_id);
                body.contents().find(".field-project_id").val(project_id);
            }
        });
    }

    function check_ml(e) {
        var num = parseInt($(e).val());
        if (isNaN(num)) {
            num = 0;
        }
        if (num > 20) {
            layer.msg('ML不能超过20');
        }
        var total = 0;
        $("input[name^='ml']").each(function (i, el) {
            var num = parseFloat($(this).val());
            if (isNaN(num)){
                num = 0;
            }
            total += num;
        });
        $('.field-total').val(total);
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>