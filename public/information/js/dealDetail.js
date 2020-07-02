
$(function(){
    if(myplatformCode != null && myplatformCode != '' && myplatformCode != 'null') {
        $.ajax({
            type : "POST",
            url : mybasepath+"/deal/service/getPlatformName_service.jsp",
            data : {platformCode:myplatformCode,areaId:myareaId},
            dataType : "json",
            success : function(data) {
                if (data.success) {
                    $("#platformName").html(data.name);
                }
            }
        });
    }

    var param = {code:'200302',typecode:mysid};
    var url = mybasepath+"/interface4j/include/visitcmd.jsp";
    $.ajax({
        type : "POST",
        url : url,
        data : param,
        dataType : "json",
        success : function(data) {

        }
    });
    var breakall= true;
    $(".detail_Table tbody:first").children("tr").children("td").each(function() {
        if($(this).html().indexOf("<")>=0&&$(this).html().indexOf(">")>=0) {
            //	$(this).css("word-break","keep-all");
            breakall= false;
            return false;
        }
    });
    if(breakall) {
        $(".detail_Table tbody:first").children("tr").children("td").css({"word-break":"break-all"});
    }

});