{include file="block/layui" /}
<div class="layui-tab-item layui-form menu-dl {if condition="$k eq 1"}layui-show{/if}">
<style type="text/css">
    .progress-wrapper {
        background: white;
        width: 100%;
        height: 18px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .progress {
        height: 100%;
        width: 60%;
        position: absolute;
        left: 0px;
        top: 0px;
        background: #63ed63;
    }

    .progress-label {
        position: absolute;
        z-index: 1;
    }

</style>
<link href="__ADMIN_JS__/vis/vis.min.css" rel="stylesheet" type="text/css"/>
<script src="__ADMIN_JS__/vis/vis.min.js"></script>
<div id="myTimeline"></div>

<script type="text/javascript">
    // DOM element where the Timeline will be attached
    var container = document.getElementById('myTimeline');

    // Create a DataSet (allows two way data-binding)
    var items = new vis.DataSet([
        {id: 1, value: 0.2, content: 'item 1', start: '2018-04-20', end: '2018-04-26'},
        {id: 2, value: 0.65, content: 'item 2', start: '2018-05-14', end: '2018-05-18'},
        {id: 3, value: 0.65, content: 'item 3', start: '2018-04-15', end: '2018-08-18'},
        {id: 4,value: 0.65, content: 'item 4 with visibleFrameTemplate in item', start: '2018-04-16', end: '2018-04-26'},
        {id: 5, value: 0.2, content: 'item 1', start: '2018-04-20', end: '2018-04-26'},
        {id: 6, value: 0.65, content: 'item 2', start: '2018-05-14', end: '2018-05-18'},
        {id: 7, value: 0.65, content: 'item 3', start: '2018-04-15', end: '2018-08-18'},
        {id: 8, value: 0.2, content: 'item 1', start: '2018-04-20', end: '2018-04-26'},
        {id: 9, value: 0.65, content: 'item 2', start: '2018-05-14', end: '2018-05-18'},
        {id: 10, value: 0.65, content: 'item 3', start: '2018-04-15', end: '2018-08-18'},
    ]);

    // Configuration for the Timeline
    var options = {
        visibleFrameTemplate: function(item) {
            if (item.visibleFrameTemplate) {
                return item.visibleFrameTemplate;
            }
            var percentage = item.value * 100 + '%';
            return '<div class="progress-wrapper"><div class="progress" style="width:' + percentage + '"></div><label class="progress-label">' + percentage + '<label></div>';
        }
    };

    // Create a Timeline
    var timeline = new vis.Timeline(container, items, options);
</script>
</div>
