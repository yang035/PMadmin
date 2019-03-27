<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: 400px;
        margin-right: 10px;
    }

</style>
<form class="layui-form" action="{:url()}" method="post" name="loginForm">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="show_div">
            <div class="layui-form-item">
                <label class="layui-form-label">机器名称</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-name" name="name" readonly
                           autocomplete="off" placeholder="机器名称">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">CPU</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-cpu" name="cpu" readonly
                           autocomplete="off" placeholder="CPU">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">内存总量</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-total_mem" name="total_mem" readonly
                           autocomplete="off" placeholder="机器名称">
                </div>
                <div class="layui-form-mid">M</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">可用内存</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-unuse_mem" name="unuse_mem" readonly
                           autocomplete="off" placeholder="可用内存">
                </div>
                <div class="layui-form-mid">M</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">主板</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-zhuban" name="zhuban" readonly
                           autocomplete="off" placeholder="主板">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">BIOS</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-bios" name="bios" readonly
                           autocomplete="off" placeholder="BIOS">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">主硬盘</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-yingpan" name="yingpan" readonly
                           autocomplete="off" placeholder="硬盘">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">主硬盘序列号</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-serial_number" name="serial_number" readonly
                           autocomplete="off" placeholder="主硬盘序列号">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显卡</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-xianka" name="xianka" readonly
                           autocomplete="off" placeholder="显卡">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">网卡</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-wangka" name="wangka" readonly
                           autocomplete="off" placeholder="网卡">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">网卡MAC</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-mac" name="mac" readonly
                           autocomplete="off" placeholder="网卡MAC">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">IP</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-ip" name="ip" readonly
                           autocomplete="off" placeholder="IP">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显示器</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-xianshiqi" name="xianshiqi" readonly
                           autocomplete="off" placeholder="显示器">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">声卡</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-shengka" name="shengka" readonly
                           autocomplete="off" placeholder="声卡">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">操作系统</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-os_info" name="os_info" readonly
                           autocomplete="off" placeholder="操作系统">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-computer_type" name="computer_type" value="0" title="台式机" checked>
                    <input type="radio" class="field-computer_type" name="computer_type" value="1" title="笔记本">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" class="field-id" name="id">
                    <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                    <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                </div>
            </div>
        </div>
        <div class="red hide_div"></div>
    </div>

</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','element', 'upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,element = layui.element,upload = layui.upload,form = layui.form;

        try{
            var locator = new ActiveXObject ("WbemScripting.SWbemLocator");
            var service = locator.ConnectServer(".");

            //CPU 信息
            var properties = service.ExecQuery("SELECT * FROM Win32_Processor");
            var e = new Enumerator (properties);
            for (;!e.atEnd();e.moveNext ()) {
                var p = e.item ();
                // console.log(p);
                $('.field-name').val(p.SystemName);
                $('.field-cpu').val(p.Name +","+p.NumberOfCores+"核,"+p.MaxClockSpeed+"Hz");
            }

            //主板信息
            var properties = service.ExecQuery("SELECT * FROM Win32_BaseBoard");
            var e = new Enumerator (properties);
            for (;!e.atEnd();e.moveNext ()) {
                var p = e.item ();
                $('.field-zhuban').val(p.Product);
            }

            //BIOS信息
            var properties = service.ExecQuery("SELECT * FROM Win32_BIOS");
            var e = new Enumerator (properties);
            for (;!e.atEnd();e.moveNext ()) {
                var p = e.item ();
                $('.field-bios').val(p.ReleaseDate);
            }

            //获取Ram信息
            var system=new Enumerator (service.ExecQuery("SELECT * FROM Win32_ComputerSystem")).item();
            var physicMenCap=Math.ceil(system.TotalPhysicalMemory/1024/1024);
            //内存信息
            var memory = new Enumerator (service.ExecQuery("SELECT * FROM Win32_PhysicalMemory"));
            for (var mem=[],i=0;!memory.atEnd();memory.moveNext()){
                mem[i++]={cap:memory.item().Capacity/1024/1024,speed:memory.item().Speed};
            }
            memDX = 0;
            for(var mi=0;mi<mem.length;mi++){
                memDX += mem[mi].cap;
            }
            $('.field-total_mem').val(memDX);
            $('.field-unuse_mem').val(physicMenCap);

            //获取网络连接信息
            var properties = service.ExecQuery("SELECT * FROM Win32_NetworkAdapterConfiguration Where IPEnabled=TRUE");
            var e = new Enumerator (properties);
            var i=1;
            for (;!e.atEnd();e.moveNext ()){
                var p = e.item ();

                i++;
                $('.field-wangka').val(p.Caption);
                $('.field-mac').val(p.MACAddress);
                $('.field-ip').val(p.IPAddress(0));
            }

            // 获取操作系统信息
            var properties = service.ExecQuery("SELECT * FROM Win32_OperatingSystem");
            var e = new Enumerator (properties);
            var i=1;
            for (;!e.atEnd();e.moveNext ()){
                var p = e.item ();
                i++;
                $('.field-os_info').val(p.Caption+'('+p.OSArchitecture+'/'+p.CSDVersion+')');
            }

            //硬盘信息
            var properties = service.ExecQuery("SELECT * FROM Win32_DiskDrive");  //Win32_DiskDrive
            var e = new Enumerator (properties);
            for (;!e.atEnd();e.moveNext ()) {
                var p = e.item ();
                $('.field-yingpan').val(p.Size.substr(0,p.Size.length-9)  +"G,型号:" + p.Model);
                $('.field-serial_number').val($.trim(p.SerialNumber));
            }

            //显卡信息
            var properties = service.ExecQuery("SELECT * FROM Win32_VideoController");  //Win32_DiskDrive
            var e = new Enumerator (properties);
            for (;!e.atEnd();e.moveNext ()) {
                var p = e.item ();
                $('.field-xianka').val(p.Name + " 厂商: " + p.AdapterCompatibility);
            }

            //显示器信息
            var properties = service.ExecQuery("SELECT * FROM Win32_DesktopMonitor");  //Win32_DiskDrive
            var e = new Enumerator (properties);
            for (;!e.atEnd();e.moveNext ()) {
                var p = e.item ();
                $('.field-xianshiqi').val(p.PNPDeviceID);
            }

            //声卡信息
            var properties = service.ExecQuery("SELECT * FROM Win32_SoundDevice");  //Win32_DiskDrive
            var e = new Enumerator (properties);
            for (;!e.atEnd();e.moveNext ()) {
                var p = e.item ();
                $('.field-shengka').val(p.Name);
            }
        }catch(e){
            $('.show_div').hide();
            var h = "出错！必须满足以下要求：<br>一、必须用 IE 浏览器；<br>二、设置步骤：IE菜单 -> 工具 -> Internet选项 -> 安全 -> 自定义级别 -> 对未标记为可安全执行脚本的ActiveX控件初始化并执行脚本--选‘提示’，保存修改项；<br>三、刷新浏览器，若有弹出框选择‘是或确认’；<br>四、等待内容自动填充后，点击‘提交’按钮即可。";
            $('.hide_div').html(h);
        }

    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>