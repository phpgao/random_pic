<?php

$pic_dir = 'pics';

$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $pic_dir;

function scandir_through($dir)
{
    $items = glob($dir . '/*');

    for ($i = 0; $i < count($items); $i++) {
        if (is_dir($items[$i])) {
            $add = glob($items[$i] . '/*');
            # $items不断递增
            $items = array_merge($items, $add);
        }
    }
    return $items;
}


function image_filter($files){
    $img_ext = array('gif','jpeg','png','jpg','gif');
    $new_list = null;
    foreach ($files as $file) {
        if(in_array(pathinfo($file, PATHINFO_EXTENSION), $img_ext)) $new_list[] = $file;
    }
    return $new_list;
}

function show_image($image_path){
    $type = pathinfo($image_path, PATHINFO_EXTENSION);
    if($type == 'jpg') $type = 'jpeg';
    /*
    $img_func_name_create = 'imagecreatefrom' . $type;
    $img_func_name_read = 'image' . $type;
    $img = call_user_func($img_func_name_create,$image_path);
    $img = call_user_func($img_func_name_read,$img);
    imagedestroy($img);
    */
    $header_info = 'Content-type:image/' . $type;
    header('Content-transfer-encoding: binary');
    header('Content-length: '.filesize($image_path));
    header($header_info);
    echo file_get_contents($image_path);
}

$img_list = image_filter(scandir_through($dir));

$num = count($img_list);

if($num == 0){
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");
    die;
}

show_image($img_list[rand(0,$num-1)]);

