<?php
////////////////////////////////////////////////////////////////////////////////
//
// devinc.bad.php
//	判断错误的函数，参数错误返回true，正确返回false
//
//  s_bad_id($id)
//	    判断数字是否正确（大于0）
//  
//  s_bad_0id($id)
//	    判断数字是否正确（等于0也可以）
//  
//  s_bad_string($string)
//	    判断字符串是否正确
//
//  s_bad_array($string, &$var)
//	    判断数组否是正确，如果正确赋值给$var变量
//
//  s_bad_email($email, $var)
//	    判断邮箱地址是否正确
//
//  s_bad_post($key, $var=false, $type="string")
//      判断POST中的$key是否正确
//          $type为: string, int, int0, array, email, phone(包含mobile和telphone), mobile, telphone, image
//
//  s_bad_get($key, $var=false, $method="string")
//    判断GET中的$key是否正确
//          $type为: string, int, int0, array, email, phone(包含mobile和telphone), mobile, telphone, image
//
//
////////////////////////////////////////////////////////////////////////////////


function s_bad_id(&$id, &$var=false) {
    if(!is_numeric($id)
        || ( $id = intval($id) ) <= 0
    ) {
        return true;
    }

    if ($var !== false) {
        $var = $id;
    }

    return false;
}


function s_bad_0id(&$id, &$var=false) {
    if(!is_numeric($id)
        || ( $id = intval($id) ) < 0
    ) {
        return true;
    }

    if ($var !== false) {
        $var = $id;
    }

    return false;
}


function s_bad_string(&$str, &$var=false, $trim=true) {
    if (!is_string($str)
        || $str !== strval($str)
        || trim($str) === ""
    ) {
        return true;
    }

    if ($var !== false) {
        $var = strval($str);
    }

    if ($trim) {
        $var = trim($var);
    }

    return false;
}


function s_bad_0string(&$str, &$var=false) {
    if (!is_string($str)
        || $str !== strval($str)
    ) {
        return true;
    }

    if ($var !== false) {
        $var = $str;
    }

    return false;
}

function s_bad_array(&$arr, &$var=false) {
    if (!is_array($arr)
        || empty($arr)
    ) {
        return true;
    }

    if ($var !== false) {
        $var = $arr;
    }

    return false;
}


function s_bad_email(&$email, &$var=false) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return true;
    }

    if ($var !== false) {
        $var = $email;
    }

    return false;
}


function s_bad_mobile($mobile, &$var=false) {
    if (!preg_match("/^1(3|4|5|6|7|8|9)\d{9}$/", $mobile)) {
        return true;
    }

    if ($var !== false) {
        $var = $mobile;
    }

    return false;
}


function s_bad_telphone($phone, &$var=false) {
    if (s_bad_string($phone)
        || !preg_match("/^(\d+\-)*\d+$/", $phone)
    ) {
        return true;
    }

    if ($var !== false) {
        $var = $phone;
    }

    return false;
}


//返回post值
function s_bad_post($key, &$var=false, $type="string", $html=true) {
    if (s_bad_string($key)) {
        return true;
    }


    if ($type === "string") {
        //字符类型
        if ($html !== true) {
            //不需要转义，直接返回判断结果
            return s_bad_string($_POST[$key], $var);
        }

        //需要对参数转义处理
        if (true === s_bad_string($_POST[$key], $var)) {
            //不需要转义，因为参数已经验证失败
            return true;
        }

        if ($var !== false) {
            $var = s_safe_html($var);
        }

        //验证成功，此处返回
        return false;

    } else if ($type === "int") {
        //整型
        return s_bad_id($_POST[$key], $var);

    } else if ($type === "int0") {
        //整型，可以为0
        return s_bad_0id($_POST[$key], $var);

    } else if ($type === "array") {
        //数组
        return s_bad_array($_POST[$key], $var);

    } else if ($type === "email") {
        //邮箱
        return s_bad_email($_POST[$key], $var);

    } else if ($type === "phone"
        || $type === "telphone"
    ) {
        //手机或电话（只需要验证telphone，因为telphone的规则很松已经包含手机了）
        return s_bad_telphone($_POST[$key], $var);

    } else if ($type === "mobile") {
        //手机
        return s_bad_mobile($_POST[$key], $var);

    } else if ($type === "image") {
        //图片（只取request.data中的数据）
        if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])
            || !($GLOBALS["HTTP_RAW_POST_DATA"])
        ) {
            return true;
        }

        if ($var !== false) {
            $var = $GLOBALS["HTTP_RAW_POST_DATA"];
        }

        return false;

    } else if ($type === 'upload') {
        //获取上传的图片
        if (!isset($_FILES['file'])) {
            return true;
        }

        if ($var !== false) {
            $var = $_FILES['file'];
        }

        return false;
    }

    return true;
}


//返回get值
function s_bad_get($key, &$var=false, $type="string", $html=true) {
    if (s_bad_string($key)
        || !isset($_GET[$key])
    ) {
        return true;
    }


    if ($type === "string") {
        //字符类型
        if ($html !== true) {
            //不需要转义，直接返回判断结果
            return s_bad_string($_GET[$key], $var);
        }

        //需要对参数转义处理
        if (true === s_bad_string($_GET[$key], $var)) {
            //不需要转义，因为参数已经验证失败
            return true;
        }

        if ($var !== false) {
            $var = s_safe_html($var);
        }

        //验证成功，此处返回
        return false;

    } else if ($type === "int") {
        //整型
        return s_bad_id($_GET[$key], $var);

    } else if ($type === "int0") {
        return s_bad_0id($_GET[$key], $var);

    } else if ($type === "email") {
        //邮箱
        return s_bad_email($_GET[$key], $var);

    } else if ($type === "phone"
        || $type === "telphone"
    ) {
        //手机或电话（只需要验证telphone，因为telphone的规则很松已经包含手机了）
        return s_bad_telphone($_GET[$key], $var);

    } else if ($type === "mobile") {
        //手机
        return s_bad_mobile($_GET[$key], $var);
    }

    return true;
}

function s_bad_upload($key, &$var=false, $size='2m', $types=array('jpg', 'gif', 'png')) {
    if (!is_string($key)) {
        return true;
    }

    if (!isset($_FILES[$key])
        || !( $file = $_FILES[$key] )
        || !( is_uploaded_file($file['tmp_name']))
        || $file['error'] > 0
    ) {
        return true;
    }


    //上传的文件大小是否符合要求
    $unit = is_string($size) ? strtolower(substr($size, -1)) : 'k';

    switch ($unit) {
        case 'k': $size = intval($size) < 10;  break;   // $size * 1024
        case 'm': $size = intval($size) < 20;  break;   // $size * 1024 * 1024
        default : $size = intval($size) < 20;  break;   // $size * 1024 * 1024
    }

    if (filesize($file['tmp_name']) > $size) {
        //不用file['size']，因为此值偏小
        return true;
    }


    //上传的文件格式是否符合要求
    if ($types) {
        //不用$_FILES提供的type属性（text/x-markdown, application/octet-stream, text/html）
        //用最原始的判断方法，取文件名后缀判断
        if (false === ( $pos = strrpos($file['name'], '.') )
            || false === ( $type = substr($file['name'], $pos + 1) )
            || false === in_array($type, $types)
        ) {
            return true;
        }
    }

    if ($var !== false) {
        $var = $file;
    }

    return false;
}


function s_bad_gd() {
    return extension_loaded('gd') === false;
}


//非ajax请求，由于采用jquery，判断头部即可
function s_bad_ajax() {
    return !isset($_SERVER['X-Requested-With']) || isset($_SERVER['X-Requested-With']) !== 'XMLHttpRequest';
}
