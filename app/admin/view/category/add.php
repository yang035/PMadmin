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
    <div class="layui-form-item">
        <label class="layui-form-label">父类型</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-pname" id="menu_parent_name" lay-verify="required" autocomplete="off">
            <div id="treediv" style="display: none;overflow:scroll;position: relative;z-index:9999;">
                <div align="right"><a href="##" id="closed"><font color="#000">关闭&nbsp;</font></a></div>
                <script language="JavaScript" type="text/JavaScript">
                    mydtree = new dTree('mydtree','/static/js/dtree/img/','no','no');
                        var ajax_url = "{:url('admin/tool/getTreeCat')}";
                        // var jsonstr={"cid":1};
                        $.ajax({
                            url:ajax_url,
                            async : false,
                            type:"post",
                            // data:jsonstr,
                            dataType:"json",
                            success:function(data){
                                // alert(JSON.stringify(data));
                                //根目录
                                mydtree.add(0,-1,"根目录","","根目录","_self",false);
                                for(var i=0;i<data.length;i++){
                                    mydtree.add(data[i].id,data[i].pid,data[i].name,"javascript:setvalue('"+data[i].id+"','"+data[i].pid+"','"+data[i].name+"','"+data[i].code+"')",data[i].name,"_self",false);
                                }
                                document.write(mydtree);
                            }
                        });
                </script>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入名称">
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-inline">
            <textarea class="layui-textarea field-remark" name="remark" lay-verify="required" autocomplete="off"></textarea>
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态设置</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
            <input type="radio" class="field-status" name="status" value="0" title="禁用">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <input type="hidden" class="field-pid" name="pid" value="{$Request.param.pid}">
            <input type="hidden" class="field-code" name="code" value="{$Request.param.code}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>

<script>
var formData = {:json_encode($data_info)};
layui.use(['form'], function() {
    var $ = layui.jquery, form = layui.form;
    if (formData) {
        $('.ass-level').val(parseInt($('.field-pid option:selected').attr('level'))+1);
    }
    $('.layui-btn-primary').click(function () {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
    });
});

xOffset = 0;//向右偏移量
yOffset = 25;//向下偏移量
var toshow = "treediv";//要显示的层的id
var target = "menu_parent_name";//目标控件----也就是想要点击后弹出树形菜单的那个控件id
$("#"+target).click(function (){
    $("#"+toshow)
        .css("left", $("#"+target).position().left+xOffset + "px")
        .css("top", $("#"+target).position().top+yOffset +"px").show();
});
//关闭层
$("#closed").click(function(){
    $("#"+toshow).hide();
});
//判断鼠标在不在弹出层范围内
function checkIn(id){
    var yy = 20;   //偏移量
    var str = "";
    var   x=window.event.clientX;
    var   y=window.event.clientY;
    var   obj=$("#"+id)[0];
    if(x>obj.offsetLeft&&x<(obj.offsetLeft+obj.clientWidth)&&y>(obj.offsetTop-yy)&&y<(obj.offsetTop+obj.clientHeight)){
        return true;
    }else{
        return false;
    }
}
//点击body关闭弹出层
$(document).click(function(){
    var is = checkIn("treediv");
    if(!is){
        // $("#"+toshow).hide();
    }
});
<!-- 弹出层-->
//生成弹出层的代码
//点击菜单树给文本框赋值------------------菜单树里加此方法
function setvalue(id,pid,name,code){
    $("#menu_parent_name").val(name);
    $(".field-id").val(id);
    $(".field-pid").val(pid);
    $(".field-code").val(code);
    $("#treediv").hide();
}
</script>
<script src="__ADMIN_JS__/footer.js"></script>