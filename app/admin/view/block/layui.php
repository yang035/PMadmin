<script src="__ADMIN_JS__/layui/pinyin.js"></script>
<script src="__ADMIN_JS__/layui/initials.js"></script>
<script src="__ADMIN_JS__/layui/layui.js?v={:config('pmadmin.version')}"></script>
<script src="__PUBLIC_JS__/jquery.2.1.4.min.js?v="></script>
<script src="__PUBLIC_JS__/layer/layer.js?v="></script>

<script>
    var ADMIN_PATH = "{$_SERVER['SCRIPT_NAME']}";
    layui.config({
        base: '__ADMIN_JS__/',
        version: '{:config("pmadmin.version")}',
        debug:false,
    }).use('global');
</script>