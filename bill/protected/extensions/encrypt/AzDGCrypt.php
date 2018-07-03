<?php
/**
 * AzDG 简单可逆加密类,每次生成不同的密文
 *
 * 使用范例：
 * $key = "abcdefgg@#!<?';
 * $cr64 = new AzDGCrypt($keys);
 * $pass = "123456789";
 * $e = $cr64->encrypt($pass);
 * echo "Crypted information = ".$e."<br>";
 * $d = $cr64->decrypt($e);
 *
 */
class AzDGCrypt {

   protected $k;

   /**
    * 构造函数
    * @param string $key 私玥
    */
   public function __construct($key)
   {
       $this->k = $key;
   }

   protected function ed($t)
   {
      $r = md5($this->k);
      $c=0;
      $v = "";
      for ($i=0;$i<strlen($t);$i++) {
         if ($c==strlen($r)) $c=0;
         $v.= substr($t,$i,1) ^ substr($r,$c,1);
         $c++;
      }
      return $v;
   }

   /**
    * 加密
    * @param string $t
    * @return string
    */
   public function encrypt($t)
   {
      srand((double)microtime()*1000000);
      $r = md5(rand(0,32000));
      $c=0;
      $v = "";
      for ($i=0;$i<strlen($t);$i++){
         if ($c==strlen($r)) $c=0;
         $v.= substr($r,$c,1) .
             (substr($t,$i,1) ^ substr($r,$c,1));
         $c++;
      }
      return base64_encode($this->ed($v));
   }

   /**
    * 解密
    * @param string $t
    * @return string
    */
   public function decrypt($t)
   {
      $t = $this->ed(base64_decode($t));
      $v = "";
      for ($i=0;$i<strlen($t);$i++){
         $md5 = substr($t,$i,1);
         $i++;
         $v.= (substr($t,$i,1) ^ $md5);
      }
      return $v;
   }
}