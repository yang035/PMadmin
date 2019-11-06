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
    <div class="layui-form-item">
        <label class="layui-form-label">项目名</label>
        <div class="layui-input-inline">
            <select name="project_id" class="layui-input field-project_id" type="select" lay-filter="project" lay-search>
                {$mytask}
            </select>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">专业类型</label>
        <div class="layui-input-inline">
            <select name="major_cat" class="field-major_cat" type="select" lay-filter="major_cat" id="c_id">
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">计划</label>
        <div class="layui-input-inline" style="width: 400px">
            <input type="text" class="layui-input field-name" name="name[]" autocomplete="off" placeholder="名称">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <select name="major_item[]" class="field-major_item" type="select" lay-filter="rid" id="i_id">
            </select>
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="number" class="layui-input field-score" name="score[]" autocomplete="off" placeholder="预设值">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-start_time" name="start_time[]" autocomplete="off" readonly placeholder="开始时间">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-end_time" name="end_time[]" autocomplete="off" readonly placeholder="结束时间">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <select name="manager_user[]" class="field-manager_user" type="select" lay-filter="manager_id" id="manager_id">
            </select>
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <select name="deal_user[]" class="field-deal_user" type="select" lay-filter="copy_id" id="copy_id">
            </select>
        </div>
    </div>
    <div class="new_task">
        <a href="javascript:void(0);" class="aicon ai-tianjia field-guige-add" style="float: left;font-size: 30px;"></a>
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

    laydate.render({
        elem: '.field-start_time',
        type: 'date',
        calendar: true,
        trigger: 'click',
    });
    laydate.render({
        elem: '.field-end_time',
        type: 'date',
        calendar: true,
        trigger: 'click',
    });

    form.on('select(project)', function(data){
        select_union('',1,1,data.value);
        select_union($("select[name='project_id']").val(),1);
    });

    form.on('select(major_cat)', function(data){
        select_union($("select[name='project_id']").val(),data.value);
    });
    if (formData.major_cat){
        select_union('{$Request.param.id}',formData.major_cat,formData.major_item);
    }else {
        select_union('{$Request.param.id}',1,1);
    }

    form.on('select(rid)', function(data){
        select_union($("select[name='project_id']").val(),$("select[name='major_cat']").val(),data.value,0,1);
    });

    function select_union(id,major_cat=0,major_item=0,project_id=0,change_user=0,num=0){
        $.ajax({
            type: 'POST',
            url: "{:url('getMajorItem')}",
            data: {id:id,major_cat:major_cat,major_item:major_item,project_id:project_id,change_user:change_user},
            dataType:  'json',
            success: function(data){
                if (project_id){
                    $('#c_id').html(data);
                }else if(change_user){
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

    function getRandomNum() {
        return parseInt(Math.random()*50);
    }

    $(".new_task").click(function(){
        var idNum = getRandomNum();//获取随机数
        var len= $('#editForm').children('div').length - 4;
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
            "            <input type=\"number\" class=\"layui-input field-score\" name=\"score["+len+"]\" autocomplete=\"off\" placeholder=\"预设值\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-start_time\" name=\"start_time["+len+"]\" id=\"start_"+idNum+"\" autocomplete=\"off\" readonly placeholder=\"开始时间\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-end_time\" name=\"end_time["+len+"]\" id=\"end_"+idNum+"\" autocomplete=\"off\" readonly placeholder=\"结束时间\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <select name=\"manager_user["+len+"]\" class=\"field-manager_user\" type=\"select\" lay-filter=\"manager_id\" id=\"manager_id"+idNum+"\">\n" +
            "            </select>\n" +
            "        </div>\n" +
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

        select_union($("select[name='project_id']").val(),$("select[name='major_cat']").val(),0,0,0,idNum);
        form.on('select(rid'+idNum+')', function(data){
            select_union($("select[name='project_id']").val(),$("select[name='major_cat']").val(),data.value,0,1,idNum);
        });
        element.render();
        form.render();
    });
    element.render();
    form.render();
});
</script>
<script src="__ADMIN_JS__/footer.js"></script>