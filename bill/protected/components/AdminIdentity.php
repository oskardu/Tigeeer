<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AdminIdentity extends CUserIdentity{
    #账户被禁止
    const ERROR_FORBIDDEN = 110;
    
    #账户不存在
    const ERROR_ACCOUNT_NOT_EXISTS = 111;
    
    #密码错误
    const ERROR_PASSWORD_INVALID = 112;

    #管理员ID
    protected $_id = 0;

    /**

    * Authenticates a user.
    * The example implementation makes sure if the username and password
    * are both 'demo'.
    * In practical applications, this should be changed to authenticate
    * against some persistent user identity storage (e.g. database).
    * @return boolean whether authentication succeeds.
    */
    public function authenticate(){
        // return 1;
        $adminInfo = $this->getAdminInfo($this->username);
        if (empty($adminInfo)) {
            $this->errorCode = self::ERROR_ACCOUNT_NOT_EXISTS;
        } else{
            Yii::import('application.extensions.encrypt.SimpleCrypt');
            $crypt = new SimpleCrypt(Yii::app()->params['encrypt_key']);
            $crypt->setSalt($adminInfo['salt']);
            $password = $crypt->encrypt($this->password);
            if ($password != $adminInfo['user_password']) { // 密码错误
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } elseif(2 == $adminInfo['state']) { // 账户被禁止
                $this->errorCode = self::ERROR_FORBIDDEN;
            } else {
                $this->_id = $adminInfo['id'];
                $this->errorCode = self::ERROR_NONE;
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
     * 获取管理员信息
     * @param string $name
     * @return array
     */
    protected function getAdminInfo($name){
        return Yii::app()->db->createCommand()
            ->select('id, user_name, user_password, salt, state, last_login_date')
            ->from('tt_admin')
            ->where('user_name=:user_name', array(':user_name' => $name,))
            ->queryRow();
    }
}