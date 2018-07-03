<?php
header("Access-Control-Allow-Origin: *");
// include Yii bootstrap file
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE);
$yii = '../core/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';
defined('YII_DEBUG') || define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL')
  || define('YII_TRACE_LEVEL', 5);
//require_once(dirname(__FILE__).'./core/yii.php');

// create a Web application instance and run
include $yii;
Yii::beginProfile('blockID');
Yii::createWebApplication($config)->run();
Yii::endProfile('blockID');
