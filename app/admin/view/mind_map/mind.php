<link type="text/css" rel="stylesheet" href="__ADMIN_JS__/jsmind/style/jsmind.css" />
<style type="text/css">
    li{margin-top:2px; margin-bottom:2px;}
    button{width:110px;}
    select{width:120px;}
    #layout{width:1230px;}
    #jsmind_nav{width:110px;height:600px;border:solid 1px #ccc;overflow:auto;float:left;}
    .file_input{width:100px;}
    button.sub{width:100px;}

    #jsmind_container{
        float:left;
        width:1100px;
        height:600px;
        border:solid 1px #ccc;
        background:#f4f4f4;
    }
</style>
<div id="layout">
    <div id="jsmind_nav">
        <ol type='A'>
<!--            <li><button class="layui-btn" onclick="toggle_editable(this);">禁用编辑</button></li>-->
<!--            <li><button class="layui-btn" onclick="add_node();">添加节点</button></li>-->
<!--            <li><button class="layui-btn" onclick="add_image_node();">添加图片节点</button></li>-->
<!--            <li><button class="layui-btn" onclick="modify_node();">修改节点</button></li>-->
<!--            <li><button class="layui-btn" onclick="remove_node();">删除节点</button></li>-->
<!--            <li><button class="layui-btn" onclick="move_node();">移动节点</button></li>-->
<!--            <li><button class="layui-btn" onclick="move_to_first();">移到第一个</button></li>-->
<!--            <li><button class="layui-btn" onclick="move_to_last();">移到最后一个</button></li>-->
            <li><button class="layui-btn" onclick="toggle();">展开折叠节点</button></li>
            <li><button class="layui-btn" onclick="expand_all();">展开所有</button></li>
            <li><button class="layui-btn" onclick="collapse_all();">折叠所有</button></li>
            <li><button class="layui-btn" onclick="add_option();">添加内容</button></li>
            <li><button class="layui-btn" onclick="edit_option();">编辑内容</button></li>
            <li><button class="layui-btn" onclick="read_option();">查看内容</button></li>
        </ol>
    </div>
    <div id="jsmind_container"></div>
    <div style="display:none">
        <input class="file" type="file" id="image-chooser" accept="image/*"/>
    </div>
</div>
{include file="block/layui" /}
<script type="text/javascript" src="__ADMIN_JS__/jsmind/js/jsmind.js"></script>
<script type="text/javascript" src="__ADMIN_JS__/jsmind/js/jsmind.draggable.js"></script>

<script type="text/javascript">
    var _jm = null;
    function open_empty(){
        var options = {
            container:'jsmind_container',
            theme:'greensea',
            editable:true
        }
        _jm = jsMind.show(options);
        // _jm = jsMind.show(options,mind);
        open_ajax();
    }

    function open_json(){
        var mind = {
            "meta":{
                "name":"jsMind remote",
                "author":"ernest96@yeah.net",
                "version":"0.1"
            },
            "format":"node_array",
            "data":[
                {"id":"0", "isroot":true,"parentid":"0", "topic":"jsMind","direction":"right"},

                {"id":"easy", "parentid":"0", "topic":"Easy", "direction":"right"},
                {"id":"easy1", "parentid":"easy", "topic":"Easy to show"},
                {"id":"easy2", "parentid":"easy", "topic":"Easy to edit"},
                {"id":"easy3", "parentid":"easy", "topic":"Easy to store"},


                {"id":"open", "parentid":"0", "topic":"Open Source", "expanded":false, "direction":"right"},
                {"id":"open1", "parentid":"open", "topic":"on GitHub"},
                {"id":"open2", "parentid":"open", "topic":"BSD License"},

                {"id":"powerful", "parentid":"0", "topic":"Powerful", "direction":"right"},
                {"id":"powerful1", "parentid":"powerful", "topic":"Base on Javascript"},
                {"id":"powerful2", "parentid":"powerful", "topic":"Base on HTML5"},
                {"id":"powerful3", "parentid":"powerful", "topic":"Depends on you"},
                {"id":"easy4", "parentid":"easy", "topic":"Easy to embed"},
            ]
        }
        _jm.show(mind);
    }

    function open_ajax(){
        var mind_url = "{:url('MindMap/ajaxGetData')}?id="+{$Request.param.id};
        jsMind.util.ajax.get(mind_url,function(mind){
            _jm.show(mind);
        });
    }
    function screen_shot(){
        _jm.screenshot.shootDownload();
    }

    function show_data(){
        var mind_data = _jm.get_data();
        var mind_string = jsMind.util.json.json2string(mind_data);
        prompt_info(mind_string);
    }

    function save_file(){
        var mind_data = _jm.get_data();
        var mind_name = mind_data.meta.name;
        var mind_str = jsMind.util.json.json2string(mind_data);
        jsMind.util.file.save(mind_str,'text/jsmind',mind_name+'.jm');
    }

    function open_file(){
        var file_input = document.getElementById('file_input');
        var files = file_input.files;
        if(files.length > 0){
            var file_data = files[0];
            jsMind.util.file.read(file_data,function(jsmind_data, jsmind_name){
                var mind = jsMind.util.json.string2json(jsmind_data);
                if(!!mind){
                    _jm.show(mind);
                }else{
                    prompt_info('can not open this file as mindmap');
                }
            });
        }else{
            prompt_info('please choose a file first')
        }
    }

    function add_option() {
        var selected_node = _jm.get_selected_node();
        if(!selected_node){prompt_info('请先选择一个节点');return;}
        var open_url = "{:url('Project/add')}?id="+selected_node.id;
        window.location.href = open_url;
    }
    function edit_option() {
        var selected_node = _jm.get_selected_node();
        if(!selected_node){prompt_info('请先选择一个节点');return;}
        var open_url = "{:url('Project/edit')}?id="+selected_node.id;
        window.location.href = open_url;
    }
    function read_option() {
        var selected_node = _jm.get_selected_node();
        if(!selected_node){prompt_info('请先选择一个节点');return;}
        var open_url = "{:url('Project/read')}?id="+selected_node.id;
        window.location.href = open_url;
    }
    // function select_node(){
    //     var nodeid = 'other';
    //     _jm.select_node(nodeid);
    // }

    function show_selected(){
        var selected_node = _jm.get_selected_node();
        if(!!selected_node){
            prompt_info(selected_node.topic);
        }else{
            prompt_info('nothing');
        }
    }

    function get_selected_nodeid(){
        var selected_node = _jm.get_selected_node();
        if(!!selected_node){
            return selected_node.id;
        }else{
            return null;
        }
    }

    function add_node(){
        var selected_node = _jm.get_selected_node(); // as parent of new node
        if(!selected_node){prompt_info('请先选择一个节点');return;}

        var nodeid = jsMind.util.uuid.newid();
        var topic = '* Node_'+nodeid.substr(0,5)+' *';
        var node = _jm.add_node(selected_node, nodeid, topic);
    }

    var imageChooser = document.getElementById('image-chooser');

    imageChooser.addEventListener('change', function (event) {
        // Read file here.
        var reader = new FileReader();
        reader.onloadend = (function () {
            var selected_node = _jm.get_selected_node();
            var nodeid = jsMind.util.uuid.newid();
            var topic = undefined;
            var data = {
                "background-image": reader.result,
                "width": "100",
                "height": "100"};
            var node = _jm.add_node(selected_node, nodeid, topic, data);
            //var node = _jm.add_image_node(selected_node, nodeid, reader.result, 100, 100);
            //add_image_node:function(parent_node, nodeid, image, width, height, data, idx, direction, expanded){
        });

        var file = imageChooser.files[0];
        if (file) {
            reader.readAsDataURL(file);
        };

    }, false);

    function add_image_node(){
        var selected_node = _jm.get_selected_node(); // as parent of new node
        if(!selected_node){
            prompt_info('请先选择一个节点');
            return;
        }

        imageChooser.focus();
        imageChooser.click();
    }

    function modify_node(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        // modify the topic
        _jm.update_node(selected_id, '--- modified ---');
    }

    function move_to_first(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.move_node(selected_id,'_first_');
    }

    function move_to_last(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.move_node(selected_id,'_last_');
    }

    function move_node(){
        // move a node before another
        _jm.move_node('other','open');
    }

    function remove_node(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.remove_node(selected_id);
    }

    function change_text_font(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.set_node_font_style(selected_id, 28);
    }

    function change_text_color(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.set_node_color(selected_id, null, '#000');
    }

    function change_background_color(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.set_node_color(selected_id, '#eee', null);
    }

    function change_background_image(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.set_node_background_image(selected_id, 'ant.png', 100, 100);
    }

    function set_theme(theme_name){
        _jm.set_theme(theme_name);
    }

    var zoomInButton = document.getElementById("zoom-in-button");
    var zoomOutButton = document.getElementById("zoom-out-button");

    function zoomIn() {
        if (_jm.view.zoomIn()) {
            zoomOutButton.disabled = false;
        } else {
            zoomInButton.disabled = true;
        };
    };

    function zoomOut() {
        if (_jm.view.zoomOut()) {
            zoomInButton.disabled = false;
        } else {
            zoomOutButton.disabled = true;
        };
    };

    function toggle_editable(btn){
        var editable = _jm.get_editable();
        if(editable){
            _jm.disable_edit();
            btn.innerHTML = '启用编辑';
        }else{
            _jm.enable_edit();
            btn.innerHTML = '禁用编辑';
        }
    }

    // this method change size of container, perpare for adjusting jsmind
    function change_container(){
        var c = document.getElementById('jsmind_container');
        c.style.width = '800px';
        c.style.height = '500px';
    }

    function resize_jsmind(){
        _jm.resize();
    }

    function expand(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.expand_node(selected_id);
    }

    function collapse(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.collapse_node(selected_id);
    }

    function toggle(){
        var selected_id = get_selected_nodeid();
        if(!selected_id){prompt_info('请先选择一个节点');return;}

        _jm.toggle_node(selected_id);
    }

    function expand_all(){
        _jm.expand_all();
    }

    function expand_to_level2(){
        _jm.expand_to_depth(2);
    }

    function expand_to_level3(){
        _jm.expand_to_depth(3);
    }

    function collapse_all(){
        _jm.collapse_all();
    }


    function get_nodearray_data(){
        var mind_data = _jm.get_data('node_array');
        var mind_string = jsMind.util.json.json2string(mind_data);
        prompt_info(mind_string);
    }

    function save_nodearray_file(){
        var mind_data = _jm.get_data('node_array');
        var mind_name = mind_data.meta.name;
        var mind_str = jsMind.util.json.json2string(mind_data);
        jsMind.util.file.save(mind_str,'text/jsmind',mind_name+'.jm');
    }

    function open_nodearray(){
        var file_input = document.getElementById('file_input_nodearray');
        var files = file_input.files;
        if(files.length > 0){
            var file_data = files[0];
            jsMind.util.file.read(file_data,function(jsmind_data, jsmind_name){
                var mind = jsMind.util.json.string2json(jsmind_data);
                if(!!mind){
                    _jm.show(mind);
                }else{
                    prompt_info('can not open this file as mindmap');
                }
            });
        }else{
            prompt_info('please choose a file first')
        }
    }

    function get_freemind_data(){
        var mind_data = _jm.get_data('freemind');
        var mind_string = jsMind.util.json.json2string(mind_data);
        alert(mind_string);
    }

    function save_freemind_file(){
        var mind_data = _jm.get_data('freemind');
        var mind_name = mind_data.meta.name || 'freemind';
        var mind_str = mind_data.data;
        jsMind.util.file.save(mind_str,'text/xml',mind_name+'.mm');
    }

    function open_freemind(){
        var file_input = document.getElementById('file_input_freemind');
        var files = file_input.files;
        if(files.length > 0){
            var file_data = files[0];
            jsMind.util.file.read(file_data, function(freemind_data, freemind_name){
                if(freemind_data){
                    var mind_name = freemind_name;
                    if(/.*\.mm$/.test(mind_name)){
                        mind_name = freemind_name.substring(0,freemind_name.length-3);
                    }
                    var mind = {
                        "meta":{
                            "name":mind_name,
                            "author":"hizzgdev@163.com",
                            "version":"1.0.1"
                        },
                        "format":"freemind",
                        "data":freemind_data
                    };
                    _jm.show(mind);
                }else{
                    prompt_info('can not open this file as mindmap');
                }
            });
        }else{
            prompt_info('please choose a file first')
        }
    }

    function prompt_info(msg){
        alert(msg);
    }

    open_empty();
</script>