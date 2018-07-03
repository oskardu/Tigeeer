<?php
class DefaultController extends ManagerController{
    public $layout= '/';
    protected $defaultUrl = array('default/index');

    public function actionIndex(){
        $this->layout = "/layouts/main";
        $this->render('index');
    }
    /**
     * 登录
     */
    public function actionLogin(){
        if (!Yii::app()->admin->isGuest){
            $this->redirect($this->defaultUrl);
        }
        $referer = $this->getQuery("r");
        $errorCode = $this->getQuery('e', AdminIdentity::ERROR_NONE);
        if (Yii::app()->request->isPostRequest) {
            $identity = new AdminIdentity($this->getPost("username"), $this->getPost("password"));
            if (!$identity->authenticate()) {
                $this->redirect(array('login', 'e' => $identity->errorCode));
            }
            if (isset($_POST["remember"]))
                $duration = 3600 * 7 * 24;
            else
                $duration = 0;

            $r = Yii::app()->admin->login($identity, $duration);
            if (!$r)
                $this->redirect(array('login', 'e' => $identity->errorCode));
            if($referer){
                $this->redirect($referer);
            }else{
                $this->redirect(array('admin/index'));
            }
        }
        $this->render('login');
    }

    /**
     * 退出
     */
    public function actionLogout(){
        if (Yii::app()->admin->isGuest)
            $this->redirect(Yii::app()->admin->loginUrl);
        Yii::app()->admin->logout();
        $this->redirect(Yii::app()->admin->loginUrl);
    }
}
