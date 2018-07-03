<?php
class Captcha{

  private $_font     = '';
  private $_possible = '0123456789abcdefhigklmnoprstuvwxyz';
  private $_width    = 120;
  private $_height   = 20;
  private $_len      = 6;
  private $_code     = '';

  public function setPossible($string)
  {
    if(!$string || !is_string($string))
      throw new CException("$string不能为空,且必须是字符串!");
    $this->_possible = $string;
  }

  private function generateCode($characters) {
    /* list all possible characters, similar looking characters and vowels have been removed */
    $code = '';
    $i = 0;
    while ($i < $characters) {
      $code .= substr($this->_possible, mt_rand(0, strlen($this->_possible)-1), 1);
      $i++;
    }
    return $code;
  }

  public function __construct($characters=4,$width = null,$height = null) {

    $this->_font = dirname(__FILE__).'/monofont.ttf';

    if($characters>0) $this->_len = $characters;
    if($width>0) $this->_width = $width;
    if($height>0) $this->_height = $height;


  }

  public function createCaptcha()
  {
    $this->_code = $this->generateCode($this->_len);
    $font_size = $this->_height * 0.94;
    if(($image = @imagecreate($this->_width, $this->_height))===false ){
      throw new CException('Cannot initialize new GD image stream');
    }
    /* set the colours */
    $background_color = imagecolorallocate($image, 255, 255, 255);
    $text_color = imagecolorallocate($image, 20, 40, 100);
    $noise_color = imagecolorallocate($image, 100, 120, 180);
    /* generate random dots in background */
    for( $i=0; $i<($this->_width*$this->_height)/3; $i++ ) {
      imagefilledellipse($image, mt_rand(0,$this->_width), mt_rand(0,$this->_height), 1, 1, $noise_color);
    }
    /* generate random lines in background */
    for( $i=0; $i<($this->_width*$this->_height)/150; $i++ ) {
      imageline($image, mt_rand(0,$this->_width), mt_rand(0,$this->_height), mt_rand(0,$this->_width), mt_rand(0,$this->_height), $noise_color);
    }
    /* create textbox and add text */
    if(($textbox = imagettfbbox($font_size, 0, $this->_font, $this->_code))===false){
      throw new CException('Error in imagettfbbox function');
    }
    $x = ($this->_width - $textbox[4])/2;
    $y = ($this->_height - $textbox[5])/2;
    if((imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->_font , $this->_code))===false ){
      throw new CException('Error in imagettftext function');
    }
    /* output captcha image to browser */
    header('Content-Type: image/jpeg');
    imagejpeg($image);
    imagedestroy($image);
  }

  public function getCode ()
  {
    return $this->_code;
  }

  public function setCharLen($len)
  {
    if(!$len)
      throw new CException('设置的字符长度不能小等于0');
    $this->_len = $len;
  }

  public function setWidth($width)
  {
    if(!$width)
      throw new CException('设置的宽度不能小等于0');
    $this->_width = $width;
  }

  public function setHeight($height)
  {
    if(!$height)
      throw new CException('设置的高度不能小等于0');
    $this->_height = $height;
  }

}