<?php
/**
 * 简单单向加密类
 */
class SimpleCrypt {

    protected $key = "";

    protected $salt = "";

    /**
     * @param string $key
     */
    public function __construct($key){
        $this->key = $key;
    }

    /**
     * 通过生成一个变化的盐值，再通过变化盐值解码后得到真正的盐值，原文MD5后稍微变换和真正的盐值组成一个新的串，然后再次MD5
     * @todo 可能效率不佳
     * @param string $str
     * @return string
     */
    public function encrypt($str){
        Yii::import('application.extensions.encrypt.AzDGCrypt');

        $crypt = new AzDGCrypt($this->key);
        if ("" != $this->salt) {
            $passKey = $crypt->decrypt($this->salt);
        } else {
            $passKey = uniqid();
            $this->salt = $crypt->encrypt($passKey);
        }

        $str = md5($str);

        $passKey = strrev(substr($str, 0, 16)) . $passKey;
        $passKey .= substr($str, -1, 16);

        return md5($passKey . $str);
    }

    /**
     * 得到盐值
     */
    public function getSalt(){
        return $this->salt;
    }

    /**
     * 设定盐值
     * @param string $salt AzDGCrypt encrypt() 结果
     */
    public function setSalt($salt){
        $this->salt = $salt;
    }
}