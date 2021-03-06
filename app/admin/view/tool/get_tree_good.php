{include file="block/layui" /}
<link href="__PUBLIC_JS__/dtree/dtree.css?v=">
<script src="__PUBLIC_JS__/dtree/dtree.js?v="></script>
<style>
    #ulSelected .remove{background-image:url("/static/js/dtree/img/remove.gif")}#ulSelected li{float:left}#ulSelected .selectedUser{background-position:right;background-repeat:no-repeat;background-color:transparent;padding-right:9px;cursor:default}ul{list-style-type:none;margin:0;padding:0}li{display:list-item;text-align:-webkit-match-parent}.selectorResult{margin:auto;margin-top:5px;line-height:18px;min-height:22px;font-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12px;width:380px}element.style{cursor:pointer}
    li{
        font-size: 15px;
    }
    .layui-input{
        width: auto;
        float: left;
    }
    .dTreeNode{
        font-size: medium;
    }
    .rootNode{
        display: none;/*注释节点根目录*/
    }
    .dep{
        color: #009688;
    }
</style>
<div>
    <div class="dtree" id="dtree_div" style="width: 500px;float: left">
        <p><button class="layui-btn layui-btn-normal" onclick="javascript:  d.openAll();">打开</button><button  class="layui-btn layui-btn-normal" onclick="javascript: d.closeAll();">关闭</button></p>
        <input class="layui-input" id="dosearch_text" type="text" onkeyup="nodeSearching()" placeholder="关键字搜索"/><input id="dosearch" type="button" class="layui-btn layui-btn-normal" value="查询" onclick="nodeSearching() " />
        <div style="color: red">备注：点击部门名称“批量操作”</div>
        <script type="text/javascript">
            d = new dTree('d', true);
            var ajax_url = "{:url('admin/tool/getTreeGood')}";
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
                    d.add(0,-1,"根目录");
                    for(var i=0;i<data.length;i++){
                        if (typeof (data[i].cat_id) == 'undefined'){
                            d.add(data[i].id,data[i].pid,'authority',data[i].id,data[i].name,true,false);
                        } else {
                            d.add(data[i].gid,data[i].cat_id,'authority',data[i].id,data[i].title+'['+data[i].kucun+']',false,false);
                        }

                    }
                    document.write(d);
                    d.openAll();
                }
            });
        </script>
    </div>
    <div class="selectorResult" style="width: 200px;float: left">
        选择结果：
        <ul id="ulSelected">
        </ul>
    </div>
    <div>
        <button type="button" class="layui-btn layui-btn-normal good_confirm" >确定</button>
    </div>
    <script>
            $(document).ready(function () {
                //#region 浏览器检测相关方法
                window["MzBrowser"] = {}; (function () {
                    if (MzBrowser.platform) return;
                    var ua = window.navigator.userAgent;
                    MzBrowser.platform = window.navigator.platform;
                    MzBrowser.firefox = ua.indexOf("Firefox") > 0;
                    MzBrowser.opera = typeof (window.opera) == "object";
                    MzBrowser.ie = !MzBrowser.opera && ua.indexOf("MSIE") > 0;
                    MzBrowser.mozilla = window.navigator.product == "Gecko";
                    MzBrowser.netscape = window.navigator.vendor == "Netscape";
                    MzBrowser.safari = ua.indexOf("Safari") > -1;
                    if (MzBrowser.firefox) var re = /Firefox(\s|\/)(\d+(\.\d+)?)/;
                    else if (MzBrowser.ie) var re = /MSIE( )(\d+(\.\d+)?)/;
                    else if (MzBrowser.opera) var re = /Opera(\s|\/)(\d+(\.\d+)?)/;
                    else if (MzBrowser.netscape) var re = /Netscape(\s|\/)(\d+(\.\d+)?)/;
                    else if (MzBrowser.safari) var re = /Version(\/)(\d+(\.\d+)?)/;
                    else if (MzBrowser.mozilla) var re = /rv(\:)(\d+(\.\d+)?)/;
                    if ("undefined" != typeof (re) && re.test(ua))
                        MzBrowser.version = parseFloat(RegExp.$2);
                })();
            });
            //显示删除
            function showRemove(obj) {
                $(obj).addClass("remove");
            }
            //隐藏删除
            function hideRemove(obj) {
                $(obj).removeClass("remove");
            }
            //鼠标移动到删除图标，显示手（pointer）
            function setRemove(obj, event) {
                var width = $(obj).width();
                var left = $(obj).position().left;
                var e = event || window.event;
                var x = IsIE(GetVersion()) ? e.x : e.pageX;
                if (x > left + width - 9) {
                    $(obj).css("cursor", "pointer")
                } else {
                    $(obj).css("cursor", "default")
                }
            }
            function GetVersion() { return MzBrowser.version; }
            function GetName() {
                var name = "undefined";
                if (MzBrowser.ie) { name = "ie"; }
                else if (MzBrowser.firefox) { name = "firefox"; }
                else if (MzBrowser.safari) { name = "safari"; }
                return name;
            }
            function IsIE(versionValue) {
                if (versionValue == 11) {
                    return IsIE11();
                }
                var name = GetName();
                var version = GetVersion();
                return name == 'ie' && parseInt(version) == versionValue;
            }

            function test() {
                var count = 0;
                var obj = document.all.authority;

                for (i = 0; i < obj.length; i++) {
                    if (obj[i].checked) {
                        alert(obj[i].value);
                        count++;
                    }
                }
            }
            //搜索节点并展开节点
            function nodeSearching() {
                var dosearch = $.trim($("#dosearch_text").val());//获取要查询的文字
                var dtree_div = $("#dtree_div").find(".dtree_node").show().filter(":contains('" + dosearch + "')");//获取所有包含文本的节点
                if (dtree_div.length > 0){
                    var p = $(dtree_div).parent().parent().parent();
                    $(p.children()).css({display: 'none'});
                    $(p.siblings()).css({display: 'none'});
                    $.each(dtree_div, function (index, element) {
                        var s = $(element).attr("node_id");
                        $($(element).parent().parent()).css({display: 'block'});
                        d.openTo(s);//根据id打开节点
                    });
                } else {
                    layer.msg('关键字搜索不到');
                }
            }

            //判断默认选中复选框
            var u = "{$Request.param.u}";
            if (u != 'undefined' || !isNaN(u)) {
                var arr = u.split(",");
                arr = $.grep(arr, function (n) {
                    return $.trim(n).length > 0;
                });
                $.each(arr, function (key, val) {
                    $('#authority_10000' + val).click();
                });
            }

            $('.good_confirm').click(function () {
                var ids='',name='',url_array = [],m="{$Request.param.m}";
                $('li').each(function(){
                    uname = $(this).attr('name');
                    if ('undefined' != typeof(uname)){
                        name+=uname+',';
                    }

                    id = $(this).attr('uid');
                    if ('undefined' != typeof(id)){
                        ids+=id+',';
                    }
                });
                window.parent.document.getElementById(m+'_select_id').innerText=name;
                window.parent.document.getElementById(m+'_cat').value = ','+ids;

                var d_h='',id_arr = ids.split(',').filter(d=>d),name_arr = name.split(',').filter(d=>d);
                // console.log(id_arr);
                // console.log(name_arr);
                if (id_arr) {
                    $.each(id_arr, function(i){
                        d_h += "<div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                            "                <input type=\"text\" class=\"layui-input field-name fl\" value=\""+name_arr[i]+"\" lay-verify=\"required\" name=\"name["+id_arr[i]+"]\" autocomplete=\"off\" readonly>\n" +
                            "            </div>\n" +
                            "            <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                            "                <input type=\"hidden\" name=\"good_id["+id_arr[i]+"]\" value=\""+id_arr[i]+"\" lay-verify=\"required\">\n" +
                            "                <input type=\"number\" class=\"layui-input field-number fl\" onkeypress=\"return (/[\\d]/.test(String.fromCharCode(event.keyCode)))\" lay-verify=\"required\" name=\"number["+id_arr[i]+"]\" onblur=\"check_v("+id_arr[i]+",this.value)\" autocomplete=\"off\" value='1' placeholder=\"请输入数量\">\n" +
                            "            </div>";
                    });
                }
                window.parent.document.getElementById('show_div').innerHTML = d_h;

                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            });




    </script>
</div>