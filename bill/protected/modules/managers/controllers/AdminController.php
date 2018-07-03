<?php
class AdminController extends ManagerController{
    /**
     * 用户列表
     */
    public function actionIndex(){
        $state = $this->getQuery('state', 1);
        $adminList = Admin::model()->getAdminAttr($state);
//        var_dump($this->projectCenter);
//        exit;
//        $adminList = Admin::model()->findAll();
        
        $this->render('index', array('admin_list' => $adminList));
    }
    
    /**
     * 获取管理员账户信息
     * @param int $id
     */
    public function actionGetDetail($id){
        $admin = Admin::model()->with("permission")->findByPk($id);
        $info = $admin->attributes;
        $info['permission'] = $admin->permission->attributes;
        echo json_encode($info);
    }
    
    /**
     * 新增用户
     */
    public function actionAdd(){
        if($this->isPostRequest){
            #存在必填字段为空的情况
            if(!$this->getPost('username')  || !$this->getPost('password')){
                echo 2;
                exit;
            }
            #已存在的用户名
            $exist = Admin::model()->find("user_name = '{$this->getPost('username')}'");
            if($exist){
                echo 3;
                exit;
            }
            $admin = new Admin();
            $admin->user_name = $this->getPost('username');
            Yii::import('application.extensions.encrypt.SimpleCrypt');
            $crypt = new SimpleCrypt(Yii::app()->params['encrypt_key']);
            $admin->user_password = $crypt->encrypt($_POST['password']);
            $admin->salt = $crypt->getSalt();
            $admin->user_email = $this->getPost('email');
            $admin->user_cn_name = $this->getPost('user_cn_name');
            $admin->user_phone = $this->getPost('phone');
            $admin->register_date = date("Y-m-d H:i:s");
            $admin->state = $this->getPost('state');
            $admin->managers = $this->getPost('managers');
            $admin->group = $this->getPost('group');
            if($admin->save(false)){
                echo 1;
            }
            
        }else{
            echo 0;
        }
        exit;
    }
    
    /**
     * 编辑信息
     * @param int $id
     */
    public function actionEdit($id){
        $admin = Admin::model()->findByPk($id);
        $info = $admin->attributes;
        if($this->isPostRequest){
            #存在必填字段为空的情况
            if(!$this->getPost('username') || !$admin->user_email = $this->getPost('email') 
               || !$this->getPost('state') ){
                echo 2;
                exit;
            }
            $admin->user_name = $this->getPost('username');
            $admin->user_cn_name = $this->getPost('user_cn_name');
            $admin->user_email = $this->getPost('email');
            $admin->user_phone = $this->getPost('phone');
            $admin->state = $this->getPost('state');
            $admin->managers = $this->getPost('managers');
            $admin->group = $this->getPost('group');
            if($admin->save(false)){
                echo 1;
                exit;
            }
        }
        echo json_encode($info);
        exit;        
    }
    /**
     * 重置密码
     * @param int $id
     */
    public function actionResetPassword($id){
        $admin = Admin::model()->findByPk($id);
        if($this->isPostRequest){
            Yii::import('application.extensions.encrypt.SimpleCrypt');
            $crypt = new SimpleCrypt(Yii::app()->params['encrypt_key']);
            $admin->user_password = $crypt->encrypt($_POST['password']);
            $admin->salt = $crypt->getSalt();
            if($admin->save(false)){
                echo 1;
            }else{
                echo 2;
            }
            exit;
        }
    }
    

    /**
     * 重置密码
     * @param int $id
     */
    public function getRoleList($id){
        return 'ddddd';
    }

}