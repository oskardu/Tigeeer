<?php
class SystemWebUser extends CWebUser{
    /**
     * 重写login, 增加管理员权限到session
     * @param AdminIdentity $identity
     * @param int $duration
     */
    public function login($identity,$duration=0){
        $code = parent::login($identity,$duration);
        return $code;
    }
}