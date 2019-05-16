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
            <label class="layui-form-label">选择部门</label>
            {neq name="ADMIN_ROLE" value="1"}
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-dep_name" id="menu_parent_name" lay-verify="required" readonly
                       name="dep_name" autocomplete="off">
                <div id="treediv" style="overflow:scroll;position: relative;z-index:9999;display:none;">
                    <div align="right"><a href="##" id="closed"><font color="#000">关闭&nbsp;</font></a></div>
                    <script language="JavaScript" type="text/JavaScript">
                        mydtree = new dTree('mydtree', '/static/js/dtree/img/', 'no', 'no');
                        var ajax_url = "{:url('admin/tool/getTreeDep')}";
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
                <select name="company_id" class="field-company_id" type="select">
                    {$company_option}
                </select>
            </div>
            {/neq}
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色分组</label>
            <div class="layui-input-inline">
                <select name="role_id" class="field-role_id" type="select">
                    {$role_option}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-username" name="username" lay-verify="required"
                       autocomplete="off" placeholder="请输入用户名">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">真实姓名</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-realname" name="realname" lay-verify="required"
                       autocomplete="off" placeholder="请输入真实姓名">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-inline">
                <select name="sex" class="field-sex" type="select">
                    {$sex_type}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系手机</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-mobile" name="mobile" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" lay-verify="phone" maxlength="11"
                       autocomplete="off" placeholder="请输入手机号码">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">登陆密码</label>
            <div class="layui-input-inline">
                <input type="password" class="layui-input" name="password" autocomplete="off"
                       placeholder="******">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" class="layui-input" name="password_confirm"
                       autocomplete="off" placeholder="******">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系邮箱</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-email" name="email" autocomplete="off"
                       placeholder="请输入邮箱地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">工作岗位</label>
            <div class="layui-input-inline">
                <select name="job_cat" class="field-job_cat" type="select" lay-filter="job_cat">
                    {$rule_option}
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="job_item" class="field-job_item" type="select" lay-filter="rid" id="c_id">
                </select>
            </div>
            <div class="layui-form-mid field_job_item">{$data_info['job_item']|default=''}</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状&nbsp;&nbsp;&nbsp;&nbsp;态</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
                <input type="radio" class="field-status" name="status" value="0" title="禁用">
            </div>
        </div>
        {eq name="ADMIN_ROLE" value="1"}
        <div class="layui-form-item">
            <label class="layui-form-label">是否虚拟账号</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-is_show" name="is_show" value="1" title="是" checked>
                <input type="radio" class="field-is_show" name="is_show" value="0" title="否">
            </div>
        </div>
        {/eq}
        <div class="layui-form-item">
            <label class="layui-form-label">变更权限</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-is_auth" name="is_auth" value="1" title="是">
                <input type="radio" class="field-is_auth" name="is_auth" value="0" title="否" checked>
            </div>
        </div>
    </div>
    <div class="layui-tab-item layui-form">
        <div class="layui-collapse page-tips">
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">温馨提示</h2>
                <div class="layui-colla-content layui-show">
                    <p>
                        默认使用当前用户的角色分组，您可以针对此用户单独设置角色分组以外的权限；如果您更改了角色分组未保存，则单独设置权限会无效哦！
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-form-item role-list-form">
            {volist name="menu_list" id="v"}
            <dl class="role-list-form-top">
                <dt><input type="checkbox" name="auth[]" lay-filter="roleAuth" value="{$v['id']}" data-parent="0"
                           data-level="1" lay-skin="primary" title="{$v['title']}"></dt>
                <dd>
                    {volist name="v['childs']" id="vv"}
                    <dl>
                        <dt><input type="checkbox" name="auth[]" lay-filter="roleAuth" value="{$vv['id']}"
                                   data-pid="{$vv['pid']}" data-level="2" lay-skin="primary" title="{$vv['title']}">
                        </dt>
                        <dd>
                            {volist name="vv['childs']" id="vvv"}
                            <dl>
                                <dt><input type="checkbox" name="auth[]" lay-filter="roleAuth" value="{$vvv['id']}"
                                           data-pid="{$vvv['pid']}" data-level="3" lay-skin="primary"
                                           title="{$vvv['title']}"></dt>
                                <dd>
                                    {volist name="vvv['childs']" id="vvvv"}
                                    <input type="checkbox" name="auth[]" lay-filter="roleAuth" value="{$vvvv['id']}"
                                           data-pid="{$vvvv['pid']}" data-level="4" lay-skin="primary"
                                           title="{$vvvv['title']}">
                                    {/volist}
                                </dd>
                            </dl>
                            {/volist}
                        </dd>
                    </dl>
                    {/volist}
                </dd>
            </dl>
            {/volist}
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" class="field-id" name="id">
                <input type="hidden" class="field-department_id" name="department_id">
                <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
    </div>
    {empty name="$Request.param.id"}
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <input type="hidden" class="field-department_id" name="department_id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
    {/empty}
</form>

<script>
    var formData = {:json_encode($data_info)};
    layui.use(['form'], function () {
        var $ = layui.jquery, form = layui.form;
        /* 有BUG 待完善*/
        form.on('checkbox(roleAuth)', function (data) {
            var child = $(data.elem).parent('dt').siblings('dd').find('input');
            /* 自动选中父节点 */
            var check_parent = function (id) {
                var self = $('.role-list-form input[value="' + id + '"]');
                var pid = self.attr('data-pid') || '';
                self.prop('checked', true);
                if (pid == '') {
                    return false;
                }
                check_parent(pid);
            };
            /* 自动选中子节点 */
            child.each(function (index, item) {
                item.checked = data.elem.checked;
            });
            check_parent($(data.elem).attr('data-pid'));
            form.render('checkbox');
        });

        /* 权限赋值 */
        if (formData) {
            for (var i in formData['auth']) {
                $('.role-list-form input[value="' + formData['auth'][i] + '"]').prop('checked', true);
            }
            form.render('checkbox');
        }

        // var i=0;
        // console.log(formData.job_cat);
        select_union();
        function select_union(){
            form.on('select(job_cat)', function(data){
                $.ajax({
                    type: 'POST',
                    url: "{:url('getJobItem')}",
                    data: {id:data.value},
                    dataType:  'json',
                    success: function(data){
                        // $("#c_id").html("");
                        // $.each(data, function(key, val) {
                        //     var option1 = $("<option>").val(val.areaId).text(val.fullname);
                        $('#c_id').html(data);
                        form.render('select');
                        // });
                        // $("#c_id").get(0).selectedIndex=0;
                    }
                });
            });
        }
    });

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
        $(".field-department_id").val(id);
        $(".field-pid").val(pid);
        $(".field-code").val(code);
        $("#treediv").hide();
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>