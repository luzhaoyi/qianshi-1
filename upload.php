<?php

////////////////////////////////////////////////////////////////////////////////
// 发表新产品
//      对图片做检查，按比例压缩

require_once("dev/devinc.common.php");

if (s_bad_post('name', $name)) {
    //产品名称
    $name = false;
}

if (s_bad_post('intro', $intro)) {
    //产品简介
    $intor = false;
}

if (s_bad_post('desc', $desc)) {
    //产品介绍
    $desc = false;
}

if (s_bad_upload('image', $image)) {
    //产品图片
    $image = false;
}

if (!$name
    || !$intro
    || !$desc
    || !$image
) {
    //有问题
    return s_action_json(array(
        'error'     => 99,
        'name'      => $name,
        'intro'     => $intro,
        'desc'      => $desc,
        'image'     => $image,
    ));
}


//将图片存储到产品目录中，按日归类
$date = date('Y-m-d');
$path = IMGS_DIR . '/prod/' . $date;

if (false === is_dir($path)
    && false === @mkdir($path, 0755)
) {
    return s_action_error('创建目录出错', 500);
}


$path .= '/' . $image['name'];

if (file_exists($path)
    && false === @unlink($path)
) {
    return s_action_error('图片已存在', 500);
}


if (move_uploaded_file($image['tmp_name'], $path)) {
    //没有移动img目录中去
    return s_action_error('移动图片出错', 500);
}


//生成缩略图（100X100）
$thumb100 = IMGS_DIR . '/thumb/' . $date . '/' . $image['name'];

if (false === ( image_adjust($path, $thumb) )) {
    return s_action_error('生成缩略图失败', 500);
}


//存储到数据库


exit(0);
