<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-inline">
                <select name="cat_id" class="field-cat_id" type="select">
                    {$cat_option}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">可见范围</label>
            <div class="layui-input-inline">
                <select name="visible_range" class="field-visible_range" type="select">
                    {$visible_range}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入名称">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">缩略图</label>
            <div class="layui-input-inline upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="oneImage">请上传首页缩略图</button>
                <input type="hidden" class="upload-input field-thumb" name="thumb" value="">
                <img id="thumb" src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">谷粒兑换</label>
            <div class="layui-input-inline">
                <input type="number" name="score" lay-verify="required" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" value="0" autocomplete="off" class="layui-input field-score">
            </div>
            <div class="layui-form-mid">斗</div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">等价于</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-marketprice" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="marketprice" value="0" autocomplete="off" placeholder="请输入兑换价">
            </div>
            <div class="layui-form-mid">元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">额外支付</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-other_price" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="other_price" value="0" autocomplete="off" placeholder="请输入额外支付价格">
            </div>
            <div class="layui-form-mid">元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开始时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required" readonly autocomplete="off" placeholder="选择开始时间">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结束时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-end_time" name="end_time" lay-verify="required" readonly autocomplete="off" placeholder="选择结束时间">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">其他设置</label>
                <div class="layui-form-mid">每</div>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="number" name="time_interval" lay-verify="required" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" value="0" autocomplete="off" class="layui-input field-time_interval">
                </div>
                <div class="layui-form-mid">天，增加</div>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="number" name="add_score" lay-verify="required" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" value="0" autocomplete="off" class="layui-input field-add_score">
                </div>
                <div class="layui-form-mid">斗</div>
                <div class="layui-form-mid" style="color: red">*</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
                <textarea id="ckeditor" name="remark" class="field-remark"></textarea>
            </div>
        </div>
        {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
        <div class="layui-form-item">
            <label class="layui-form-label">附件说明</label>
            <div class="layui-input-inline">
                <!--            <div class="layui-upload">-->
                <!--                <button type="button" class="layui-btn" id="attachment-upload">选择附件</button>-->
                <!--                <div class="layui-upload-list">-->
                <!--                    <img class="layui-upload-file" id="attachment-upload-file">-->
                <!--                    <p id="attachment-upload-text"></p>-->
                <!--                </div>-->
                <!--            </div>-->
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-normal" id="testList">选择多文件</button>
                    <div class="other-div" style="display: none">
                        <div class="layui-upload-list">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>文件名</th>
                                    <th>大小</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="demoList"></tbody>
                            </table>
                        </div>
                        <button type="button" class="layui-btn layui-btn-danger" id="testListAction">开始上传</button>
                        <input class="layui-input field-attachment" type="hidden" name="attachment" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
                <input type="radio" class="field-status" name="status" value="0" title="禁用">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <input type="hidden" class="field-shop_type" name="shop_type" value="{$Request.param.shop_type}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form;
        laydate.render({
            elem: '.field-start_time',
            type: 'date',
            trigger: 'click',
            // value: new Date(),
        });

        laydate.render({
            elem: '.field-end_time',
            type: 'date',
            trigger: 'click',
            // value: new Date(),
        });

        var uploadOneIns = upload.render({
            elem: '#oneImage',
            url: '{:url("admin/UploadFile/upload?group=front")}',
            method: 'post',
            size:120,
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        $('#thumb').attr('src', $('.field-thumb').val()).show();
        //多文件列表示例
        var demoListView = $('#demoList'),uploadListIns = upload.render({
            elem: '#testList',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file',
            size:"{:config('upload.upload_file_size')}",
            multiple: true,
            auto: false,
            bindAction: '#testListAction',
            choose: function(obj){
                var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                //读取本地文件
                obj.preview(function(index, file, result){
                    var tr = $(['<tr id="upload-'+ index +'">'
                        ,'<td>'+ file.name +'</td>'
                        ,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
                        ,'<td>等待上传</td>'
                        ,'<td>'
                        ,'<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                        ,'<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                        ,'</td>'
                        ,'</tr>'].join(''));

                    //单个重传
                    tr.find('.demo-reload').on('click', function(){
                        obj.upload(index, file);
                    });

                    //删除
                    tr.find('.demo-delete').on('click', function(){
                        delete files[index]; //删除对应的文件
                        tr.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });

                    demoListView.append(tr);
                });
                $('.other-div').show();
            }
            ,done: function(res, index, upload){
                if(res.code == 1){ //上传成功
                    var tr = demoListView.find('tr#upload-'+ index)
                        ,tds = tr.children();
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html(''); //清空操作
                    var new_value = $('.field-attachment').val();
                    new_value += res.data.file+',';
                    $('.field-attachment').val(new_value);
                    return delete this.files[index]; //删除文件队列已经上传成功的文件
                }
                this.error(index, upload);
            }
            ,error: function(index, upload){
                var tr = demoListView.find('tr#upload-'+ index)
                    ,tds = tr.children();
                tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>