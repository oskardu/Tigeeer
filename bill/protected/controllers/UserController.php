<?php 
    class UserController extends Controller {
        const IMAGE_DIR = "uploads/user";
        public function actionGet () {
            $id = $_GET["id"];
            $exist = $this->getUserInfo($id);
            if ($exist) {
                $dir = self::IMAGE_DIR;
                $image = $exist['image'];
                $exist['image'] = "http://{$_SERVER['HTTP_HOST']}/{$dir}/{$image}";
                echo json_encode(array('code' => 200, 'msg' => '', 'data' => $exist));
                exit;
            }
            echo json_encode(array('code' => 100, 'msg' => '帐号不存在'));
        }

        public function actionEdit () {
            $id = Yii::app()->user->id;
            $nickname = $_POST['nickname'];
            $description = $_POST['description'];
            #存在必填字段为空的情况
            if(!$nickname || !$description){
                echo json_encode(array('code' => 100, 'msg' => '参数错误'));
                exit;
            }
            
            #已存在的用户名
            $admin = Admin::model()->find("id = '{$id}'");

            if($admin){
                $admin->user_cn_name = $nickname;
                $admin->description = $description;
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

        public function actionOrder () {
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

        public function actionCollect () {
            header('Content-type:text/json');
            $userid = (int) $this->getQuery('userid', 85);
            $userid = 85;
            $page = (int) $this->getQuery('page', 1);
            $type = (int) $this->getQuery('type', 1);
            $pageSize = (int) $this->getQuery('pagesize', 5);
            if($page < 1){
                $page = 1;
            }
            $dir = self::IMAGE_DIR;
            // var_dump(321);exit;
            $offset = ($page - 1) * $pageSize;
            $res = Yii::app()->db->createCommand()
                ->select('id, userid, name, type, image, description, date, width, height')
                ->from('tt_collect')
                ->where('userid=:userid and type=:type', array(':userid' => $userid, ':type' => $type,))
                ->offset($offset)
                ->limit($pageSize)
                ->queryAll();
            $arr = array();
            foreach ($res as $key => $value) {
                $image = $value['image'];
                $value['path'] = "http://{$_SERVER["HTTP_HOST"]}/{$dir}/{$image}";
                array_push($arr, $value);
            }

            echo json_encode(array('code' => 200, 'msg' => 'User Collections', 'data' => $arr));
        }

        /**
         * 获取用户信息
         * @param string $name
         * @return array
         */
        protected function getUserInfo($id){
            return Yii::app()->db->createCommand()
                ->select('id, user_name, user_cn_name, description, image, managers, last_login_date')
                ->from('tt_admin')
                ->where('id=:id', array(':id' => $id,))
                ->queryRow();
        }

    }
?>