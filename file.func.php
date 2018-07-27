<?php
/**
 * Created by PhpStorm.
 * User: zjy
 * Date: 2018/7/27
 * Time: 16:43
 */


/**
 * 转换字节大小
 * @param $size
 * @return string
 */
function transByte($size){
    $arr = array("B","KB","MB","GB","TB","EB");
    $i = 0;
    while ($size >= 1024){
        $size /= 1024;
        $i++;
    }
    return round($size,2).$arr[$i];
}

function createFile($filename){
    // 验证文件的合法性，是否包含一些特殊字符
    $pattern = "/[\/,\*,<>,\?\|]/";
    if (!preg_match_all($pattern,basename($filename))){
        // 当前目录下是否存在同名文件
        if (!file_exists($filename)){
            // 通过touch($filename)创建
            if (touch($filename)){
                return "文件创建成功";
            }else{
                return "文件创建失败";
            }
        }else{
            return "文件已存在，请重命名以后创建";
        }
    }else{
        return "非法文件名";
    }
}