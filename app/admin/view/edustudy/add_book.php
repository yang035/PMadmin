<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">课程书籍</label>
            <div class="layui-input-inline">
                <select name="book_cat" class="field-book_cat" type="select" lay-filter="book_cat">
                    {$book_option}
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="book_id" class="field-book_id" type="select" id="book_id">
                </select>
            </div>
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
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate', 'form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,form = layui.form;
        form.on('select(book_cat)', function(data){
            select_union(data.value);
        });

        function select_union(cat_id){
            $.ajax({
                type: 'POST',
                url: "{:url('getBookId')}",
                data: {cat_id:cat_id},
                dataType:  'json',
                success: function(data){
                    $('#book_id').html(data);
                    form.render('select');
                }
            });
        }
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>