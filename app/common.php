<?php

// 应用公共函数库
use think\exception\HttpException;

if (!function_exists('dblang')) {
    /**
     * 获取语言包ID，数据库读取时使用
     * @param string $group 分组[admin]，默认为前台
     * @return int
     */
    function dblang($group = '') {
        $lang = cookie($group.'_language');
        if (empty($lang)) {
            $lang = config('default_lang');
        }
        return model('AdminLanguage')->lists($lang);
    }
}

if (!function_exists('get_domain')) {
    /**
     * 获取当前域名
     * @param bool $http true 返回http协议头,false 只返回域名
     * @return string
     */
    function get_domain($http = true) {
        if ($http) {
            $port = '';
            if (input('server.server_port') != 80) {
                $port = ':'.input('server.server_port');
            }

            if (input('server.https') && input('server.https') == 'on') {
                return 'https://'.input('server.http_host').$port;
            }
            return 'http://'.input('server.http_host').$port;
        }
        return input('server.http_host');
    }
}

if (!function_exists('get_num')) {
    /**
     * 获取数值型
     * @param string $field 要获取的字段名
     * @return bool
     */
    function get_num($field = 'id') {
        $_id = input('param.' . $field. '/d', 0);
        if ($_id > 0) {
            return $_id;
        }

        if (request()->isAjax()) {
            json(['msg'=> '参数传递错误', 'code'=> 0]);
        } else {
            throw new HttpException(404, $field.'参数传递错误！');
        }
        exit;
    }
}

if (!function_exists('is_email')) {
    /**
     * 判断邮箱
     * @param string $str 要验证的邮箱地址
     * @return bool
     */
    function is_email($str) {
        return preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $str);
    }
}

if (!function_exists('is_mobile')) {
    /**
     * 判断手机号
     * @param string $num 要验证的手机号
     * @return bool
     */
    function is_mobile($num) {
        return preg_match("/^1(3|4|5|7|8)\d{9}$/", $num);
    }
}

if (!function_exists('cur_url')) {
    /**
     * 获取当前访问的完整URL
     * @return string
     */
    function cur_url() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === 'on') {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}

if (!function_exists('is_username')) {
    /**
     * 判断用户名
     * 用户名支持中文、字母、数字、下划线，但必须以中文或字母开头，长度3-20个字符
     * @param string $str 要验证的字符串
     * @return bool
     */
    function is_username($str) {
        return preg_match("/^[\x80-\xffA-Za-z]{1,1}[\x80-\xff_A-Za-z0-9]{2,19}+$/", $str);
    }
}

if (!function_exists('random')) {
    /**
     * 随机字符串
     * @param int $length 长度
     * @param int $type 类型(0：混合；1：纯数字)
     * @return string
     */
    function random($length = 16, $type = 1) {
         $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $type ? 10 : 35);
         $seed = $type ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
         if($type) {
              $hash = '';
         } else {
              $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
              $length--;
         }
         $max = strlen($seed) - 1;
         for($i = 0; $i < $length; $i++) {
              $hash .= $seed{mt_rand(0, $max)};
         }
         return $hash;
    }
}

if (!function_exists('order_number')) {
    /**
     * 生成订单号(年月日时分秒+5位随机数)
     * @return int
     */
    function order_number() {
        return date('YmdHis').random(5);
    }
}

if (!function_exists('hide_str')) {
    /**
     * 将一个字符串部分字符用*替代隐藏
     * @param string    $string   待转换的字符串
     * @param int       $bengin   起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int       $len      需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int       $type     转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串中间用***代替
     * @param string    $glue     分割符
     * @return string   处理后的字符串
     */
    function hide_str($string, $bengin=0, $len = 4, $type = 0, $glue = "@") {
        if (empty($string))
            return false;
        $array = array();
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }
        if ($type == 0) {
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i])) $array[$i] = "*";
            }
            $string = implode("", $array);
        }else if ($type == 1) {
            $array = array_reverse($array);
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i])) $array[$i] = "*";
            }
            $string = implode("", array_reverse($array));
        }else if ($type == 2) {
            $array = explode($glue, $string);
            if (isset($array[0])) {
                $array[0] = hide_str($array[0], $bengin, $len, 1);
            }
            $string = implode($glue, $array);
        } else if ($type == 3) {
            $array = explode($glue, $string);
            if (isset($array[1])) {
                $array[1] = hide_str($array[1], $bengin, $len, 0);
            }
            $string = implode($glue, $array);
        } else if ($type == 4) {
            $left = $bengin;
            $right = $len;
            $tem = array();
            for ($i = 0; $i < ($length - $right); $i++) {
                if (isset($array[$i])) $tem[] = $i >= $left ? "" : $array[$i];
            }
            $tem[] = '*****';
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; $i++) {
                if (isset($array[$i])) $tem[] = $array[$i];
            }
            $string = implode("", $tem);
        }
        return $string;
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * 获取客户端IP地址
     * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

if (!function_exists('parse_attr')) {
    /**
     * 配置值解析成数组
     * @param string $value 配置值
     * @return array|string
     */
    function parse_attr($value = '') {
        if (is_array($value)) return $value;
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        if (strpos($value, ':')) {
            $value  = array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k]   = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }
}

if (!function_exists('login')) {
    /**
     * 会员登录
     * @param string $account 账号
     * @param string $password 密码
     * @param bool $remember 记住登录 TODO
     * @param string $field 登陆之后缓存的字段
     * @param bool $token token验证
     * @return bool|array
     */
    function login($account = '', $password = '', $remember = false, $field = 'nick', $token = false)
    {
        return model('AdminMember')->login($account, $password, $remember, $field, $token);
    }
}

if (!function_exists('is_login')) {
    /**
     * 判断会员是否登录
     * @return bool|array
     */
    function is_login() {
        return model('AdminMember')->isLogin();
    }
}

if (!function_exists('logout')) {
    /**
     * 退出登陆
     * @return bool|array
     */
    function logout() {
        return model('AdminMember')->logout();
    }
}

if (!function_exists('xml2array')) {
    /**
     * XML转数组
     * @param string $xml xml格式内容
     * @param bool $isnormal 
     * @return array
     */
    function xml2array(&$xml, $isnormal = FALSE) {
        $xml_parser = new app\common\util\Xml($isnormal);
        $data = $xml_parser->parse($xml);
        $xml_parser->destruct();
        return $data;
    }
}

if (!function_exists('array2xml')) {
    /**
     * 数组转XML
     * @param array $arr 待转换的数组
     * @param bool $ignore XML解析器忽略
     * @param intval $level 层级
     * @return type
     */
    function array2xml($arr, $ignore = true, $level = 1) {
        $s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
        $space = str_repeat("\t", $level);
        foreach($arr as $k => $v) {
            if(!is_array($v)) {
                $s .= $space."<item id=\"$k\">".($ignore ? '<![CDATA[' : '').$v.($ignore ? ']]>' : '')."</item>\r\n";
            } else {
                $s .= $space."<item id=\"$k\">\r\n".array2xml($v, $ignore, $level + 1).$space."</item>\r\n";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s."</root>" : $s;
    }
}

if (!function_exists('form_type')) {
    /**
     * 获取表单类型(中文描述)
     * @param string $type 类型(英文)
     * @return array|string
     */
    function form_type($type = '') {
        $arr = [];
        $arr['input'] = '单行文本';
        $arr['textarea'] = '多行文本';
        $arr['array'] = '数组';
        $arr['switch'] = '开关';
        $arr['radio'] = '单选按钮';
        $arr['checkbox'] = '多选框';
        $arr['tags'] = '标签';
        $arr['select'] = '下拉框';
        $arr['hidden'] = '隐藏';
        $arr['image'] = '图片';
        $arr['file'] = '文件';
        $arr['date'] = '日期';
        $arr['datetime'] = '日期+时间';
        $arr['time'] = '时间';
        if (isset($arr[$type])) {
            return $arr[$type];
        }
        return $arr;
    }
}

if (!function_exists('json_indent')) {
    /**
     * JSON数据美化
     * @param string $json json字符串
     * @return string
     */
    function json_indent($json) { 
        $result = ''; 
        $pos = 0; 
        $strLen = strlen($json); 
        $indentStr = '  '; 
        $newLine = "\n"; 
        $prevChar = ''; 
        $outOfQuotes = true; 
        for ($i=0; $i<=$strLen; $i++) { 
            $char = substr($json, $i, 1);
            if ($char == '"' && $prevChar != '\\') { 
                $outOfQuotes = !$outOfQuotes;
            } else if(($char == '}' || $char == ']') && $outOfQuotes) { 
                $result .= $newLine; 
                $pos --; 
                for ($j=0; $j<$pos; $j++) { 
                    $result .= $indentStr; 
                } 
            }
            $result .= $char;
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) { 
                $result .= $newLine; 
                if ($char == '{' || $char == '[') { 
                    $pos ++; 
                } 
                for ($j = 0; $j < $pos; $j++) { 
                    $result .= $indentStr; 
                } 
            } 
            $prevChar = $char; 
        } 
        return $result; 
    }
}

if (!function_exists('parse_sql')) {
    /**
     * 解析sql语句
     * @param  string $content sql内容
     * @param  int $limit  如果为1，则只返回一条sql语句，默认返回所有
     * @param  array $prefix 替换表前缀
     * @return array|string 除去注释之后的sql语句数组或一条语句
     */
    function parse_sql($sql = '', $limit = 0, $prefix = []) {
        // 被替换的前缀
        $from = '';
        // 要替换的前缀
        $to = '';

        // 替换表前缀
        if (!empty($prefix)) {
            $to   = current($prefix);
            $from = current(array_flip($prefix));
        }

        if ($sql != '') {
            // 纯sql内容
            $pure_sql = [];

            // 多行注释标记
            $comment = false;

            // 按行分割，兼容多个平台
            $sql = str_replace(["\r\n", "\r"], "\n", $sql);
            $sql = explode("\n", trim($sql));

            // 循环处理每一行
            foreach ($sql as $key => $line) {
                // 跳过空行
                if ($line == '') {
                    continue;
                }

                // 跳过以#或者--开头的单行注释
                if (preg_match("/^(#|--)/", $line)) {
                    continue;
                }

                // 跳过以/**/包裹起来的单行注释
                if (preg_match("/^\/\*(.*?)\*\//", $line)) {
                    continue;
                }

                // 多行注释开始
                if (substr($line, 0, 2) == '/*') {
                    $comment = true;
                    continue;
                }

                // 多行注释结束
                if (substr($line, -2) == '*/') {
                    $comment = false;
                    continue;
                }

                // 多行注释没有结束，继续跳过
                if ($comment) {
                    continue;
                }

                // 替换表前缀
                if ($from != '') {
                    $line = str_replace('`'.$from, '`'.$to, $line);
                }
                if ($line == 'BEGIN;' || $line =='COMMIT;') {
                    continue;
                }
                // sql语句
                array_push($pure_sql, $line);
            }

            // 只返回一条语句
            if ($limit == 1) {
                return implode($pure_sql, "");
            }

            // 以数组形式返回sql语句
            $pure_sql = implode($pure_sql, "\n");
            $pure_sql = explode(";\n", $pure_sql);
            return $pure_sql;
        } else {
            return $limit == 1 ? '' : [];
        }
    }
}

if (!function_exists('editor')) {
    /**
     * 富文本编辑器
     * @param array $obj 编辑器的容器id或class
     * @param string $name [为了方便大家能在系统设置里面灵活选择编辑器，建议不要指定此参数]，目前支持的编辑器[ueditor,umeditor,ckeditor,kindeditor]
     * @param string $url [选填]附件上传地址，建议保持默认
     * @return html
     */
    function editor($obj = [], $name = '', $url = '') {
        $js_path = config('view_replace_str.__PUBLIC_JS__').'/editor/';
        if (empty($name)) {
            $name = config('sys.editor');
        }

        if (empty($url)){
            $url = url("admin/UploadFile/upload?thumb=no&from=".$name);
        }

        switch (strtolower($name)) {
            case 'ueditor':
                $html = '<script src="'.$js_path.'ueditor/ueditor.config.js"></script>';
                $html .= '<script src="'.$js_path.'ueditor/ueditor.all.min.js"></script>';
                $html .= '<script>';
                foreach ($obj as $k =>$v) {
                    $html .= 'var ue'.$k.' = UE.ui.Editor({serverUrl:"'.$url.'",initialFrameHeight:300,initialFrameWidth:"100%"});ue'.$k.'.render("'.$v.'");';
                }
                $html .= '</script>';
                break;
            case 'kindeditor':
                if (is_array($obj)) {
                    $obj = implode(',#', $obj);
                }
                $html = '<script src="'.$js_path.'kindeditor/kindeditor-min.js"></script>
                        <script>
                            var editor;
                            KindEditor.ready(function(K) {
                                editor = K.create(\'#'.$obj.'\', {uploadJson: "'.$url.'",allowFileManager : false,minHeight:300, width:"100%", afterBlur:function(){this.sync();}});
                            });
                        </script>';
                break;
            case 'ckeditor':
                $html = '<script src="'.$js_path.'ckeditor/ckeditor.js"></script>';
                $html .= '<script>';
                foreach ($obj as  $v) {
                    $html .= 'CKEDITOR.replace("'.$v.'",{filebrowserImageUploadUrl:"'.$url.'"});';
                }
                $html .= '</script>';
                break;
            
            default:
                $html = '<link href="'.$js_path.'umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">';
                $html .= '<script src="'.$js_path.'umeditor/third-party/jquery.min.js"></script>';
                $html .= '<script src="'.$js_path.'umeditor/third-party/template.min.js"></script>';
                $html .= '<script src="'.$js_path.'umeditor/umeditor.config.js"></script>';
                $html .= '<script src="'.$js_path.'umeditor/umeditor.min.js"></script>';
                $html .= '<script>';
                foreach ($obj as  $k => $v) {
                    $html .= 'var um'.$k.' = UM.getEditor("'.$v.'", {
                                initialFrameWidth:"100%"
                                ,initialFrameHeight:"300"
                                ,imageUrl:"'.$url.'"
                                ,imageFieldName:"upfile"});';
                }
                $html .= '</script>';
                break;
        }

        return $html;
    }
}

if (!function_exists('str_coding')) {
    /**
     * 字符串加解密
     * @param  string  $string   要加解密的原始字符串
     * @param  string  $operation 加密：ENCODE，解密：DECODE
     * @param  string  $key      密钥
     * @param  integer $expiry   有效期
     * @return string
     */
    function str_coding($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $key = md5($key ? $key : config('tb_auth.key'));
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
}

if (!function_exists('is_empty')) {
    /**
     * 判断是否为空值
     * @param array|string $value 要判断的值 
     * @return bool
     */
    function is_empty($value) {
        if (!isset($value)){
            return true;
        }
        if ($value === null){
            return true;
        }
        if (trim($value) === ''){
            return true;
        }
        return false;
    }
}

if (!function_exists('module_info')) {
    /**
     * 获取模块信息[非系统模块]
     * @param string $name 模块名
     * @return bool|array
     */
    function module_info($name = '')
    {
        if (is_empty($name)) {
            $name = request()->module();
        }

        $path = APP_PATH.strtolower($name).DS.'info.php';
        if (!file_exists($path)) {
            return false;
        }

        return include_once $path;
    }
}

// +----------------------------------------------------------------------
// | 插件相关函数 start
// +----------------------------------------------------------------------

if (!function_exists('runhook')) {
    /**
     * 监听钩子的行为
     * @param string $name 钩子名称
     * @param array $params 参数
     */
    function runhook($name = '', $params = []) {
        \think\Hook::listen($name, $params);
    }
}

if (!function_exists('get_plugins_class')) {
    /**
     * 获取插件类名
     * @param  string $name 插件名
     * @return string
     */
    function get_plugins_class($name)
    {
        return "plugins\\{$name}\\{$name}";
    }
}

if (!function_exists('plugins_action_exist')) {
    /**
     * 检查插件操作是否存在
     * @param string $path 插件操作路径：插件名/控制器/[操作]
     * @param string $group 控制器分组[admin,home]
     * @return bool
     */
    function plugins_action_exist($path = '', $group = 'admin')
    {
        if (strpos($path, '/')) {
            list($name, $controller, $action) = explode('/', $path);
        }
        $controller = empty($controller) ? 'Index' : ucfirst($controller);
        $action = empty($action) ? 'index' : $action;

        return method_exists("plugins\\{$name}\\{$group}\\{$controller}", $action);
    }
}

if (!function_exists('plugins_run')) {
    /**
     * 运行插件操作
     * @param string $path  执行操作路径：插件名/控制器/[操作]
     * @param mixed $params 参数
     * @param string $group 控制器分组[admin,home]
     * @return mixed
     */
    function plugins_run($path = '', $params = [], $group = 'admin')
    {
        if (strpos($path, '/')) {
            list($name, $controller, $action) = explode('/', $path);
        } else {
            $name = $path;
        }
        $controller = empty($controller) ? 'index' : ucfirst($controller);
        $action = empty($action) ? 'index' : $action;
        if (!is_array($params)) {
            $params = (array)$params;
        }
        define('IS_PLUGINS', true);
        $class = "plugins\\{$name}\\{$group}\\{$controller}";
        $obj = new $class;
        return call_user_func_array([$obj, $action], [$params]);
    }
}

if (!function_exists('plugins_info')) {
    /**
     * 获取插件信息
     * @param string $name 插件名
     * @return bool
     */
    function plugins_info($name = '')
    {
        $path = ROOT_PATH.'plugins/'.$name.'/info.php';
        if (!file_exists($path)) {
            return false;
        }
        $info = include_once $path;
        return $info;
    }
}

if (!function_exists('plugins_url')) {
    /**
     * 生成插件URL
     * @param string $url 链接：插件名称/控制器/操作
     * @param array $param 参数
     * @param string $group 控制器分组[admin,home]
     * @param integer $urlmode URL模式
     * URL模式1 [/plugins/插件名/控制器/[方法]?参数1=参数值&参数2=参数值]
     * URL模式2 [/plugins.php?_p=插件名&_c=控制器&_a=方法&参数1=参数值&参数2=参数值] 推荐
     * @return string
     */
    function plugins_url($url = '', $param = [], $group = '', $urlmode = 2)
    {
        $params = [];
        $params['_p'] = input('get._p');
        $params['_c'] = input('get._c', 'Index');
        $params['_a'] = input('get._a', 'index');
        if ($url) {
            $url = explode('/', $url);
            $params['_p'] = isset($url[0]) ? $url[0] : '';
            $params['_c'] =  isset($url[1]) ? ucfirst($url[1]) : 'Index';
            $params['_a'] = isset($url[2]) ? $url[2] : 'index';
        }
        if (!$params['_p']) {
            return '#链接错误';
        }

        $params = array_merge($params, $param);
        if (empty($group)) {
            if (defined('ENTRANCE')) {
                return url('admin/plugins/run', $params);
            } else {
                if ($urlmode == 2) {
                    return ROOT_DIR.'plugins.php?'.http_build_query($params);
                }
                return ROOT_DIR.'plugins/'.$params['_p'].'/'.$params['_c'].'/'.$params['_a'].'?'.http_build_query($param);
            }
        } elseif ($group == 'admin') {
            return url('admin/plugins/run', $params);
        } else {
            if ($urlmode == 2) {
                return ROOT_DIR.'plugins.php?'.http_build_query($params);
            }
            return ROOT_DIR.'plugins/'.$params['_p'].'/'.$params['_c'].'/'.$params['_a'].'?'.http_build_query($param);
        }
    }
}
/**
 * @param $start_time
 * @param $end_time
 * @param string $type
 * @return float
 * 计算2个日期时间之差
 */
function time_diff($start_time,$end_time,$type='day'){
    switch ($type){
        case 'day':
            $time = floor((strtotime($end_time)-strtotime($start_time))/86400);
            break;
        case 'hour':
            $time = floor((strtotime($end_time)-strtotime($start_time))%86400/3600);
            break;
        case 'minute':
            $time = floor((strtotime($end_time)-strtotime($start_time))%86400/60);
            break;
        case 'second':
            $time = floor((strtotime($end_time)-strtotime($start_time))%86400%60);
            break;
        default:
            $time = floor((strtotime($end_time)-strtotime($start_time))/86400);
            break;
    }
    return $time;
}

/**
 * @param $start_time
 * @param $end_time
 * @param string $time
 * @return float
 * 计算计划完成时间百分比
 */
function time_per($start_time,$end_time,$time = ''){
    if (empty($time)){
        $time = date('Y-m-d');
    }
    $day1 = time_diff($start_time,$end_time,'day');
    $day2 = time_diff($start_time,$time,'day');
    $per = 100;
    if ($day1 > 0){
        $per = round($day2/$day1*100,2);
    }
    return $per;

}

/**
 * @param $val 表单添加的
 * @param $old_val 数据库原来的，编辑时候要传值
 * @param string $s1  explode分割使用的符号
 * @param string $s2  trim要去掉的符号
 * @return array|null
 * 专为分配人员使用
 */
function user_array($val, $old_val = '', $s1 = ',', $s2 = ',')
{
    $data = explode($s1, trim($val, $s2));
    if (!empty($data[0])) {
        $data = array_flip($data);
        foreach ($data as $k => $v) {
            $data[$k] = '';
        }
        //新值与数据库中值合并
        if (!empty($old_val)) {
            $old_val = json_decode($old_val, true);
            $new = $old_val + $data;//数字索引相加合并数组，注意顺序，保证老值不被替换
            return json_encode($new,JSON_FORCE_OBJECT);
        }
        return json_encode($data,JSON_FORCE_OBJECT);
    }
    return json_encode([],JSON_FORCE_OBJECT);
}

function user_array1($val, $old_val = '', $s1 = ',', $s2 = ',')
{
    $data = explode($s1, trim($val, $s2));
    if (!empty($data[0])) {
        $data = array_flip($data);
        foreach ($data as $k => $v) {
            $data[$k] = 'a';
        }
        //新值与数据库中值合并
        if (!empty($old_val)) {
            $old_val = json_decode($old_val, true);
            $new = $old_val + $data;//数字索引相加合并数组，注意顺序，保证老值不被替换
            return json_encode($new,JSON_FORCE_OBJECT);
        }
        return json_encode($data,JSON_FORCE_OBJECT);
    }
    return json_encode([],JSON_FORCE_OBJECT);
}

function user_array2($data)
{
//    $data = json_decode($val,true);
//    print_r($data);exit();
    if (!empty($data[0])) {
        foreach ($data as $k => $v) {
            foreach ($v as $kk => $vv){
                $data[$k][$kk] = '';
            }
        }
        //新值与数据库中值合并
//        if (!empty($old_val)) {
//            $old_val = json_decode($old_val, true);
//            $new = $old_val + $data;//数字索引相加合并数组，注意顺序，保证老值不被替换
//            return json_encode($new,JSON_FORCE_OBJECT);
//        }
        return json_encode($data,JSON_FORCE_OBJECT);
    }
    return json_encode([],JSON_FORCE_OBJECT);
}

/**
 * @param $data
 * @return float|int
 * 日志或者汇报超过多少行给与鼓励分值
 */
function sumLineScore($data){
    $data = (array)$data;
    $total = 0;
    $score = config('config_score');
    $num = count(array_filter($data));
    if ($num > $score['rows']){
        $total += $score['gl']['daily_line'] * ($num - $score['rows']);
    }
    return $total;
}

/**
 * @param string $name 服务名称
 * @param int $forceMaster 1:强制连主库  2强制连从库 3根据配置
 * @return mixed
 * 构建基础服务方法
 */
function service($name='',$forceMaster = 3){
    static $model = array();
    if(!isset($model[$name])){
        if($name){
            $class = "\\app\\common\\service\\".$name.'Service';
            $model[$name]      =   class_exists($class) ? new $class($name) : new \app\common\service\Service();
        }else{
            $model[$name]      =   new \app\common\service\Service();
        }
    }
    $model[$name]->forceMaster = $forceMaster;
    return $model[$name];
}

function  curlInfo($toUrl,$urlParams){
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $toUrl );//目标网址
    curl_setopt($ch, CURLOPT_POST,1);  //post方式传递
    curl_setopt($ch, CURLOPT_POSTFIELDS,$urlParams);
    // curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    // curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    // curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    // curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    // curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    // curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    return $content;
}

/**
 * @param $a
 * @return bool验证大类专业填写规则是否符合要求
 */
function big_major_match($a){
    $r = "/^[\x{4e00}-\x{9fa5}A-Za-z]+[：]{1}[\d]+$/u";
    if (preg_match($r,$a)){
        return true;
    }
    return false;
}

/**
 * @param $a
 * @return bool验证小类专业填写规则是否符合要求
 */
function small_major_match($a){
    $re = "/[\x{4e00}-\x{9fa5}A-Za-z]+[：]{1}[\d]+$/u";
    $re1 = "/^[\x{4e00}-\x{9fa5}A-Za-z][\x{4e00}-\x{9fa5}A-Za-z：\d，]+[\d]+$/u";
    if (!preg_match($re,$a)){
        return false;
    }
    $d = explode('，',$a);
    if ($d){
        foreach ($d as $k=>$v){
            if (!preg_match($re1,$v)){
                return false;
            }
        }
    }
    return true;
}

function get_ip()
{
    if (isset($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], "unknown")) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], "unknown")) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else if (isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = "";
    }
    return ($ip);
}

//2. 在生成的二维码中加上logo(生成图片文件)
function scerweima1($url = '')
{
    vendor ( 'phpqrcode.phpqrcode' );
    $value = $url;//二维码内容
    $errorCorrectionLevel = 'H';//容错级别
    $matrixPointSize = 6;//生成图片大小
//生成二维码图片
    $filename = 'qrcode/' . microtime() . '.png';
    \QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    $logo = 'qrcode/favicon.jpg'; //准备好的logo图片
    $QR = $filename; //已经生成的原始二维码图
    if (file_exists($logo)) {
        $QR = imagecreatefromstring(file_get_contents($QR)); //目标图象连接资源。
        $logo = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
        $QR_width = imagesx($QR); //二维码图片宽度
        $QR_height = imagesy($QR); //二维码图片高度
        $logo_width = imagesx($logo); //logo图片宽度
        $logo_height = imagesy($logo); //logo图片高度
        $logo_qr_width = $QR_width / 4; //组合之后logo的宽度(占二维码的1/5)
        $scale = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
        $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
        $from_width = ($QR_width - $logo_qr_width) / 2; //组合之后logo左上角所在坐标点
//重新组合图片并调整大小
        /*
             * imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    }
//输出图片
    imagepng($QR, $filename);
    imagedestroy($QR);
    imagedestroy($logo);
    return $filename;
}

/**
 * 字节格式化 把字节数格式为B K M G T P E Z Y 描述的大小
 * @param int $size 大小
 * @param int $dec 显示类型
 * @return int
 */
function byte_format($size, $dec = 2)
{
    $a = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, $dec) . " " . $a[$pos];
}

/**
 * 取得单个磁盘信息
 * @param $letter
 * @return array
 */
function get_disk_space($letter)
{
    //获取磁盘信息
    $diskct = 0;
    $disk = array();
    /*if(@disk_total_space($key)!=NULL) *为防止影响服务器，不检查软驱
    {
     $diskct=1;
     $disk["A"]=round((@disk_free_space($key)/(1024*1024*1024)),2)."G / ".round((@disk_total_space($key)/(1024*1024*1024)),2).'G';
    }*/
    $diskz = 0; //磁盘总容量
    $diskk = 0; //磁盘剩余容量
    $is_disk = $letter . ':';
    if (@disk_total_space($is_disk) != NULL) {
        $diskct++;
        $disk[$letter][0] = byte_format(@disk_free_space($is_disk));
        $disk[$letter][1] = byte_format(@disk_total_space($is_disk));
        $disk[$letter][2] = round(((@disk_free_space($is_disk) / (1024 * 1024 * 1024)) / (@disk_total_space($is_disk) / (1024 * 1024 * 1024))) * 100, 2) . '%';
        $diskk += byte_format(@disk_free_space($is_disk));
        $diskz += byte_format(@disk_total_space($is_disk));
    }
    return $disk;
}

/**
 * 取得磁盘使用情况
 * @return var
 */
function get_spec_disk($type = 'system')
{
    $disk = array();
    switch ($type) {
        case 'system':
            //strrev(array_pop(explode(':',strrev(getenv_info('SystemRoot')))));//取得系统盘符
            $disk = get_disk_space(strrev(array_pop(explode(':', strrev(getenv('SystemRoot'))))));
            break;
        case 'all':
            foreach (range('b', 'z') as $letter) {
                $disk = array_merge($disk, get_disk_space($letter));
            }
            break;
        default:
            $disk = get_disk_space($type);
            break;
    }
    return $disk;
}