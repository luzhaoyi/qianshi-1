<?php
/*********************************************************
 * common file
 * 
 *
**********************************************************/


define('ROOT_DIR',      $_SERVER['DOCUMENT_ROOT']);
define('IMGS_DIR',      ROOT_DIR . '/img');
define('APP_DB_PREFIX', '201207quanshi');


require_once(ROOT_DIR . '/dev/devinc.bad.php');
require_once(ROOT_DIR . '/dev/devinc.user.php');
require_once(ROOT_DIR . '/dev/devinc.mdb2.php');
require_once(ROOT_DIR . '/dev/devinc.safe.php');
require_once(ROOT_DIR . '/dev/devinc.action.php');




//发表产品
function product_post(&$prod, &$user) {
    if (s_bad_array($user)
        || s_bad_array($prod)
    ) {
        return false;
    }


    $data = array();
    $data['uid']    = $user['uid'];
    $data['uname']  = $user['name'];
    $data['a50']    = $user['a50'];
    $data['purl']   = $user['purl'];


    $data['ip']     = s_action_ip();
    $data['source'] = s_action_source();

    $data['text']   = $ret['text'];
    $data['big']    = $ret['original_pic'];
    $data['small']  = $ret['thumbnail_pic'];

    $time = s_action_time();
    $data['time']   = $time;
    $data['fdate']  = date('Y-m-d', $time);
    $data['ftime']  = date('Y-m-d H:i:s', $time);


    return s_db('%s_product:insert', $data);
}


function image_info(&$path, $data=false) {
    if (s_bad_string($path)
        || false === ( $size = getimagesize($path) )
    ) {
        return false;
    }

    $image = array(
        'path'      => $path,
        'width'     => $size[0],
        'height'    => $size[1],
        'size'      => filesize($path),
    );

    if ($size['2'] === IMAGETYPE_JPEG) {
        //jpg / jpeg 图片格式
        $image['type'] = 'jpg';
        $image['data'] = $data === false ? false : imagecreatefromjpeg($path);

    } else if ($size['2'] === IMAGETYPE_GIF) {
        //gif 图片格式
        $image['type'] = 'gif';
        $image['data'] = $data === false ? false : imagecreatefromgif($path);

    } else if ($size['2'] === IMAGETYPE_PNG) {
        //png 图片格式
        $image['type'] = 'png';
        $image['data'] = $data === false ? false : imagecreatefrompng($path);
    }

    return $image;
}


//压缩图片，按比例压缩图片
function image_adjust(&$source, $target=false, $width=450, $height=300) {
    if (s_bad_gd()
        //获取路径对应的图片对象
        || false === ( $img = image_info($source, true) )
    ) {
        return false;
    }


    //检查源图片大小
    $s_w = imagesx($img['data']);
    $s_h = imagesy($img['data']);

    //先检查图片是否没有缩略图大
    $bigger = false;

    if ($img['width'] > $width
        || $img['height'] > $height
    ) {
        $bigger = true;
    }


    if ($bigger === false) {
        //没有缩略图大直接返回图片资源
        return $img['data'];
    }

    //新宽、新高
    $n_w = false;
    $n_h = false;

    //需要生成缩略图，按比例缩放
    if ($img['width'] > $img['height']) {
        //以宽为基准
        $ratio  = $width / $img['width'];
        $n_w    = floor($width);
        $n_h    = floor($img['height'] * $ratio);

    } else {
        //以高为基准
        $ratio  = $height / $img['height'];
        $n_w    = floor($img['width'] * $ratio);
        $n_h    = floor($height);
    }


    $new = imagecreatetruecolor($n_w, $n_h);
    if (!imagecopyresized($new, $img['data'], 0, 0, 0, 0, $n_w, $n_h, $img['width'], $img['height'])) {
        $new = false;
    }

    imagedestroy($img['data']);

    if ($target === false) {
        $target = '/tmp/' . md5($img['width'] . '|' . $img['height'] . '|' . $img['path'] . '|' . $img['size'] . '|' . s_action_time()) . '.' . $img['type'];
    }

    if ($img['type'] === 'jpg') {
        imagejpeg($new, $target);

    } else if ($img['type'] === 'png') {
        imagegif($new, $target);

    } else if ($img['type'] === 'png') {
        imagepng($new, $target);
    }


    return $target;
}
