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
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">项目名</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <select name="project_id" class="layui-input field-project_id" type="select" lay-filter="project" lay-search>-->
<!--                {$mytask}-->
<!--            </select>-->
<!--        </div>-->
<!--        <div class="layui-form-mid" style="color: red">*</div>-->
<!--    </div>-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">专业类型</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <select name="major_cat" class="field-major_cat" type="select" lay-filter="major_cat" id="c_id">-->
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->
    <div class="layui-form-item">
        <label class="layui-form-label">项目名</label>
        <div class="layui-input-inline">
            <div class="layui-form-mid" style="color: red">{$Request.param.project_name}</div>
            <input type="hidden" name="project_id" value="{$Request.param.project_id}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">专业类型</label>
        <div class="layui-form-mid" style="color: red">{$Request.param.major_name}</div>
        <input type="hidden" name="major_cat" value="{$Request.param.major_cat}">
        <input type="hidden" name="major_cat_name" value="{$Request.param.major_name}">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">选择角色</label>
        <div class="layui-input-inline">
            <select name="major_item" class="field-major_item" type="select" lay-filter="rid" id="i_id0">
            </select>
            <input type="hidden" name="major_item_name" id="major_item_name" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">任务名称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" autocomplete="off" placeholder="名称" value="{$task_name}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">选择</label>
        {volist name="p_res" id="vo"}
        <div class="layui-input-block">
            <input type="checkbox" name="report_ids[]" lay-skin="primary" title="【{$vo['realname']}】{$vo['mark']}[{$vo['name']}]" value="{$vo['id']}" checked><br>
        </div>
        {/volist}
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">总结</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-mark" name="mark" lay-verify="required" autocomplete="off" placeholder="请输入结论"></textarea>
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
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
        <div class="layui-input-block">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back();" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate','upload','form','element'], function() {
    var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form,element = layui.element;

    lay('.field-start_time').each(function(){
        laydate.render({
            elem: this,
            trigger: 'click'
        });
    });
    lay('.field-end_time').each(function(){
        // console.log(data);
        laydate.render({
            elem: this,
            trigger: 'click'
        });
    });

    select_union('{$Request.param.project_id}','{$Request.param.major_cat}');

        form.on('select(rid)', function(data){
        });

    function select_union(id,major_cat=0,major_item=0,project_id=0,change_user=0,num=0){
        $.ajax({
            type: 'POST',
            url: "{:url('getMajorItem')}",
            data: {id:id,major_cat:major_cat,major_item:major_item,project_id:project_id,change_user:change_user,filter_str:'负责'},
            dataType:  'json',
            success: function(data){
                if(change_user){
                    if (num){
                        $('#manager_id'+num).html(data);
                        $('#copy_id'+num).html(data);
                    } else {
                        $('#manager_id').html(data);
                        $('#copy_id').html(data);
                    }
                } else {
                    if (num){
                        $('#i_id'+num).html(data);
                    } else {
                        $('.field-major_item').html(data);
                    }
                }
                form.render('select');
            }
        });
    }

    //创建监听函数
    var xhrOnProgress=function(fun) {
        xhrOnProgress.onprogress = fun; //绑定监听
        //使用闭包实现监听绑
        return function() {
            //通过$.ajaxSettings.xhr();获得XMLHttpRequest对象
            var xhr = $.ajaxSettings.xhr();
            //判断监听函数是否为函数
            if (typeof xhrOnProgress.onprogress !== 'function')
                return xhr;
            //如果有监听函数并且xhr对象支持绑定时就把监听函数绑定上去
            if (xhrOnProgress.onprogress && xhr.upload) {
                xhr.upload.onprogress = xhrOnProgress.onprogress;
            }
            return xhr;
        }
    };

    //多文件列表示例
    var demoListView = $('#demoList'),uploadListIns = upload.render({
        elem: '#testList',
        url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
        accept: 'file',
        size:"{:config('upload.upload_file_size')}",
        multiple: true,
        auto: false,
        bindAction: '#testListAction',
        xhr:xhrOnProgress,
        progress:function(value,obj){
            $("#demoList").find('.layui-progress ').each(function () {
                if ($(this).attr("file") == obj.name) {
                    var progressBarName = $(this).attr("lay-filter");
                    var percent = Math.floor((value.loaded / value.total) * 100);//计算百分比
                    element.progress(progressBarName, percent + '%');//设置页面进度条
                }
            })},
        choose: function(obj){
            var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
            var count = 0;
            //读取本地文件
            obj.preview(function(index, file, result){
                count++;
                var tr = $(['<tr id="upload-'+ index +'">'
                    ,'<td>'+ file.name +'</td>'
                    ,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
                    ,'<td>'
                    +'<div  file="'+file.name+'" class="layui-progress layui-progress-big" lay-showpercent="true"   lay-filter="progressBar'+count+'">'
                    +'<div  class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>'
                    +'</div>'
                    , '</td>'
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
                tds.eq(3).html('<span style="color: #5FB878;">上传成功</span>');
                tds.eq(4).html(''); //清空操作
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
            tds.eq(3).html('<span style="color: #FF5722;">上传失败</span>');
            tds.eq(4).find('.demo-reload').removeClass('layui-hide'); //显示重传
        }
    });

    $(".new_task").click(function(){
        var idNum=len= $('#editForm').children('div').length - 3;
        // console.log(len);
        $(".new_task").before("<div class=\"layui-form-item\">\n" +
            "        <label class=\"layui-form-label\">计划</label>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 400px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-name\" name=\"name["+len+"]\" autocomplete=\"off\" placeholder=\"名称\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <select name=\"major_item["+len+"]\" class=\"field-major_item\" type=\"select\" lay-filter=\"rid"+idNum+"\" id=\"i_id"+idNum+"\">\n" +
            "            </select>\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"number\" class=\"layui-input field-score\" name=\"score["+len+"]\" autocomplete=\"off\" placeholder=\"预设值\" onblur=\"checkScore("+idNum+",this.value)\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-start_time\" name=\"start_time["+len+"]\" id=\"start_"+idNum+"\" autocomplete=\"off\" readonly placeholder=\"开始时间\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-end_time\" name=\"end_time["+len+"]\" id=\"end_"+idNum+"\" autocomplete=\"off\" readonly placeholder=\"结束时间\">\n" +
            "        </div>\n" +
            "<div class=\"layui-form-mid\">负责人</div>"+
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <select name=\"manager_user["+len+"]\" class=\"field-manager_user\" type=\"select\" lay-filter=\"manager_id\" id=\"manager_id"+idNum+"\">\n" +
            "            </select>\n" +
            "        </div>\n" +
                "<div class=\"layui-form-mid\">参与人</div>"+
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <select name=\"deal_user["+len+"]\" class=\"field-deal_user\" type=\"select\" lay-filter=\"copy_id\" id=\"copy_id"+idNum+"\">\n" +
            "            </select>\n" +
            "        </div>\n" +
            "    </div>");
        laydate.render({
            elem: '#start_'+idNum,
            type: 'date',
            calendar: true,
            trigger: 'click',
        });
        laydate.render({
            elem: '#end_'+idNum,
            type: 'date',
            calendar: true,
            trigger: 'click',
        });

        select_union('{$Request.param.project_id}','{$Request.param.major_cat}',0,0,0,idNum);
        form.on('select(rid'+idNum+')', function(data){
            select_union('{$Request.param.project_id}','{$Request.param.major_cat}',data.value,0,1,idNum);
        });
        element.render();
        form.render();
    });
    element.render();
    form.render();
});

function checkScore(i,v){
    var id={$Request.param.project_id},major_cat={$Request.param.major_cat},major_item=$("#i_id"+i).val(),major_item_name=$("#i_id"+i).find("option:selected").text(),total = 0;
    if (major_item > 0){
        $.ajax({
            type: 'POST',
            url: "{:url('getSmallMajorScore')}",
            data: {id:id,major_cat:major_cat,major_item:major_item},
            dataType:  'json',
            success: function(data){
                $("input[name^='score']").each(function (k, el) {
                    var score = parseFloat($(this).val());
                    if (isNaN(score)){
                        score = 0;
                    }
                    k++;
                    if ($("#i_id"+k).val() == major_item) {
                        total += score;
                    }
                });
                if (total > data[major_item]) {
                    layer.msg("["+major_item_name+"]总和不能超过["+data[major_item]+"]斗", {icon: 5});
                }
            }
        });
    } else {
        layer.msg("请选择专业", {icon: 5});
    }

}
</script>
<script src="__ADMIN_JS__/footer.js"></script>