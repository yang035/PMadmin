<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label" style="width: 20px">省</label>
            <div class="layui-input-inline" style="width: 150px">
                <select name="province" class="layui-input field-province" type="select" lay-filter="province" id="province_id">
                    {$province}
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label" style="width: 20px">市</label>
            <div class="layui-input-inline" style="width: 150px">
                <select name="city" class="field-city" type="select" lay-filter="city" lay-filter="city" id="city_id">
                </select>
            </div>
        </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型</label>
        <div class="layui-input-inline">
            <select name="cat_id" class="field-cat_id" type="select">
                {$cat_option}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">岗位</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-title" name="title" lay-verify="required" autocomplete="off"
                   placeholder="请输入岗位">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标签</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-tags" name="tags" autocomplete="off" placeholder="标签用英文','隔开">
        </div>
    </div>
        <div class="layui-form-item">
            <label class="layui-form-label">学历</label>
            <div class="layui-input-inline">
                <select name="education" class="field-education" type="select">
                    {$education_type}
                </select>
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">工作经验</label>
            <div class="layui-input-inline">
                <select name="experience" class="field-experience" type="select">
                    {$experience_type}
                </select>
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
    <div class="layui-form-item">
        <label class="layui-form-label">月薪范围</label>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-min_money" name="min_money" autocomplete="off">
        </div>
        <div class="layui-form-mid">元</div>
        <div class="layui-form-mid"> ~ </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-max_money" name="max_money" autocomplete="off">
        </div>
        <div class="layui-form-mid">元</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea id="ckeditor" name="content" class="field-content"></textarea>
        </div>
    </div>
    {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">作者</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" data-disabled class="layui-input field-author" name="author" lay-verify="required"-->
<!--                   autocomplete="off" placeholder="请输入作者">-->
<!--        </div>-->
<!--        <div class="layui-form-mid" style="color: red">*</div>-->
<!--    </div>-->
    <div class="layui-form-item">
        <label class="layui-form-label">附件说明</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <button type="button" class="layui-btn layui-btn-normal" id="testList">选择多文件</button>
                <div class="other-div" style="display: none">
                    <div class="layui-upload-list">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>文件名</th>
                                <th>大小</th>
                                <th>上传进度</th>
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
        <label class="layui-form-label">状&nbsp;&nbsp;&nbsp;&nbsp;态</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
            <input type="radio" class="field-status" name="status" value="0" title="禁用">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate', 'upload', 'form'], function () {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload, form = layui.form;
        //多文件列表示例
        var demoListView = $('#demoList'), uploadListIns = upload.render({
            elem: '#testList',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file',
            size: "{:config('upload.upload_file_size')}",
            multiple: true,
            auto: false,
            bindAction: '#testListAction',
            choose: function (obj) {
                var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                //读取本地文件
                obj.preview(function (index, file, result) {
                    var tr = $(['<tr id="upload-' + index + '">'
                        , '<td>' + file.name + '</td>'
                        , '<td>' + (file.size / 1014).toFixed(1) + 'kb</td>'
                        , '<td>等待上传</td>'
                        , '<td>'
                        , '<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                        , '<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                        , '</td>'
                        , '</tr>'].join(''));

                    //单个重传
                    tr.find('.demo-reload').on('click', function () {
                        obj.upload(index, file);
                    });

                    //删除
                    tr.find('.demo-delete').on('click', function () {
                        delete files[index]; //删除对应的文件
                        tr.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });

                    demoListView.append(tr);
                });
                $('.other-div').show();
            }
            , done: function (res, index, upload) {
                if (res.code == 1) { //上传成功
                    var tr = demoListView.find('tr#upload-' + index)
                        , tds = tr.children();
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html(''); //清空操作
                    var new_value = $('.field-attachment').val();
                    new_value += res.data.file + ',';
                    $('.field-attachment').val(new_value);
                    return delete this.files[index]; //删除文件队列已经上传成功的文件
                }
                this.error(index, upload);
            }
            , error: function (index, upload) {
                var tr = demoListView.find('tr#upload-' + index)
                    , tds = tr.children();
                tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });

        form.on('select(province)', function(data){
            select_city(data.value);
        });

        select_city({$ip_region['p']},{$ip_region['c']});

        function select_city(province,type){
            var open_url = "{:url('Resources/getCity')}?province="+province+"&type="+type;
            $.ajax({
                type: 'POST',
                url: open_url,
                dataType:  'json',
                success: function(data){
                    $('#city_id').html(data);
                    form.render('select');
                }
            });
        }
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>