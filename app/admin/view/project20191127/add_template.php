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
            <select name="major_item[]" class="field-major_item" type="select" lay-filter="rid" id="i_id0">
            </select>
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="number" class="layui-input field-score" name="score[0]" autocomplete="off" placeholder="预设值" onblur="checkScore(0,this.value)">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-start_time" name="start_time[]" autocomplete="off" readonly placeholder="开始时间">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-end_time" name="end_time[]" autocomplete="off" readonly placeholder="结束时间">
        </div>
        <div class="layui-form-mid">负责人</div>
        <div class="layui-input-inline" style="width: 100px">
            <select name="manager_user[]" class="field-manager_user" type="select" lay-filter="manager_id" id="manager_id">
            </select>
        </div>
        <div class="layui-form-mid">参与人</div>
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
    function select_union(id,major_cat,major_item,project_id,change_user,num){
        var id=id,major_cat=major_cat||0,major_item=major_item||0,project_id=project_id||0,change_user=change_user||0,num=num||0;
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
        var len= $('#editForm').children('div').length - 4;
        // console.log(len);
        $(".new_task").before("<div class=\"layui-form-item\">\n" +
            "        <label class=\"layui-form-label\">计划</label>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 400px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-name\" name=\"name["+len+"]\" autocomplete=\"off\" placeholder=\"名称\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <select name=\"major_item["+len+"]\" class=\"field-major_item\" type=\"select\" lay-filter=\"rid"+len+"\" id=\"i_id"+len+"\">\n" +
            "            </select>\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"number\" class=\"layui-input field-score\" name=\"score["+len+"]\" autocomplete=\"off\" placeholder=\"预设值\" onblur=\"checkScore("+len+",this.value)\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-start_time\" name=\"start_time["+len+"]\" id=\"start_"+len+"\" autocomplete=\"off\" readonly placeholder=\"开始时间\">\n" +
            "        </div>\n" +
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <input type=\"text\" class=\"layui-input field-end_time\" name=\"end_time["+len+"]\" id=\"end_"+len+"\" autocomplete=\"off\" readonly placeholder=\"结束时间\">\n" +
            "        </div>\n" +
                "<div class=\"layui-form-mid\">负责人</div>"+
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <select name=\"manager_user["+len+"]\" class=\"field-manager_user\" type=\"select\" lay-filter=\"manager_id\" id=\"manager_id"+len+"\">\n" +
            "            </select>\n" +
            "        </div>\n" +
                "<div class=\"layui-form-mid\">参与人</div>"+
            "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
            "            <select name=\"deal_user["+len+"]\" class=\"field-deal_user\" type=\"select\" lay-filter=\"copy_id\" id=\"copy_id"+len+"\">\n" +
            "            </select>\n" +
            "        </div>\n" +
            "    </div>");
        laydate.render({
            elem: '#start_'+len,
            type: 'date',
            calendar: true,
            trigger: 'click',
        });
        laydate.render({
            elem: '#end_'+len,
            type: 'date',
            calendar: true,
            trigger: 'click',
        });

        select_union($("select[name='project_id']").val(),$("select[name='major_cat']").val(),0,0,0,len);
        form.on('select(rid'+len+')', function(data){
            select_union($("select[name='project_id']").val(),$("select[name='major_cat']").val(),data.value,0,1,len);
        });
        element.render();
        form.render();
    });
    element.render();
    form.render();
});

function checkScore(i='',v){
    var id=$("select[name='project_id']").val(),major_cat=$("select[name='major_cat']").val(),major_item=$("#i_id"+i).val(),major_item_name=$("#i_id"+i).find("option:selected").text(),total = 0;
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