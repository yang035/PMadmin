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
            <label class="layui-form-label">部门</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-warm" id="department_user_id" onclick="open_div('department')">选择部门</button>
                <div id="department_select_id">{$data_info['department_select_id']|default=''}</div>
                <input type="hidden" name="department_id" id="department_id" value="{$data_info['department_id']|default=''}">
            </div>
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
                <input type="text" class="layui-input field-username" name="username" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" lay-verify="required"
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
            <label class="layui-form-label">英文名</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-nick" name="nick" lay-verify="required" autocomplete="off" placeholder="请输入英文名">
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
                <input type="text" class="layui-input field-mobile" name="mobile" onkeyup="value=value.replace(/[^\d]/g,'')" lay-verify="phone" maxlength="11"
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
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">日常工作</label>
            <div class="layui-input-inline">
                <select name="work_cat" class="field-work_cat" type="select">
                    {$work_option}
                </select>
            </div>
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
                <input type="radio" class="field-is_auth" name="is_auth" value="1" title="是(需要另一页设置权限)" lay-filter="is_auth">
                <input type="radio" class="field-is_auth" name="is_auth" value="0" title="否" checked lay-filter="is_auth">
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
        <div class="layui-form-item hide" id="sub_id_1">
            <div class="layui-input-block">
                <input type="hidden" class="field-id" name="id">
                <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
    </div>
    <div class="layui-form-item" id="sub_id">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
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

        form.on('radio(is_auth)', function(data){
            if(1 == data.value){
                $('#sub_id').hide();
                $('#sub_id_1').show();
            }else {
                $('#sub_id').show();
            }
        });

        /* 权限赋值 */
        if (formData) {
            for (var i in formData['auth']) {
                $('.role-list-form input[value="' + formData['auth'][i] + '"]').prop('checked', true);
            }
            form.render('checkbox');
        }

        form.on('select(job_cat)', function(data){
            select_union(data.value);
        });
        if (formData.job_cat){
            select_union(formData.job_cat,formData.job_item);
        }else {
            select_union(1);
        }

        function select_union(id,gid){
            var id=id,gid=gid||0;
            $.ajax({
                type: 'POST',
                url: "{:url('getJobItem')}",
                data: {id:id,gid:gid},
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
        }
    });

    function open_div(flag) {
        var flag_user = $('#'+flag+'_id').val();
        var open_url = "{:url('Tool/getTreeDep')}?m="+flag+"&u="+flag_user;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'员工列表',
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>