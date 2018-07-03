<?php 
    class LoginController extends Controller {
        public function actionIndex () {
            $user_name = $_POST['username'];
            $password =  $_POST["password"];
            // $user_name = "897786427@qq.com";
            // $password =  123456;
            $remember =1;
            $identity = new UserIdentity($user_name, $password);
            if (!$identity->authenticate()) {
                
                echo json_encode(array('code'=>100,'msg'=>'登陆失败','data'=>$identity->authenticate()));
                exit;
            }
            if ($remember)
                $duration = 3600 * 7 * 24;
            else
                $duration = 0;
            $r = Yii::app()->user->login($identity, $duration);
            if ($r) {
                //Yii::app()->user->getStateKeyPrefix()
                echo json_encode(array('code' => 200, 'message' => '登录成功', 'email' => Yii::app()->user->name,'data'=>$identity, 'token' => session_id()));
            } else {
                echo json_encode(array('code' => 100, 'message' => '呃哦！登录遇到了问题', 'email' => ''));

            }

        }
        public function actionCheckLogin () {

            if (Yii::app()->user->id){
                echo json_encode(array('code' => 200, 'message' => '已登录'));
            }else{
                echo json_encode(array('code' => 100, 'message' => '未登录'));
            }
            
        }
        public function actionUserInfo () {

            if (Yii::app()->user->id){
                echo json_encode(array('code' => 200, 'username' => Yii::app()->user->name));
            }else{
                echo '未登录';
            }

        }
        /**
        * 退出
        */
        public function actionLogout()
        {

            $code = Yii::app()->user->logout(true, true);
    
            echo  json_encode(array('code' => 200, 'message' => '退出登录成功', 'username' => ''));
        }
    }
?>