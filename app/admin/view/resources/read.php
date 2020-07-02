{notempty name="data_info[$Request.param.atype]"}
{volist name="data_info[$Request.param.atype]" id="vo"}
{$vo['content']}
{/volist}
{/notempty}
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>