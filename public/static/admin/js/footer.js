layui.use(['jquery'], function() {
    var $ = layui.jquery, input = '';
    /* 修改模式下表单自动赋值 */
    if (formData) {
        for (var i in formData) {
            switch($('.field-'+i).attr('type')) {
                case 'select':
                    input = $('.field-'+i).find('option[value="'+formData[i]+'"]');
                    input.prop("selected", true);
                    break;
                case 'radio':
                    input = $('.field-'+i+'[value="'+formData[i]+'"]');
                    input.prop('checked', true);
                    break;
                case 'checkbox':
                    if (typeof(formData[i]) == 'object') {
                        for(var j in formData[i]) {
                            input = $('.field-'+i+'[value="'+formData[i][j]+'"]');
                            input.prop('checked', true);
                        }
                    } else {
                        input = $('.field-'+i+'[value="'+formData[i]+'"]');
                        input.prop('checked', true);
                    }
                    break;
                case 'img':
                    input = $('.field-'+i);
                    input.attr('src', formData[i]);
                default:
                    input = $('.field-'+i);
                    input.val(formData[i]);
                    break;
            }
            if (input.attr('data-disabled')) {
                input.prop('disabled', true);
            }
            if (input.attr('data-readonly')) {
                input.prop('readonly', true);
            }
        }
    }

    Date.prototype.Format = function(fmt)
    {
        var o = {
            "M+" : this.getMonth()+1,                 //月份
            "d+" : this.getDate(),                    //日
            "h+" : this.getHours(),                   //小时
            "m+" : this.getMinutes(),                 //分
            "s+" : this.getSeconds(),                 //秒
            "q+" : Math.floor((this.getMonth()+3)/3), //季度
            "S"  : this.getMilliseconds()             //毫秒
        };
        if(/(y+)/.test(fmt))
            fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
        for(var k in o)
            if(new RegExp("("+ k +")").test(fmt))
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        return fmt;
    }
});