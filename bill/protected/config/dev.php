<?php
return array(
    //'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    //'name' => 'BuyinCoins.com',
    'basePath' => dirname(dirname(__FILE__)),
    'import' => array(
                'application.models.*',
                'application.components.*',
            ),
    'components'=>array(
        'db' => array(
                'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=127.0.0.1;dbname=hongjia',
                'emulatePrepare' => true,
                'schemaCachingDuration' => 3600,
                'username' => 'root',
                //'password' => 'Hongjia!@#',
                'password' => '',
                'charset' => 'utf8',
                'tablePrefix' => 'tt_',
                'enableProfiling' => true,
                'enableParamLogging' => true,
            ),
        'admin'=>array(
                // enable cookie-based authentication
                'class' => 'SystemWebUser',
                'allowAutoLogin' => true,
                'loginUrl' => '/managers/default/login',
            ),
        'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                // 'rules' => CMap::mergeArray(
                    
                // )
            ),
        'imageLib'=>array(
            'class'  => 'application.components.image.CImageComponent',
            'driver' => 'GD',
            'params' => array('directory'=>'/usr/local/bin'), // ImageMagick setup path
        ),

        ),

    
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            'ipFilters' => array('*.*.*.*'),
            //'generatorPaths' => array(
            //    'ext.bootstrap.gii'
            //),
        ),
        'managers'=>array(
            'defaultController'=>'index',
        ),
        
    ),
    'params' => array(
        'key'=>'hongjia+',

    ),

);
