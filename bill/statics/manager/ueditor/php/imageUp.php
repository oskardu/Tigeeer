<?php
	

    /**
     * Created by JetBrains PhpStorm.
     * User: taoqili
     * Date: 12-7-18
     * Time: 上午10:42
     */
    header("Content-Type: text/html; charset=utf-8");
    error_reporting(E_ERROR | E_WARNING);
    date_default_timezone_set("Asia/chongqing");
    include "Uploader.class.php";
    include "ResizeImage.php";
    //上传图片框中的描述表单名称，
    $title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
    $path = htmlspecialchars($_POST['dir'], ENT_QUOTES);
    $folder=$_POST['NewsID'];//用专属id号作为文件夹名称
    //上传配置
    $config = array(
        "savePath" => ($path == "1" ? "upload/".$folder : "upload1/".$folder),
        "maxSize" => 1000, //单位KB
        "allowFiles" => array(".gif", ".png", ".jpg", ".jpeg", ".bmp")
    );
   
    //生成上传实例对象并完成上传
    $up = new Uploader("upfile", $config);

    /**
     * 得到上传文件所对应的各个参数,数组结构
     * array(
     *     "originalName" => "",   //原始文件名
     *     "name" => "",           //新文件名
     *     "url" => "",            //返回的地址
     *     "size" => "",           //文件大小
     *     "type" => "" ,          //文件类型
     *     "state" => ""           //上传状态，上传成功时必须返回"SUCCESS"
     * )
     */
    $info = $up->getFileInfo();

    //图片做一次剪切
    $t = new ThumbnailImage();
    $dirname=dirname($info['url']);
     $smallPath= $t->getFolder($dirname.'/small');
    $t->src_file  =$info['url'];
    $t->dest_type =  THUMB_JPEG;
    $t->dest_file = $smallPath.'/'.basename($info['url']);
    $t->max_width =140;
    $t->max_height  =90;
    // 指定固定宽高
    $t->Output();
    /**
     * 向浏览器返回数据json数据
     * {
     *   'url'      :'a.jpg',   //保存后的文件路径
     *   'title'    :'hello',   //文件描述，对图片来说在前端会添加到title属性上
     *   'original' :'b.jpg',   //原始文件名
     *   'state'    :'SUCCESS'  //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
     * }
     */
    echo "{'url':'" . $info["url"] . "','title':'" . $title . "','original':'" . $info["originalName"] . "','state':'" . $info["state"] . "'}";

