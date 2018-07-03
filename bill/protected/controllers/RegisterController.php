<?php 
    class RegisterController extends Controller {
        public function actionIndex () {
            $username = $_POST['username'];
            $password =  $_POST["password"];
            $nickname = $_POST["nickname"];
            
            #存在必填字段为空的情况
            if(!$username  || !$password || !$nickname){
                echo json_encode(array('code' => 100, 'msg' => '参数错误'));
                exit;
            }
            #已存在的用户名
            $exist = Admin::model()->find("user_name = '{$username}'");
            if($exist){
                echo json_encode(array('code' => 100, 'msg' => '帐号已存在'));
                exit;
            }
            $admin = new Admin();
            $admin->user_name = $username;
            Yii::import('application.extensions.encrypt.SimpleCrypt');
            $crypt = new SimpleCrypt(Yii::app()->params['encrypt_key']);
            $admin->user_password = $crypt->encrypt($password);
            $admin->salt = $crypt->getSalt();
            $admin->user_name = $username;
            $admin->user_cn_name = $nickname;
            $admin->state = 2;
            $admin->register_date = date("Y-m-d H:i:s");
            $res = $admin->save(false);
            $id = $admin->attributes['id'];
            if($res){
                echo json_encode(array('code' => 200, 'msg' => '注册成功', 'data' => array('id' => $id)));
                $refferUrl = "{$_SERVER['HTTP_REFERER']}#/active/{$id}";
                $data = array();
                $data['address'] = $username;
                $data['subject'] = "Confirmation instructions";
                $data['body'] = "<pre>Hello ".$username.",<br>

                    Thanks for registering for Tigeeer!

                    Please confirm your account by clicking the link below:

                    <a href='".$refferUrl."'>Confirm my account</a>

                    Best regards,<br>

                    Tigeeer<pre>";
                MailHelper::send($data);
            } else {
                echo json_encode(array('code' => 100, 'msg' => '注册失败'));
            }
        }
        public function actionResendEmail () {
            $id = $_GET['id'];
            $exist = Admin::model()->find("id = '{$id}'");
            if ($exist) {
                $refferUrl = "{$_SERVER['HTTP_REFERER']}#/active/{$id}";
                $data = array();
                $data['address'] = $exist->user_name;
                $data['subject'] = "Confirmation instructions";
                $data['body'] = "<pre>Hello ".$exist->user_name.",<br>

                    Thanks for registering for Tigeeer!

                    Please confirm your account by clicking the link below:

                    <a href='".$refferUrl."'>Confirm my account</a>

                    Best regards,<br>

                    Tigeeer<pre>";
                MailHelper::send($data);
                echo json_encode(array('code' => 200, 'msg' => '发送成功', 'data' => array('id' => $id)));
            } else {
                echo json_encode(array('code' => 100, 'msg' => '用户不存在', 'data' => array('id' => $id)));
            }

        }

        public function actionCheck () {
            $id = $_GET["id"];
            if (!$id) {
                echo json_encode(array('code'=>100,'msg'=>'参数错误'));
                exit;
            }
            $admin = Admin::model()->findByPk($id);
            $admin->state = 1;
            if ($admin->save(false)) {
                echo json_encode(array('code'=>200,'msg'=>'激活成功', 'data'=> $admin));
            } else {
                echo json_encode(array('code'=>100,'msg'=>'无数据'));
            }
        }

        public function actionH() {
            print_r($_SERVER);
        }
        /**
         * 获取用户信息
         * @param string $name
         * @return array
         */
        protected function getUserInfo($email){
            return Yii::app()->db->createCommand()
                ->select('id, user_name, user_password, salt, state, last_login_date')
                ->from('tt_admin')
                ->where('user_email=:user_email', array(':user_email' => $email,))
                ->queryRow();
        }
    }
?>