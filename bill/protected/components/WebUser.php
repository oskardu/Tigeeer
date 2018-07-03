<?php
class WebUser extends CWebUser{

    const SIGNUP_STATE_EXISTED = -301; #用户已存在
    const SIGNUP_STATE_FAILED = -302; #注册失败
    const LOGIN_STATE_EMPTY_INPUT = -401; #用户名密码为空
    const LOGIN_STATE_FAILED = -403; #登陆失败
    
    public function signup($indentity){
        if (empty($indentity->password) ||  empty($indentity->user_email)) {
            throw new CException('Invalid user info');
        }
        $user = User::model()->find('user_email = :user_email', array(':user_email' => $indentity->user_email));
        if(!$user){
            $user = new User();
            $user->imported = 2;
        }else{
            if(1 != $user->imported){
                return self::SIGNUP_STATE_EXISTED;
            }else{
                $user->imported = 3;
            }
        }
        $user->user_name = $indentity->user_name;
        $user->user_email = $indentity->user_email;
        $user->user_phone = $indentity->user_phone;
        $user->activity = $indentity->activity;
        $user->ip = IpHelper::getIp();
        Yii::import('application.extensions.encrypt.SimpleCrypt');
        $crypt = new SimpleCrypt(Yii::app()->params['encrypt_key']);
        $user->user_password = $crypt->encrypt($indentity->password);
        $user->salt = $crypt->getSalt();
        $user->register_date = date('Y-m-d H:i:s');
        $user->state = 1;
        $user->imported = $indentity->imported;

        if($user->save(false)){
            return $user->id;
        }else{
            return self::SIGNUP_STATE_FAILED;
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see CWebUser::login()
     */
    public function login($identity, $duration = 0){
        if ($identity->authenticate() <= 0) 
            return false;
        $this->setName($identity->user_name);
        $this->setState('user_phone', $identity->user_phone);
        $this->setState('user_email', $identity->user_email);
        $this->setState("real_name", $identity->real_name);
        
        // IMPORTANT： 否则rememberMe不会正常工作
        return parent::login($identity, $duration);
    }

}
