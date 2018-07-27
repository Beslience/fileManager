<?php
/**
 * Created by PhpStorm.
 * User: zjy
 * Date: 2018/7/27
 * Time: 17:06
 */

/**
 * 提示信息 并且跳转
 * @param $mes
 * @param $url
 */
function alertMes($mes,$url){
    echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
}