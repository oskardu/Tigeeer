<!DOCTYPE html>
<html><!--[if lt IE 7]><html class="ltie9 ie6"><![endif]--><!--[if IE 7]><html class="ltie9 ie7"><![endif]--><!--[if IE 8]><html class="ltie9 ie8"><![endif]--><!--[if gte IE 9]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8" />
        <title>弘家物流后台管理系统</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=1">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="/feed/all">
        <link rel="stylesheet" type="text/css" href="/statics/manager/css/global.css">
        <link rel="stylesheet" type="text/css" href="/statics/manager/css/style.css">
        <script type="text/javascript" src="/statics/manager/js/jquery-1.9.1.min.js"></script>
    </head>
    <body>
        <div class="contains">
            <div id="header" class="onload">
                <div class="header-logo"><a href="#">弘家物流后台管理系统</a></div>
            </div>
            <div id="main">
                <div class="load-box">
                    <div class="load-wrap">
                        <?php
                            $usernameMsg = "请输入用户名";
                            $passwordMsg = "请输入密码";
                            if(AdminIdentity::ERROR_FORBIDDEN == $this->getQuery('e')){
                                    $usernameMsg = "用户状态已冻结，请联系管理员";
                            }else if(AdminIdentity::ERROR_ACCOUNT_NOT_EXISTS == $this->getQuery('e')){
                                    $usernameMsg = "用户名不存在";
                            }else if(AdminIdentity::ERROR_PASSWORD_INVALID == $this->getQuery('e')){
                                    $passwordMsg = "密码错误";
                            }
                        ?>
                        <form method="post" name="load-form">
                            <p><span class="icomoon user-name">K</span>
                                <input name="username" id="user-name" type="text" placeholder="<?php echo $usernameMsg; ?>" class="load-input Lposr">
                            </p>
                            <p><span class="icomoon userpsw">L</span>
                                <input name="password" id="user-psw" type="password" placeholder="<?php echo $passwordMsg; ?>" class="load-input Lposr">
                            </p>
                            <p class="load-btns">
                                <button type="submit" value="登录" class="Lflr pms-btn">登录</button>
                                <!-- <input name="remember" type="checkbox" value="记住我">记住我 -->
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div id="footer">
                <p>Copyright © <?php echo date("Y")?> Hongjiawuliu</p>
            </div>
        </div>
        <div id="scriptArea" data-page-name="@onload" class="Ldn"></div>
        <!--  <script type="text/javascript" src="/statics/manager/js/global.js"></script>-->
    </body>
</html>