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

/**
 * 得到文件夹大小
 * @param $path
 * @return int
 */
function dirSize($path){
    $sum = 0;
    $handle = opendir($path);
    // 返回目录中下一个文件的文件名
    while (($item = readdir($handle)) !== false){
        if ($item != "." && $item != ".."){
            if (is_file($path. "/" .$item)){
                $sum += filesize($path. "/" .$item);
            }
            if (is_dir($path. "/" .$item)){
                $sum += dirSize($path. "/" .$item);
            }
        }
    }
    closedir($handle);
    return $sum;
}

/**
 * 创建文件夹
 * @param $dirname
 * @return string
 */
function createFolder($dirname){
    if (checkDirname($dirname)){
        // 当前目录下是否存在同名文件
        if (!file_exists($dirname)){
            // 通过mkdir($dirname)创建
            if (mkdir($dirname)){
                return "文件夹创建成功";
            }else{
                return "文件夹创建失败";
            }
        }else{
            var_dump(file_exists($dirname));
            return "文件夹已存在，请重命名以后创建";
        }
    }else{
        return "非法文件夹名";
    }
}

/**
 * 检测文件名是否合法 合法 返回true
 * @param $dirname
 * @return bool
 */
function checkDirname($dirname){
    // 验证文件的合法性，是否包含一些特殊字符
    $pattern = "/[\/,\*,<>,\?\|]/";
    if (preg_match_all($pattern,basename($dirname))){
        return false;
    }else{
        return true;
    }
}

/**
 * 重命名文件夹名字
 * @param $oldname
 * @param $newdirname
 * @return string
 */
function renameFolder($oldname,$newdirname){
    // 验证文件名是否合法
    if (checkFilename($newdirname)){
        // 获取旧文件所在路径
        $path = dirname($oldname);
        // 检测新文件名是否存在
        if (!file_exists($path. "/" .$newdirname)){
            // 进行重命名
            if (rename($oldname,$path. "/" .$newdirname)){
                return "重命名文件夹成功";
            }else{
                return "重命名文件夹失败";
            }
        }else{
            return "存在同名文件夹，请重新命名";
        }
    }else{
        return "非法文件夹名";
    }
}

/**
 * 复制文件夹
 * @param $src
 * @param $dst
 */
function copyFolder($src,$dst){
    if (!file_exists($dst)){
        mkdir($dst,0777,true);
    }
    $handle = opendir($src);
    while (($item = readdir($handle)) !== false){
        if ($item != "." && $item != ".."){
            if (is_file($src. "/" .$item)){
                copy($src. "/" .$item, $dst. "/" .$item);
            }
            if (is_dir($src. "/" .$item)){
                copyFolder($src. "/" .$item, $dst. "/" .$item);
            }
        }
    }
    closedir($handle);
}

/**
 * 剪切文件夹
 * @param $src
 * @param $dst
 * @return string
 */
function cutFolder($src,$dst){
    if (file_exists($dst)){
        if (is_dir($dst)){
            if (!file_exists($dst. "/" . basename($src))){
                if (rename($src,$dst. "/" . basename($src))){
                    return "剪切文件夹成功";
                }else{
                    return "剪切文件夹失败";
                }
            }else{
                return "存在同名文件夹";
            }
        }else{
            return "不是一个文件夹";
        }
    }else{
        return "目标文件夹不存在";
    }
}

function delFolder($path){
    $handle = opendir($path);
    while (($item = readdir($handle)) !== false){
        if ($item != "." && $item != ".."){
            if (is_file($path . "/" .$item)){
                unlink($path . "/" .$item);
            }
            if (is_dir($path . "/" .$item)){
                delFolder($path . "/" .$item);
            }
        }
    }
    closedir($handle);
    rmdir($path);
}
