<?php
class ManagerController extends Controller{
    #无需登录验证的列表
    protected $notAuthActionList = array(
        'login',
        'logout',
        'add',
    );
    
    #权限列表
    protected $permissions = array();
    
    public $projectMenu = array();
    public $systemMenu = array();
    public $adminAllAclname = array();
    public $systemModels = array();
    public $isSuper = false;
    public $isManagers = false;
    public $group;
    
    public function init(){
        Yii::app()->user->setState('redirect_url', $_SERVER['REQUEST_URI']);
        parent::init();
    }
    
    /**
     * 登录检查／授权检查
     * @param CWebAction $action
     * @return boolean
     */
    protected function beforeAction($action){
        if(in_array($action->id, $this->notAuthActionList))
            return true;
        if (Yii::app()->admin->isGuest) {
            $this->redirect(Yii::app()->admin->loginUrl . '?r='.Yii::app()->user->getState('redirect_url'));
        }
        return true;
    }
    
    /**
     * 权限检查
     * @return boolean
     */
    protected function _checkPermission()
    {
       $route = $this->getRoute();
       $pur = str_replace('/','_',$route);
       $purArr = $this->loadConfig('purview.php');
       
       //当前用户的所有权限
       $adminAclname = RoleAdmin::model()->getAclnameByAdminid(Yii::app()->admin->id);
       $this->adminAllAclname = $adminAclname;
       
        $this->getAdminMenu($adminAclname);
        if(Yii::app()->params['SUPER_USERID'] != Yii::app()->admin->id)
        {
            if(in_array($pur,$purArr) && !in_array($pur,$adminAclname))
            {
                return false;
            }
        }else{
            $this->isSuper = true;
        }
//       exit;
       return true;
     
    }

    
/**
 * 后台配置文件加载类
 * TODO:以后配置文件种类比较多的时候，此方法可以独立出来放到
 */    
    public function loadConfig($filename, $option = null, $path=''){
        if(empty($filename)){
            return array();
        }
        if(!empty($path)){
            $filepath = $path .'/'.$filename;
        }else{
            $configAry = include dirname(__FILE__).'/../../../config/'.$filename;
            //todo:项目的绝对路径完善这个地方
        }
        if (false === is_null($option)) {
            $key = explode(', ', $option);
            $return = array();
            foreach ($key as $val) {
                if (false === isset($configAry[$val])) {
                    $return[$val] = false;
                } else {
                    $return[$val] = $configAry[$val];
                }
            }
            if (count($key) == 1) {
                return $return[$key[0]];
            }
            return $return;
        } else {
            return $configAry;
        }
    }
/**
 * 获取用户菜单
 */
    public function getAdminMenu($adminAclname)
    {
//        echo "<pre>";
//        print_r($adminAclname);exit;
       //项目中心的主菜单
        $projectM = Menu::model()->getChildrenByParentID(2);
       //系统管理的主菜单
        $systemMenu = Menu::model()->getChildrenByParentID(5);
        $models = array();
        if(Yii::app()->params['SUPER_USERID'] != Yii::app()->admin->id)
        {
            foreach($projectM as $kp => $menu)
            {
                if($menu['display'] == 0 || !in_array($menu['aclname'],$adminAclname))
                {
                    unset($projectM[$kp]);
                }
            }
            foreach($systemMenu as $kp => $menu)
            {
                if($menu['display'] == 0 || !in_array($menu['aclname'],$adminAclname))
                {
                    unset($systemMenu[$kp]);
                }
            }
        }
        foreach($projectM as $kp => $menu)
        {
            $model = explode('_',$menu['aclname']);
            if(isset($model[1]) && !empty($model[1]))
            {
                $projectM[$kp]['model'] = $model[1];
            }
        }
        foreach($systemMenu as $kp => $menu)
        {
            $model = explode('/',$menu['urlpath']);
            if(isset($model[2]) && !empty($model[2]))
            {
                $systemMenu[$kp]['model'] = $model[2];
                $models[] = $model[2];
            }
        }
         
        $this->projectMenu = array_values($projectM);
        $this->systemMenu = array_values($systemMenu);
        $this->systemModels = $models;
    }
    //获取用户归属组和检查是否为管理员
    protected function _checkGroup(){
        $group = Admin::model()->find("id=:id",array(":id"=>Yii::app()->admin->id));
        if (!empty($group)) {
            $this->group = $group->group;
            if ($group->managers ==1) {
                $this->isManagers = true;
            }
        }
    }
}