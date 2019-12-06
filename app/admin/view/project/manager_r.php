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
        <div class="layui-input-block">
            <a onclick="c()" class="layui-btn layui-btn-normal">下一步</a>
            <a href="javascript:history.back();" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate','upload','form','element'], function() {
    var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form,element = layui.element;

    form.on('select(project)', function(data){
        select_union('',1,1,data.value);
        select_union($("select[name='project_id']").val(),1);
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

    element.render();
    form.render();
});

function c(){
    var  project_id=$("select[name='project_id']").val(),project_name=$("select[name='project_id'] option:selected").text();
    var  major_cat=$("select[name='major_cat']").val(),major_name=$("select[name='major_cat'] option:selected").text();
    var open_url = "{:url('managerReport')}?project_id="+project_id+"&project_name="+project_name+"&major_cat="+major_cat+"&major_name="+major_name;
    window.location.href = open_url;
}
</script>
<script src="__ADMIN_JS__/footer.js"></script>