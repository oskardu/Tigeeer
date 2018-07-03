<?php
class ManagersModule extends CWebModule{
    public $layout = "/layouts/main";
    
    public function init(){
        parent::init();
        $this->setImport(array(
            'managers.components.*',
        ));
        defined('YII_DEBUG') || define('YII_DEBUG', true);
        Yii::app()->setComponents(array(
        	'errorHandler' => array('errorAction' => null),
        ));     
    }
}