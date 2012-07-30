<?php

function s_action_error($message="no params.", $code=99, $type="json") {
    $error = array(
        'error'     => $code,
        'errmsg'    => $message,
    );

    //if ($type === "josn") {
        s_action_json($error);

    //} else if ($type === 'xml') {
        //s_action_xml($error);
    //}
}


function s_action_user($update=true, $checkref=true) {
    return true;
}


//当前请求的时间
function s_action_time() {
    return $_SERVER["REQUEST_TIME"];
}



function s_action_json($data) {
    if ($data === false) {
        //多半是直接函数调用后返回的false
        $data = array(
            'error'     => 100,
            'errmsg'    => '参数错误',
        );
    }


    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT'); 
    header('Cache-Control: no-cache, must-revalidate'); 
    header('Pragma: no-cache');
    header('content-type: application/json; charset=utf-8');

    echo json_encode($data);
}


//返回用户的IP地址
function s_action_ip() {
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        //客户IP地址
        return $_SERVER['HTTP_CLIENT_IP'];

    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //经过代理服务器的IP地址列表
        return $_SERVER['HTTP_X_FORWARDED_FOR'];

    } else if(isset($_SERVER['REMOTE_ADDR'])) {
        //可能是代理服务器的最后一个IP地址
        return $_SERVER['REMOTE_ADDR'];
    } else {
        //没有了，返回默认的IPV4
        return '000.000.000.000';
    }
}


//返回http的referer
function s_action_referer() {
    return $_SERVER['referer'];
}


//重定向
function s_action_redirect($url, $delay=0, $msg=false) {
    if (s_bad_string($url)) {
        $url = defined('APP_NAME') ? '/' . APP_NAME : '';
    }

    if (s_bad_ajax()) {
        if ($delay !== 0) {
            //需要提示，输出页面

            return ;
        }


        //非ajax请求，又没有提示语句，直接302
        if (is_string($msg)) {
            $url .= $msg;
        }

        header("Location: {$url}");

        return ;
    }

    return s_action_json(array('error' => 1, 'redirect' => $url));
}

