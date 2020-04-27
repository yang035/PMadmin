<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <p class="MsoNormal" align="center" style="text-align:center;text-indent:2em;">
        <b><span style="font-size:18.0pt;font-family:宋体;">项目合作协议<span></span></span></b>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;"></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">项目发包人（以下简称甲方）：<u> <span>MLGL平台</span></u><span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">项目负责人（以下简称乙方）：<u> <span>&nbsp;</span><span>{$user['realname']}<span>&nbsp; </span></span></u>（身份证号码：<u> <span>{$user['idcard']}&nbsp;</span></u><span><span>&nbsp;</span></span>）<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">本协议依照《合同法》、《建筑法》及地方等有关法律法规的规定，本着国家、企业、个人三者利益，按照各尽所能、按劳分配的原则，经甲乙双方友好协商，为明确各方的权利义务，特制定本协议，甲、乙双方共同遵守执行。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">一、合作方式<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">1</span><span style="font-family:宋体;">、乙方承接<u> <span>&nbsp;</span><span>{$data_info['name']}&nbsp;<span>&nbsp;</span></span></u>设计任务，本项目采取项目负责人管理制，任务分配及费用支付由项目负责人负责，并承担相应设计的全部工作任务及责任，团队成员需要在甲方平台资格备案并接受甲方管理和考核。甲方提供项目设计任务所需的相关资料，并对项目设计文件进行内部技术审查，审查合格后进行签字盖章。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">2</span><span style="font-family:宋体;">、合作期限：本协议期限为<u><span><span>&nbsp;&nbsp; </span>{$xieyi['begin_date']}<span>&nbsp;&nbsp; </span></span></u>至<u><span><span>&nbsp;&nbsp; </span>{$xieyi['end_date']}<span>&nbsp;&nbsp; </span></span></u>。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">（注：实际协议期限以设计合同为准）<span></span></span>
    </p>
    <div class="layui-form">
        <table class="layui-table mt10" lay-even="" lay-skin="row">
            <thead>
            <tr>
                <th>专业类型</th>
                <th>专业</th>
                <th>进度系数</th>
                <th>人员</th>
                <th>ML(斗)</th>
                <th>合伙系数</th>
                <th>协议单价</th>
                <th>参考总价</th>
            </tr>
            </thead>
            <tbody>
            {volist name="data_info['small_major_deal_arr']" id="f"}
            {if condition="$f['value'] > 0"}
                {volist name="f['child']" id="f1"}
                <tr {if condition="$f1['dep_name'] == $user['realname']"} style="font-weight: bold" {/if}>
                    <td>{$f['name']}({$f['value']/100})</td>
                    <td>{$f1['name']}({$f1['value']/100})</td>
                    <td>1.00</td>
                    <td>{$f1['dep_name']|default=''}</td>
                    <td>{$f1['ml']}</td>
                    <td>{$f1['hehuo_name']['name']|default=''}({$f1['hehuo_name']['ratio']|default=''})</td>
                    <td>{$f1['per_price']|default=''}</td>
                    <td>{$f1['ml']*$f1['per_price']}</td>
                </tr>
                {/volist}
            {/if}
            {/volist}
            </tbody>
        </table>
    </div>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">3</span><span style="font-family:宋体;">、技术服务费用标准及支付：<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">规模<u> <span>{$data_info['score']}&nbsp;<span>&nbsp;</span></span></u>平方米，项目剩余工作量<u><span><span>&nbsp; </span>{$xieyi['remain_work']}%<span>&nbsp; </span></span></u>。（注：实际面积以设计合同为准）<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <br />
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">技术服务费按平台规则进行计算和支付。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">（注：平台计算结果为税前）<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">4</span><span style="font-family:宋体;">、本项目设计费支付受当期<span>GL</span>影响：<span>
                {eq name="xieyi['is_gl']" value='1'}是{else/}否{/eq}</span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">5</span><span style="font-family:宋体;">、设计条款：<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">① 合伙级别详见附表一<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">② 专业配比详见附表二<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">③ 专业系数详见附表三<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">④<span> GL</span>排名系数详见附表四<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">二、双方的权利和责任：<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">1</span><span style="font-family:宋体;">、甲方负责客户的的前端对接及客户关系维护，并按照设计流程对项目管理和成果验收。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">2</span><span style="font-family:宋体;">、甲方根据协议要求对项目考核并按要求支付设计费。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">3</span><span style="font-family:宋体;">、乙方严格履行甲方与业主签订的项目设计合同，全面负责甲方与业主在合同中规定的责任和义务，按时保质保量，给甲方创出良好的信誉<span>,</span>否则因此造成的损失由乙方承担。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">4</span><span style="font-family:宋体;">、乙方必须全力维护甲方企业形象和企业信誉，不得做出任何假冒、欺诈、侵权等违法违规事情，在经营活动中应严格遵守国家法律法规及甲方的各项规章制度规定执行，若发生此类情况，则甲方有权追究乙方的法律责任，并要求进行相关经济赔偿和处罚。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">5</span><span style="font-family:宋体;">、乙方在计划<span>30%</span>时间内无明显进展或中途无论任何原因不能胜任或无法继续工作，甲方将委任新任项目负责人继续负责执行项目工作，具体项目结算统一由新任任项目负责人支配，原负责人自愿放弃已完成工作量的报酬要求。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">6</span><span style="font-family:宋体;">、乙方设计项目的地勘报告、规划设计文件、合同原件、设计档案及图纸电子版、竣工验收报告及相关现场文件等资料必须上报一份甲方进行统一存档。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">三、其它<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;"><span>&nbsp;&nbsp;&nbsp;&nbsp; </span>1</span><span style="font-family:宋体;">、上述条款若有违反，按有关法律法规、甲方管理制度及有关规定和追究责任，如乙方承接的工程设计出现任何问题，乙方承担该项目工程设计造成的全部经济损失及法律责任，并且承担给甲方造成的全部经济损失。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">2</span><span style="font-family:宋体;">、如本协议以个人名义签订，应有个人身份证复印件加盖手印。<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
	<span style="font-family:宋体;"><br />
</span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
	<span style="font-family:宋体;"><br />
</span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;"></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">甲方签字（盖章）： <span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>乙方签字（盖章）： <span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">联系方式： <span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>联系方式：</span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
	<span style="font-family:宋体;"><br />
</span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;签订日期：</span><span style="font-family:宋体;">&nbsp;&nbsp;&nbsp; </span><span style="font-family:宋体;">年</span><span style="font-family:宋体;">&nbsp; </span><span style="font-family:宋体;">月</span><span style="font-family:宋体;">&nbsp; </span><span style="font-family:宋体;">日</span>
    </p>
    <p style="text-indent:2em;">
        <br />
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">附件1:<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">{$xieyi['att1']}<span></span></span>
    </p>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">附件2:<span></span></span>
    </p>
    <br>
    <p class="MsoNormal" style="text-indent:2em;">
        <span style="font-family:宋体;">{$xieyi['att2']}<span></span></span>
    </p>
    <hr>
    {eq name="Request.param.p" value="s"}
    {eq name="xieyi['is_sign']" value="0"}
    <div class="layui-form-item">
        <label class="layui-form-label">登陆密码</label>
        <div class="layui-input-inline">
            <input type="password" class="layui-input" name="password" autocomplete="off"
                   placeholder="******" lay-verify="required">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <input type="hidden" class="layui-input" name="id" autocomplete="off" value="{$Request.param.id}">
    <button type="button" class="layui-btn layui-btn-normal" onclick="agree()">同意协议</button>
    {else/}
    <div class="layui-form-mid" style="color: red">{$user['realname']}(已签署)</div>
    {/eq}
    {/eq}
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate','upload','form','element'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form,element = layui.element;
    });
    function agree(){
        var open_url = "{:url('signXieyi')}";
        var data = $("form").serialize();
        $.post(open_url,data,function(res) {
            var index = parent.layer.getFrameIndex(window.name);
            if (res.code == 1) {
                layer.alert(res.msg,{
                    yes:function(){
                        parent.layer.close(index);
                    }
                });
            }else {
                layer.alert(res.msg);
            }
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>