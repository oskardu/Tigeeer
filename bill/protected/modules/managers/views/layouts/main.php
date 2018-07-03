<!DOCTYPE html>
<html><!--[if lt IE 7]><html class="ltie9 ie6"><![endif]--><!--[if IE 7]><html class="ltie9 ie7"><![endif]--><!--[if IE 8]><html class="ltie9 ie8"><![endif]--><!--[if gte IE 9]><html class="ie9"><![endif]-->
    <head><meta charset="utf-8" />
        <title>后台管理系统</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=1">
        <meta name="keywords" content="">
        <meta name="description" content="">
        
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="/feed/all">
        <link rel="stylesheet" type="text/css" href="/statics/manager/css/custom-theme/jquery-ui-1.10.3.custom.min.css">
        <link rel="stylesheet" type="text/css" href="/statics/manager/css/global.css">
        <link rel="stylesheet" type="text/css" href="/statics/manager/css/style.css">
        <script type="text/javascript" src="/statics/manager/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/statics/manager/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/statics/manager/js/datepicker_cn.js"></script>
        <script type="text/javascript" src="/statics/manager/js/common.js"></script>
    </head>
    <body>
        <div class="contains">
            <div id="header" class="clearfix">
                <div class="header-logo Lfll"><a href="#">后台管理系统</a></div>
                <div class="header-info Lflr"><span class="Ldib header-portrait icomoon">K</span><span class="Ldib"><?php echo Yii::app()->admin->name; ?></span><span class="Ldib exit icomoon" id="exit_btn">C</span></div>
                <div class="header-main">
                    
                    <div class="header-tips">
                        <a href="/managers/order/index" class="<?php if(!strpos(Yii::app()->request->pathInfo,'admin')){echo 'cur';}?> header-tips-item">项目中心</a>
                        <a href="/managers/admin/index" class="<?php if(strpos(Yii::app()->request->pathInfo,'admin')){echo 'cur';}?> header-tips-manage">系统管理</a>
                    </div>
                </div>
            </div>
        <?php echo $content; ?>
        </div>
    </body>
</html>