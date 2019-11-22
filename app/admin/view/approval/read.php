<link rel="stylesheet" href="__ADMIN_JS__/pictureViewer/css/pictureViewer.css">
<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
    #pictureViewer > .content{
        background-color: #fff;
        position: absolute;
        width: 590px;
        height: 450px;
        margin: auto;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .layui-btn {
        display: inline-block;
        height: 38px;
        line-height: 38px;
        padding: 0 18px;
        background-color: #009688;
        color: #fff;
        white-space: nowrap;
        text-align: center;
        font-size: 14px;
        border: none;
        border-radius: 2px;
        cursor: pointer;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            {if condition="$class_type eq 2 && $list1"}
            {eq name="ct" value='4'}
            <blockquote class="layui-elem-quote" style="color: grey">
                申请时间：{$list1['create_time']|date='Y-m-d H:i:s',###}<br>
                姓名：{$list1['real_name']}<br>
                开始时间：{$list1['start_time']}<br>
                结束时间：{$list1['end_time']}<br>
                项目名称：{$list1['project_name']}<br>
                地点：{$list1['address']}<br>
                同行人：{$list1['fellow_user']}<br>
                事由：{$list1['reason']}<br>
                附件说明：
                {notempty name="list1['attachment'][0]"}
                <!--            <div class="image-list">-->
                <ul>
                    {volist name="list1['attachment']" id="vo"}
                    <!--                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>-->
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{$vo}" style="color: #5c90d2">附件{$i}</a></li>
                    {/volist}
                </ul>
                <!--            </div>-->
                {else/}
                <span>无</span>
                {/notempty}
                <br>
                审批人：{$list1['send_user']}<br>
                抄送人：{$list1['copy_user']}<br>
                结果：{$approval_status[$list1['status']]}<br>
                备注：{$list1['mark']}<br>
                批示时间：{$list1['update_time']|date='Y-m-d H:i:s',###}<br>

                <div class="layui-card-header">报告记录</div>
                <ul class="layui-timeline">
                    {volist name="report_info" id="vo"}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                <span style="color: red">[{$vo['create_time']}]</span>
                                <br>
                                {$vo['mark']}
                                <br>
                                {notempty name="vo['attachment']"}
                                <ul>
                                    {volist name="vo['attachment']" id="v"}
                                    <li>
                                        <a target="_blank" href="{$v}">附件{$i}</a>
                                    </li>
                                    {/volist}
                                </ul>
                                <br>
                                {/notempty}
                                <ul>
                                    {volist name="vo['reply']" id="v"}
                                    <li>
                                        <span style="color: grey">[{$v['create_time']}回复]</span><br>
                                        {$v['content']}
                                    </li>
                                    {/volist}
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/volist}
                </ul>

            </blockquote>
            {/eq}
            {eq name="ct" value='3'}
            <blockquote class="layui-elem-quote" style="color: grey">
                申请时间：{$list1['create_time']|date='Y-m-d H:i:s',###}<br>
                姓名：{$list1['real_name']}<br>
                开始时间：{$list1['start_time']}<br>
                结束时间：{$list1['end_time']}<br>
                项目名称：{$list1['project_name']}<br>
                费用类型：{$expense_type[$list1['type']]}<br>
                金额：{$list1['money']}<br>
                事由：{$list1['reason']}<br>
                附件说明：
                {notempty name="list1['attachment'][0]"}
                <!--            <div class="image-list">-->
                <ul>
                    {volist name="list1['attachment']" id="vo"}
                    <!--                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>-->
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{$vo}" style="color: #5c90d2">附件{$i}</a></li>
                    {/volist}
                </ul>
                <!--            </div>-->
                {else/}
                <span>无</span>
                {/notempty}
                <br>
                审批人：{$list1['send_user']}<br>
                抄送人：{$list1['copy_user']}<br>
                结果：{$approval_status[$list1['status']]}<br>
                备注：{$list1['mark']}<br>
                批示时间：{$list1['update_time']|date='Y-m-d H:i:s',###}<br>
            </blockquote>
            {/eq}
            {/if}
            {if condition="$class_type eq 15"}
            <blockquote class="layui-elem-quote" style="color: grey">
                申请时间：{$list1['create_time']|date='Y-m-d H:i:s',###}<br>
                姓名：{$list1['real_name']}<br>
                开始时间：{$list1['start_time']}<br>
                结束时间：{$list1['end_time']}<br>
                项目名称：{$list1['project_name']}<br>
                请假类型：{$list1['type']}<br>
                事由：{$list1['reason']}<br>
                附件说明：
                {notempty name="list1['attachment'][0]"}
                <!--            <div class="image-list">-->
                <ul>
                    {volist name="list1['attachment']" id="vo"}
                    <!--                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>-->
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{$vo}" style="color: #5c90d2">附件{$i}</a></li>
                    {/volist}
                </ul>
                <!--            </div>-->
                {else/}
                <span>无</span>
                {/notempty}
                <br>
                审批人：{$list1['send_user']}<br>
                抄送人：{$list1['copy_user']}<br>
                结果：{$approval_status[$list1['status']]}<br>
                备注：{$list1['mark']}<br>
                批示时间：{$list1['update_time']|date='Y-m-d H:i:s',###}<br>
            </blockquote>
            {/if}
            申请时间：{$data_list['create_time']|date='Y-m-d H:i:s',###}<br>
            姓名：{$data_list['real_name']}<br>
            开始时间：{$data_list['start_time']}<br>
            结束时间：{$data_list['end_time']}<br>
            项目名称：{$project_name['name']}<br>
            {switch name="class_type"}
                {case value="1"}
                请假类型：{$leave_type[$data_list['type']]}<br>
                {/case}
                {case value="2"}
                报销明细：<br>
                {volist name="$data_list['detail']" id="vo"}
                &nbsp;&nbsp;&nbsp;&nbsp;{$expense_type[$vo['type']]} ~ {$vo['amount']}元(说明：{$vo['mark']})<br>
                {/volist}
                合计：{$data_list['total']}元<br>
                {/case}
                {case value="3"}
                费用类型：{$expense_type[$data_list['type']]}<br>
                金额：{$data_list['money']}<br>
                {/case}
                {case value="4"}
                地点：{$data_list['address']}<br>
                同行人：{$data_list['fellow_user']}<br>
                {/case}
                {case value="5"}
                物品名称：{$data_list['name']}<br>
                数量：{$data_list['number']}<br>
                总价：{$data_list['amount']}元<br>
                供应商：{$data_list['supplier']}<br>
                产品链接：<a href="{$data_list['url']}" target="_blank" style="color: #5c90d2">{$data_list['url']}</a><br>
                {/case}
                {case value="6"}
                加班类型：{$overtime_type[$data_list['overtime_type']]}<br>
                加班时长：{$data_list['time_long']}小时<br>
                {/case}
                {case value="7"}
                外出地点：{$data_list['address']}<br>
<!--                外出时长：{$data_list['time_long']}小时<br>-->
                同行人：{$data_list['fellow_user']}<br>
                {/case}
                {case value="8"}
                司机：{$data_list['deal_user']}<br>
                同行人：{$data_list['fellow_user']}<br>
                车辆类型：{$car_type[$data_list['car_type']]}<br>
                发车前照片：
                    {notempty name="data_list['before_img']"}
                    <div class="image-list">
                        {volist name="data_list['before_img']" id="vo"}
                        <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>
                        {/volist}
                    </div>
                    {else/}
                    <span>无</span>
                    {/notempty}
                    <br>
                    回来后照片：
                    {notempty name="data_list['after_img']"}
                    <div class="image-list">
                        {volist name="data_list['after_img']" id="vo"}
                        <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>
                        {/volist}
                    </div>
                    {else/}
                    <span>无</span>
                    {/notempty}
                    <br>
                {/case}
                {case value="9"}
                {/case}
                {case value="10"}
                {/case}
                {case value="11"}
                    {notempty name="data_list['goods']"}
                    物品清单：
                    <div>
                        {volist name="data_list['goods']" id="vo"}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$vo['name']}:{$vo['number']}<br>
                        {/volist}
                    </div>
                    {else/}
                    <span>无</span>
                    {/notempty}
                {/case}
                {case value="12"}
                用途：{$data_list['application']}<br>
                打印类型：{$data_list['type']}<br>
                规格：<br>{$data_list['s']}
                打印单位：{$data_list['store_id']}<br>
                {/case}
                {case value="13"}
                派遣地点：{$data_list['address']}<br>
                执行人：{$data_list['deal_user']}<br>
                联系人：{$data_list['contacts']}<br>
                随身物品：{$data_list['belongs']}<br>
                {/case}
                {case value="14"}
                    {notempty name="data_list['borrow']"}
                    物品清单：
                    <div>
                        {volist name="data_list['borrow']" id="vo"}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$vo}<br>
                        {/volist}
                    </div>
                    {else/}
                    <span>无</span>
                    {/notempty}
                {/case}
                {case value="16"}
                日期：{$data_list['date']}<br>
                内容明细：<br>
                {volist name="$data_list['detail']" id="vo"}
                    说明：{$vo['content']}&nbsp;&nbsp;|&nbsp;&nbsp;计量：{$vo['num']}{$unit_type[$vo['unit']]}&nbsp;&nbsp;|&nbsp;&nbsp;单价：{$vo['per_price']}元（合计：{$vo['num']*$vo['per_price']}元）<br>
                {/volist}
                <br>
                总计：{$data_list['money']}元<br>
                施工员：{$data_list['shigong_user']}<br>
                {/case}
            {/switch}
            事由：{$data_list['reason']}<br>
            附件说明：
            {notempty name="data_list['attachment'][0]"}
<!--            <div class="image-list">-->
            <ul>
                {volist name="data_list['attachment']" id="vo"}
<!--                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>-->
                <li>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{$vo}" style="color: #5c90d2">附件{$i}</a></li>
                {/volist}
            </ul>
<!--            </div>-->
            {else/}
            <span>无</span>
            {/notempty}
            <br>
            审批人：{$data_list['send_user']}<br>
            抄送人：{$data_list['copy_user']}<br>

            {notempty name="su_list"}
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                <legend>流程审批</legend>
            </fieldset>
            <ul class="layui-timeline">
                {volist name="su_list" id="vo"}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                            {$vo['flow_num']+1}级审批(任一人审批)：{$vo['send_user']}<br>
                            {if condition="($vo['status'] eq 1) && ($status[$vo['flow_num']-1] eq 2) && $vo['cunzai'] && ($Request.param.atype eq 3)"}
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <input type="radio" name="status" value="2" title="同意" checked>
                                    <input type="radio" name="status" value="4" title="驳回">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">备注</label>
                                <div class="layui-input-inline">
                                    <textarea type="text" class="layui-textarea field-mark" name="mark" autocomplete="off" placeholder=""></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                                    <input type="hidden" class="field-atype" name="atype" value="{$Request.param.atype}">
                                    <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
                                    <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                                    <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                                </div>
                            </div>
                            {else/}
                            结果：{$approval_status[$vo['status']]}{eq name="vo['status']" value="4"}<font style="color: red">（流程终止）</font>{/eq}<br>
                                {if condition="$vo['status'] neq 1"}
                                    备注：{$vo['mark']}<br>
                                    批示时间：{$vo['update_time']}<br>
                                {/if}
                            {/if}
                            </div>
                        </div>
                    </li>
                {/volist}
            </ul>
            {else/}
                {if condition="($data_list['status'] eq 1) && ($Request.param.atype eq 3) "}
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="2" title="同意" checked>
                        <input type="radio" name="status" value="4" title="驳回">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-inline">
                        <textarea type="text" class="layui-textarea field-mark" name="mark" autocomplete="off" placeholder=""></textarea>
                    </div>
                </div>
                <br>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                        <input type="hidden" class="field-atype" name="atype" value="{$Request.param.atype}">
                        <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
                        <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                        <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                    </div>
                </div>
                {else/}
                <br>
                结果：{$approval_status[$data_list['status']]}<br>
                备注：{$data_list['mark']}<br>
                批示时间：{$data_list['update_time']|date='Y-m-d H:i:s',###}<br>
                {/if}
            {/notempty}
            <hr>
            {eq name="data_list['status']" value="2"}
            {in name="Request.param.class_type" value="2,3,5"}
                {if condition="($Request.param.atype eq 4) && ($data_list['is_deal'] eq 0)"}
                <div class="layui-form-item">
                    <label class="layui-form-label">支付结果</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_deal" value="1" title="未支付">
                        <input type="radio" name="is_deal" value="2" title="支付">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">支付备注</label>
                    <div class="layui-input-inline">
                        <textarea type="text" class="layui-textarea field-deal_mark" name="deal_mark" autocomplete="off" placeholder="请输入备注"></textarea>
                    </div>
                </div>
                <br>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                        <input type="hidden" class="field-atype" name="atype" value="{$Request.param.atype}">
                        <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
                        <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                        <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                    </div>
                </div>
                {else/}
                <br>
                支付结果：{eq name="data_list['is_deal']" value="2"}已支付{else/}未支付{/eq}<br>
                支付备注：{$data_list['deal_mark']}<br>
                支付时间：{$data_list['deal_time']}<br>
                {/if}
            {/in}
            {/eq}
        </div>
    </div>
    {if condition="($Request.param.class_type eq 8) && ($Request.param.atype eq 5) "}
        {empty name="$data_list['before_img']"}
        <div class="layui-form-item">
            <label class="layui-form-label" >发车前</label>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img1">左前方照片</button>
                <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img2">右前方照片</button>
                <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img3">后面照片</button>
                <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img4">中控照片</button>
                <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
        </div>
        {/empty}
        {if condition="!empty($data_list['before_img']) && empty($data_list['after_img']) "}
        <div class="layui-form-item">
            <label class="layui-form-label" >回来后</label>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img11">左前方照片</button>
                <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img22">右前方照片</button>
                <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img33">后面照片</button>
                <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
            <div class="layui-input-block upload">
                <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img44">中控照片</button>
                <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
                <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
        </div>
        {/if}
        <br>
        {if condition="empty($data_list['before_img']) || empty($data_list['after_img']) "}
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                <input type="hidden" class="field-atype" name="atype" value="{$Request.param.atype}">
                <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
                <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
        {/if}
    {/if}
    {if condition="$Request.param.class_type eq 4"}
        {notin name="$Request.param.atype" value="3,4,5,6"}
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">出差报告</div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">内容<span style="color: red"></span></label>
                        <div class="layui-input-inline">
                            <textarea type="text" class="layui-textarea field-mark" name="mark" autocomplete="off" lay-verify="required" placeholder="请输入内容"></textarea>
                        </div>
                        <div class="layui-form-mid" style="color: red">*</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">附件说明</label>
                        <div class="layui-input-inline">
                            <div class="layui-upload">
                                <button type="button" class="layui-btn layui-btn-normal" id="testList">选择多文件</button>
                                <div class="other-div" style="display: none">
                                    <div class="layui-upload-list">
                                        <table class="layui-table">
                                            <thead>
                                            <tr>
                                                <th>文件名</th>
                                                <th>大小</th>
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
                            <input type="hidden" class="field-id" name="aid" value="{$Request.param.id}">
                            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                            <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
                            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                        </div>
                    </div>
            </div>
        </div>
        {/notin}
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">报告记录</div>
                <ul class="layui-timeline">
                    {volist name="report_info" id="vo"}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                <span style="color: red">[{$vo['create_time']}]</span>
                                <a onclick="open_reply({$vo['id']},{$vo['aid']})" class="layui-btn layui-btn-normal layui-btn-xs">回复</a>
                                <br>
                                {$vo['mark']}
                                <br>
                                {notempty name="vo['attachment']"}
                                <ul>
                                    {volist name="vo['attachment']" id="v"}
                                    <li>
                                        <a target="_blank" href="{$v}">附件{$i}</a>
                                    </li>
                                    {/volist}
                                </ul>
                                <br>
                                {/notempty}
                                <ul>
                                    {volist name="vo['reply']" id="v"}
                                    <li>
                                        <span style="color: grey">[{$v['create_time']}回复]</span><br>
                                        {$v['content']}
                                    </li>
                                    {/volist}
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/volist}
                </ul>
            </div>
        </div>
    {/if}
</form>
{include file="block/layui" /}
<script src="__ADMIN_JS__/pictureViewer/js/pictureViewer.js"></script>
<script src="__ADMIN_JS__/pictureViewer/js/jquery.mousewheel.min.js"></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','flow', 'upload'], function () {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload,flow = layui.flow;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });
        $('.image-list').on('click', '.cover', function () {
            var this_ = $(this);
            var images = this_.parents('.image-list').find('.cover');
            var imagesArr = new Array();
            $.each(images, function (i, image) {
                imagesArr.push($(image).children('img').attr('src'));
            });
            $.pictureViewer({
                images: imagesArr, //需要查看的图片，数据类型为数组
                initImageIndex: this_.index() + 1, //初始查看第几张图片，默认1
                scrollSwitch: true //是否使用鼠标滚轮切换图片，默认false
            });
        });

        upload.render({
            elem: '#img1',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        upload.render({
            elem: '#img2',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        upload.render({
            elem: '#img3',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        upload.render({
            elem: '#img4',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        upload.render({
            elem: '#img11',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        upload.render({
            elem: '#img22',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        upload.render({
            elem: '#img33',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        upload.render({
            elem: '#img44',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        $('.upload img').attr('src', $('.field-img').val()).show();

        //多文件列表示例
        var demoListView = $('#demoList'), uploadListIns = upload.render({
            elem: '#testList',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file',
            size: "{:config('upload.upload_file_size')}",
            multiple: true,
            auto: false,
            bindAction: '#testListAction',
            choose: function (obj) {
                var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                //读取本地文件
                obj.preview(function (index, file, result) {
                    var tr = $(['<tr id="upload-' + index + '">'
                        , '<td>' + file.name + '</td>'
                        , '<td>' + (file.size / 1014).toFixed(1) + 'kb</td>'
                        , '<td>等待上传</td>'
                        , '<td>'
                        , '<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                        , '<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                        , '</td>'
                        , '</tr>'].join(''));

                    //单个重传
                    tr.find('.demo-reload').on('click', function () {
                        obj.upload(index, file);
                    });

                    //删除
                    tr.find('.demo-delete').on('click', function () {
                        delete files[index]; //删除对应的文件
                        tr.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });

                    demoListView.append(tr);
                });
                $('.other-div').show();
            }
            , done: function (res, index, upload) {
                if (res.code == 1) { //上传成功
                    var tr = demoListView.find('tr#upload-' + index)
                        , tds = tr.children();
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html(''); //清空操作
                    var new_value = $('.field-attachment').val();
                    new_value += res.data.file + ',';
                    $('.field-attachment').val(new_value);
                    return delete this.files[index]; //删除文件队列已经上传成功的文件
                }
                this.error(index, upload);
            }
            , error: function (index, upload) {
                var tr = demoListView.find('tr#upload-' + index)
                    , tds = tr.children();
                tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });

    });

    function open_reply(id,project_id) {
        var open_url = "{:url('ApprovalReportReply/add')}?id="+id+"&aid="+project_id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            maxmin: true,
            title :'回复',
            area: ['600px', '400px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                body.contents().find(".field-report_id").val(id);
                body.contents().find(".field-aid").val(project_id);
            }
        });
    }

</script>
<script src="__ADMIN_JS__/footer.js"></script>