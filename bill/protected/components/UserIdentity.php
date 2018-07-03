<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity{
    #账户被禁止
    const ERROR_FORBIDDEN = 110;

    #账户不存在
    const ERROR_ACCOUNT_NOT_EXISTS = 111;

    #密码错误
    const ERROR_PASSWORD_INVALID = 112;

    #User ID
    protected $_id = 0;
    
    public $loginname;
    public $user_phone;
    public $user_email;
    public $user_name;
    public $activity = 0;
    public $real_name;
    public $imported;
    public $userid = 0;
    public $role = 0;
    public $user_cn_name;

    public function __construct($loginname,$password,$user_name = null,$imported = 2,$userid = null, $role = null,$user_cn_name = null){
        $this->loginname = $loginname;
        $this->user_email = $user_name;
        $this->password=$password;
        $this->user_name = $user_name ? $user_name : $loginname;//若不设置user_name则默认为邮箱名
        $this->imported = $imported;
        $this->userid = $userid;
        $this->role = $role;
        $this->user_cn_name = $user_cn_name;
    }

    /**
     * Returns the display name for the identity.
     * The default implementation simply returns {@link username}.
     * This method is required by {@link IUserIdentity}.
     * @return string the display name for the identity.
     */
    public function getName()
    {
        return $this->user_name;
    }

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate(){
        $userInfo = $this->getUserInfo($this->user_name);
        
        
        if (empty($userInfo)) {
            $this->errorCode = self::ERROR_ACCOUNT_NOT_EXISTS;
        } else{
            Yii::import('application.extensions.encrypt.SimpleCrypt');
            $crypt = new SimpleCrypt(Yii::app()->params['encrypt_key']);
            $crypt->setSalt($userInfo['salt']);
            $password = $crypt->encrypt($this->password);
            if ($password != $userInfo['user_password']) { // 密码错误
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } elseif(2 == $userInfo['state']) { // 账户被禁止
                $this->errorCode = self::ERROR_FORBIDDEN;
            } else {
                $this->_id = $userInfo['id'];
                $this->user_phone = $userInfo['user_phone'];
                $this->user_name = $userInfo['user_name'];
                $this->user_email = $userInfo['user_email'];
                $this->real_name  = $userInfo['name'];
                $this->errorCode = self::ERROR_NONE;
                $this->userid = $userInfo['id'];
                $this->role = $userInfo['managers'];
                $this->user_cn_name = $userInfo['user_cn_name'];
            }
        }
       
        return !$this->errorCode;
    }


    /**
     * 重新父类方法，返回ID
     */
    public function getId(){
        return $this->_id;
    }

    /**
     * 获取用户信息
     * @param string $name
     * @return array
     */
    protected function getUserInfo($user_name){
        return Yii::app()->db->createCommand()
            ->select('id, user_name, user_password, salt, state, managers, user_cn_name,last_login_date')
            ->from('tt_admin')
            ->where('user_name=:user_name', array(':user_name' => $user_name))
            ->queryRow();
    }
}