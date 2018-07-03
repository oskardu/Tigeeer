<?php 
    class FwdController extends Controller {
        public function actionIndex () {
            echo 1;
        }

        public function actionSendEmail () {
            $username = $_POST['username'];
            #存在必填字段为空的情况
            if(!$username){
                echo json_encode(array('code' => 100, 'msg' => '参数错误'));
                exit;
            }
            #已存在的用户名
            $admin = Admin::model()->find("user_name = '{$username}'");
            if($admin){
                $admin->identify = time();
                $res = $admin->save(false);
                if($res){
                    $refferUrl = "{$_SERVER['HTTP_REFERER']}#/reset/{$username}";
                    $data = array();
                    $data['address'] = $username;
                    $data['subject'] = "Confirmation instructions";
                    $data['body'] = "<pre>Hello ".$username.",<br>

                        Thanks for registering for Tigeeer!

                        Please confirm your account by clicking the link below:

                        <a href='".$refferUrl."'>Reset password</a>

                        Best regards,<br>

                        Tigeeer<pre>";
                    $mail = MailHelper::send($data);
                    if ($mail) {
                        echo json_encode(array('code' => 200, 'msg' => '发送成功'));
                    } else {
                        echo json_encode(array('code' => 100, 'msg' => '操作失败'));
                    }
                } else {
                    echo json_encode(array('code' => 100, 'msg' => '操作失败'));
                }
            } else {
                echo json_encode(array('code' => 100, 'msg' => '帐号不存在'));
                exit;
            }
        }
        public function actionReset () {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            #存在必填字段为空的情况
            if(!$username || !$password || !$password2){
                echo json_encode(array('code' => 100, 'msg' => '参数错误'));
                exit;
            }
            if ($password != $password2) {
                echo json_encode(array('code' => 100, 'msg' => '2次密码不一致'));
                exit;
            }
            #已存在的用户名
            $admin = Admin::model()->find("user_name = '{$username}'");

            if($admin){
                $diff = time() - $admin->identify > 300;
                if ($diff) {
                    echo json_encode(array('code' => 100, 'msg' => '验证过期，请重新验证'));
                    exit;
                }
                Yii::import('application.extensions.encrypt.SimpleCrypt');
                $crypt = new SimpleCrypt(Yii::app()->params['encrypt_key']);
                $admin->user_password = $crypt->encrypt($password);
                $admin->salt = $crypt->getSalt();
                $res = $admin->save(false);
                if($res){
                    echo json_encode(array('code' => 200, 'msg' => '修改成功'));
                } else {
                    echo json_encode(array('code' => 100, 'msg' => '操作失败'));
                }
            } else {
                echo json_encode(array('code' => 100, 'msg' => '帐号不存在'));
                exit;
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