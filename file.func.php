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

/**
 * 创建文件
 * @param $filename
 * @return string
 */
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

/**
 * 重命名文件名
 * @param $oldname
 * @param $newname
 * @return string
 */
function renameFile($oldname,$newname){
    // 验证文件名是否合法
    if (checkFilename($newname)){
        // 获取旧文件所在路径
        $path = dirname($oldname);
        // 检测新文件名是否存在
        if (!file_exists($path. "/" .$newname)){
            // 进行重命名
            if (rename($oldname,$path. "/" .$newname)){
                return "重命名成功";
            }else{
                return "重命名失败";
            }
        }else{
            return "存在同名文件，请重新命名";
        }
    }else{
        return "非法文件名";
    }
}

/**
 * 删除文件
 * @param $filename
 * @return string
 */
function delFile($filename){
    if (unlink($filename)){
        return "文件删除成功";
    }else{
        return "文件删除失败";
    }
}

/**
 * 下载文件
 * @param $filename
 */
function downFile($filename){
    header("content-disposition:attachment;filename=".basename($filename));
    header("content-length:".filesize($filename));
    readfile($filename);
}


/**
 * 检测文件名是否合法 合法 返回true
 * @param $filename
 * @return bool
 */
function checkFilename($filename){
    // 验证文件的合法性，是否包含一些特殊字符
    $pattern = "/[\/,\*,<>,\?\|]/";
    if (preg_match_all($pattern,basename($filename))){
        return false;
    }else{
        return true;
    }
}

function copyFile($filename,$dst){
    if (file_exists($dst)){
        if (!file_exists($dst. "/" .basename($filename))){
            if(copy($filename,$dst. "/" .basename($filename))){
                return "复制文件成功";
            }else{
                return "复制文件失败";
            }
        }else{
            return "存在同名文件";
        }
    }else{
        return "目录不存在";
    }
}

function cutFile($filename,$dst){
    if (file_exists($dst)){
        if (!file_exists($dst. "/" .basename($filename))){
            if(rename($filename,$dst. "/" .basename($filename))){
                return "剪切文件成功";
            }else{
                return "剪切文件失败";
            }
        }else{
            return "存在同名文件";
        }
    }else{
        return "目录不存在";
    }
}

function uploadFile($fileInfo,$path,$allowExt = array("jpg", "jpeg", "gif", "png", "txt"),$maxSize = 10485760){
    // 判断错误号
    if ($fileInfo['error'] == UPLOAD_ERR_OK){
        // 文件是否是通过http上传
        if (is_uploaded_file($fileInfo['tmp_name'])){
            // 上传文件的文件类型,
            $ext = getExt($fileInfo['name']);
            $uniqid = getUniqidName();
            $destination = $path. "/" .pathinfo($fileInfo['name'],PATHINFO_FILENAME). "_" .$uniqid. "." .$ext;
            if (in_array($ext, $allowExt)){
                if ($fileInfo['size'] <= $maxSize){
                    if (move_uploaded_file($fileInfo['tmp_name'], $destination)){
                        $mes = "文件上传成功";
                    }else{
                        $mes = "文件上传失败";
                    }
                }else{
                    $mes = "文件过大";
                }
            }else{
                $mes = "非法文件类型";
            }
        }else{
            $mes = "文件不是通过http post上传";
        }
    }else{
        switch ($fileInfo['error']){
            case 1:
                $mes = "超过配置文件的大小";
                break;
            case 2:
                $mes = "超过了表单允许接受数据的大小";
                break;
            case 3:
                $mes = "文件被部分上传";
                break;
            case 4:
                $mes = "没有文件被上传";
        }
    }
    return $mes;
}