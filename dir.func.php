<?php
/**
 * Created by PhpStorm.
 * User: zjy
 * Date: 2018/7/27
 * Time: 16:25
 */

/**
 * 读取目录最外层内容
 * @param $path
 * @return array
 */
function readDirectory($path){
    $arr = array();
    // 打开指定目录
    $handle = opendir($path);
    // 返回目录中下一个文件的文件名
    while (($item = readdir($handle)) !== false){
        // .和..这两个特殊目录
        if ($item != "." && $item != ".."){
            if (is_file($path. "/" .$item)){
                $arr['file'][] = $item;
            }
            if (is_dir($path. "/" .$item)){
                $arr['dir'][] = $item;
            }
        }
    }
    closedir($handle);
    return $arr;
}

