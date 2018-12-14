<link href="__PUBLIC_JS__/dtree/dtreeck.css?v=">
{include file="block/layui" /}
<style type="text/css">
    .dTreeNode a:link {
        font-family: "宋体";
        font-size: 12px;
        color: #0000FF;
        text-decoration: none;
    }

    .dTreeNode a:visited {
        font-family: "宋体";
        font-size: 12px;
        color: #0000FF;
        text-decoration: none;
    }

    .dTreeNode a:hover {
        font-family: "宋体";
        font-size: 12px;
        color: #CC6600;
        text-decoration: none;
    }

    .dTreeNode a:active {
        font-family: "宋体";
        font-size: 12px;
        color: #006600;
        text-decoration: none;
    }
</style>
<script src="__PUBLIC_JS__/dtree/dtreeck.js?v="></script>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-tab-item layui-form layui-show">
        <div class="layui-form-item">
            <label class="layui-form-label">选择类型</label>
            {neq name="ADMIN_ROLE" value="1"}
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-cat_name" id="menu_parent_name" lay-verify="required"
                       name="cat_name" autocomplete="off" value="{$data_info['category']['name']|default=''}">
                <div id="treediv" style="overflow:scroll;position: relative;z-index:9999;display:none;">
                    <div align="right"><a href="##" id="closed"><font color="#000">关闭&nbsp;</font></a></div>
                    <script language="JavaScript" type="text/JavaScript">
                        mydtree = new dTree('mydtree', '/static/js/dtree/img/', 'no', 'no');
                        var ajax_url = "{:url('admin/tool/getTreeCat')}";
                        // var jsonstr={"cid":1};
                        $.ajax({
                            url: ajax_url,
                            async: false,
                            type: "post",
                            // data:jsonstr,
                            dataType: "json",
                            success: function (data) {
                                // alert(JSON.stringify(data));
                                //根目录
                                mydtree.add(0, -1, "根目录", "", "根目录", "_self", false);
                                for (var i = 0; i < data.length; i++) {
                                    mydtree.add(data[i].id, data[i].pid, data[i].name, "javascript:setvalue('" + data[i].id + "','" + data[i].pid + "','" + data[i].name + "','" + data[i].code + "')", data[i].name, "_self", false);
                                }
                                document.write(mydtree);
                            }
                        });
                    </script>
                </div>
            </div>
            {else/}
            <div class="layui-input-inline">
                <select name="cat_id" class="field-cat_id" type="select">
                    {$cat_option}
                </select>
            </div>
            {/neq}
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-title" name="title" lay-verify="required"
                       autocomplete="off" placeholder="请输入物品名称">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <textarea type="text" class="layui-textarea field-description" name="description" lay-verify="required"
                          autocomplete="off" placeholder="请输入不超过200字的描述" maxlength="120"></textarea>
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">采购单价</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-marketprice" name="marketprice" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" lay-verify="required" maxlength="11"
                       autocomplete="off" placeholder="请输入采购单价">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">库存数</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-total" name="total" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" lay-verify="required" maxlength="11"
                       autocomplete="off" placeholder="请输入库存数">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">单位</label>
            <div class="layui-input-inline">
                <select name="unit" class="field-unit" type="select">
                    {$unit_option}
                </select>
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
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea id="ckeditor" name="content" class="field-content"></textarea>
            </div>
        </div>
        {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <input type="hidden" class="field-cat_id" name="cat_id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>

<script>
    var formData = {:json_encode($data_info)};
    xOffset = 0;//向右偏移量
    yOffset = 25;//向下偏移量
    var toshow = "treediv";//要显示的层的id
    var target = "menu_parent_name";//目标控件----也就是想要点击后弹出树形菜单的那个控件id
    $("#" + target).click(function () {
        $("#" + toshow)
            .css("left", $("#" + target).position().left + xOffset + "px")
            .css("top", $("#" + target).position().top + yOffset + "px").show();
    });
    //关闭层
    $("#closed").click(function () {
        $("#" + toshow).hide();
    });

    //判断鼠标在不在弹出层范围内
    function checkIn(id) {
        var yy = 20;   //偏移量
        var str = "";
        var x = window.event.clientX;
        var y = window.event.clientY;
        var obj = $("#" + id)[0];
        if (x > obj.offsetLeft && x < (obj.offsetLeft + obj.clientWidth) && y > (obj.offsetTop - yy) && y < (obj.offsetTop + obj.clientHeight)) {
            return true;
        } else {
            return false;
        }
    }

    //点击body关闭弹出层
    // $(document).click(function(){
    //     var is = checkIn("treediv");
    //     if(!is){
    //         $("#"+toshow).hide();
    //     }
    // });
    <!-- 弹出层-->
    //生成弹出层的代码
    //点击菜单树给文本框赋值------------------菜单树里加此方法
    function setvalue(id, pid, name, code) {
        $("#menu_parent_name").val(name);
        $(".field-cat_id").val(id);
        $(".field-pid").val(pid);
        $(".field-code").val(code);
        $("#treediv").hide();
    }

    layui.use(['jquery', 'laydate', 'upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, layer = layui.layer, upload = layui.upload, form = layui.form;
        var uploadOneIns = upload.render({
            elem: '#oneImage',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:0,
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

        if(1 == formData.is_push){
            $('#tuijian_div').show();
        }

        form.on('radio(is_push)', function(data){
            if(1 == data.value){
                $('#tuijian_div').show();
            }else {
                $('#tuijian_div').hide();
            }
        });

        //多文件列表示例
        var demoListView = $('#demoList'),uploadListIns = upload.render({
            elem: '#testList',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
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
        // 日期渲染
        laydate.render({elem: '.layui-date'});
        form.render();
    });

</script>
<script src="__ADMIN_JS__/footer.js"></script>