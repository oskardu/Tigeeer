<?php
/**
 * 通用工具类
 * @author Williams
 */
class CommonHelper{
    /**
     * 是否本地开发环境
     * @return boolean
     */
    public static function isDev(){
        return ( strpos($_SERVER['SERVER_ADDR'], '192.168') !== false || strpos($_SERVER['SERVER_ADDR'], '127.0') !== false );
    }

    /**
     * 开发环境是否开启Minify
     * @return boolean
     */
    public static function isMin()
    {
        return (defined('MIN_STATE') ? MIN_STATE : false);
    }

    /**
     * 分页
     * @param unknown_type $url 跳转url
     * @param unknown_type $page_no 页码
     * @param unknown_type $total_page 总页数
     * @param unknown_type $total_size 总记录数
     * @param unknown_type $get_single_action 获取单独的请求url L:上一页 N：下一页
     */
    public static function pagination($url, $page_no, $total_page ,$total_size ,$get_single_action = false){
        //get参数
        $hidden_str = '';
        if (!$url) 
            $url = Yii::app()->request->getUrl();
        $url = preg_replace('/&?page=?(\d+)?/i', '', $url);
        $url = false === strpos($url, '?') ? "{$url}?page=" : "{$url}&page=";
        $parse_url = parse_url($url);
        $page_no = min(intval($page_no),intval($total_page)) ;
        if(!empty($parse_url['query'])) {
    
            //在这个变量设置跳转的地址和传递的参数
            $this_webPage = $url;
    
            parse_str($parse_url['query'],$para);
    
            foreach ($para as $k => $v) {
                //$hidden_str .= "<input type='hidden' name='{$k}' value='".urldecode($v)."' />\n";
            }
        }else{
            //在这个变量设置跳转的地址和传递的参数
            $this_webPage = $url;
        }
    
        $out_put = "
        <div id='paginationWrapper'>\n
        <div id='pagination'>\n
        <form action='{$parse_url['path']}' method='GET'> \n";
    
        //最前页
        if($total_page <= 1 || $page_no == 1) {
            $out_put .= "<em>首页</em>";
        }else{
            $out_put .= "<a href=\"{$this_webPage}1\" class='first first-grey disabled'>首页</a>";
        }
    
    
        //上一页
        $last_page = $page_no-1;
        if ($last_page > 0){
            if('L' == strtoupper($get_single_action)) {
                return "{$this_webPage}{$last_page}";
            }
            $out_put .= "<a href=\"{$this_webPage}{$last_page}\" class='prev prev-grey disabled'>上页</a>&nbsp;";
        }else{
            $out_put .= "<em>上页</em>";
            if('L' == strtoupper($get_single_action)) {
                return '';
            }
        }
    
    
        //中间7页
        for ($i = -3;$i < 4;$i++){
    
            if ($page_no < 4){
                //靠近最左边的情况
                $displayNo = $i + 4;
            }
            else if ($page_no > $total_page - 3){
                //靠近最右边的情况
                $displayNo = $total_page + $i - 3;
            }
            else{
                //中间
                $displayNo = $page_no + $i;
            }
    
            if ($displayNo >= 1 && $displayNo <= $total_page){
                if($displayNo == $page_no){
                    $out_put .= "<a href=\"{$this_webPage}{$displayNo}\" class='current'>{$displayNo}</a> ";
                }else{
                    $out_put .= "<a href=\"{$this_webPage}{$displayNo}\">{$displayNo}</a> ";
                }
            }
    
        }
    
    
        //下一页
        $next_page = $page_no + 1;
        if ($next_page < $total_page+1){
            if('N' == strtoupper($get_single_action)) {
                return "{$this_webPage}";
            }
            $out_put .= "<a href=\"{$this_webPage}{$next_page}\" class='next next-grey'>下页</a>";
    
        }else{
            $out_put .= "<em>下页</em>";
            if('N' == strtoupper($get_single_action)) {
                return '';
            }
        }
    
        //最后页
        if ($total_page > 1 && $page_no != $total_page){
    
            $out_put .= "<a href=\"{$this_webPage}{$total_page}\" class='next next-grey'>尾页</a>";
        }else{
            $out_put .= "<em>尾页</em>";
        }
    
        $out_put .= "&nbsp;总共{$total_size}条&nbsp;共{$total_page}页&nbsp;到 <input type='text' value='{$page_no}' name='page' id='pageindex' style='width:30px;'/> 页<button type='submit' value='跳转' style='padding: 5px 4px;color:black'>跳转</button>";
        $out_put .= $hidden_str;
    
        $out_put .= '</div></div></form>';
        return $out_put;
    }
    
    /**
     * 获取图片路径
     * @param int $id 酒店ID
     * @param boolean $show 是否是展示用
     * @param boolean $local 是否用户本机
     * @param int $flag 用于决定使用哪个域名，可以是任意整数
     */
    public static function getImagePath($id, $show = true, $local = true, $flag = 0){
        $domainType = $flag % 3;
        switch($domainType)
        {
            case 1 : $domain = 'http://image01.hivilla.com'; break;
            case 2 : $domain = 'http://image02.hivilla.com'; break;
            default : $domain = 'http://statics.hivilla.com'; break;
        }
        if($local)
            $domain = "";
        
        if($show)
            return $domain."/uploads/destination/article/".floor($id/1000)."/".$id."/";
        else
            return Yii::getPathOfAlias('webroot')."/uploads/destination/article/".floor($id/1000)."/".$id."/";
    }

    public static function getStaticPath() {
        return '/statics/web/antarctica';
    }
    
    /**
     * 生成缩略图
     * @param string $imageFile
     * @param boolean 只生成原图
     */
    public static function generateThumb($imageFile, $rawOnly = false, $tlwatermark = false, $brwatermark = false){
        if(!$imageFile || !file_exists($imageFile)){
            return false;
        }
        $info = pathinfo($imageFile);
        $path = $info['dirname'];
        $name = $info['filename'];
        $ext = $info['extension'];

        list($width, $height, $picture_type) = getimagesize($imageFile);
        if($ext == 'png'){
            $imageFile = self::png2jpg($imageFile, 100);
            $ext = 'jpg';
        }

        $image = Yii::app()->imageLib->load($imageFile);

        $thumbSize = Yii::app()->params['thumb_size'];
        list($width, $height, $picture_type) = getimagesize($imageFile);
        $thumb = $originalName = "{$path}/{$name}.{$ext}";
        $image->quality(Yii::app()->params['thumb_quality']);
        $image->resize($width, $height, Image::WIDTH) // 文字会同比缩小
                ->save($thumb, false);
        if(!$rawOnly){
            $raw = "{$path}/raw.{$name}.{$ext}";
            $image->resize($width, $height, Image::WIDTH) // 文字会同比缩小
            ->save($raw, false);
            foreach ($thumbSize as $key=>$size) {
                $thumb = "{$path}/{$key}.{$name}.{$ext}";
                $_width = $size['width'];
                $_height = $size['height'];
                $image->resize($_width, $_height, Image::WIDTH) // 文字会同比缩小
                    ->save($thumb, false);
            }
        }
        if($tlwatermark){
            self::watermark($originalName, 'tl', Yii::getPathOfAlias('webroot').'/statics/manager/images/watermark.png');
        }
        if($brwatermark){
            self::watermark($originalName, 'br', Yii::getPathOfAlias('webroot').'/statics/manager/images/watermark_.png');
        }
        return true;
    }

    private static function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
        $opacity=$pct;
        // getting the watermark width
        $w = imagesx($src_im);
        // getting the watermark height
        $h = imagesy($src_im);
        
        // creating a cut resource
        $cut = imagecreatetruecolor($src_w, $src_h);
        // copying that section of the background to the cut
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
        // inverting the opacity
        $opacity = 100 - $opacity;
        
        // placing the watermark now
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
    }
        
    public static function watermark($imageFile, $position='bl', $watermarkImage=null){
        if(!is_file($watermarkImage))
            return;
        $watermark = imagecreatefrompng($watermarkImage);
        imagecolorclosest($watermark, 0, 0, 0);
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);
        
        $image = imagecreatefromjpeg($imageFile);
        $size = getimagesize($imageFile);
        
        if($position == 'tl') // top left
        {
            $dest_x = 0;
            $dest_y = 0;
        }
        elseif($position == 'bl') // bottom left
        {
            $dest_x = 0;
            $dest_y = $size[1] - $watermark_height - 5;
        }
        elseif($position == 'br') // bottom right
        {
            $dest_x = $size[0] - $watermark_width;
            $dest_y = $size[1] - $watermark_height;
        }
        elseif($position == 'tr') // top right
        {
            $dest_x = $size[0] - $watermark_width;
            $dest_y = 0;
        }
        
        self::imagecopymerge_alpha($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 35);
        imagejpeg($image, $imageFile);
        imagedestroy($image);
        imagedestroy($watermark);
    }
    /*
     * PNG转JPG
     * 例子： abc.png -> abc.jpg
     */
    public static function png2jpg($file, $quality=100) {
        $image = imagecreatefrompng($file);
        $jpg = str_replace('.png', '.jpg', $file);
        imagejpeg($image, $jpg, $quality);
        imagedestroy($image);
        return $jpg;
    }
    
    /**
     * 判读是否为移动设备访问
     */
    public static function isMobile(){
        $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        
        #对于pad则显示pc页面
        if(strpos($useragent, "iPad") || strpos($useragent, "pad")){
            return false;
        }
        
        $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
        if(!function_exists("CheckSubstrs")) {
            function CheckSubstrs($substrs,$text){  
                foreach($substrs as $substr)  
                    if(false!==strpos($text,$substr)){  
                        return true;  
                    }  
                    return false;  
            }
        }
        $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
        $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');  
              
        $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||  
                  CheckSubstrs($mobile_token_list,$useragent);  
              
        if ($found_mobile){  
            return true;  
        }else{  
            return false;  
        }  
    }

    
    /**
     * 并发执行url列表
     * @param $urls
     * @param $callback
     * @param $rolling_window
     * @param $custom_options
     * @return unknown_type
     */
    public static function rollingCurl($urls, $callback = null, $rolling_window = 5, $custom_options = null) {
        // make sure the rolling window isn't greater than the # of urls
        set_time_limit(0);
        $rolling_window = (sizeof($urls) < $rolling_window) ? sizeof($urls) : $rolling_window;

        $master = curl_multi_init();
        $curl_arr = array();

        // add additional curl options here
        $std_options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_MAXREDIRS => $rolling_window);
        $options = ($custom_options) ? ($std_options + $custom_options) : $std_options;

        // start the first batch of requests
        for ($i = 0; $i < $rolling_window; $i++) {
            $ch = curl_init();
            $options[CURLOPT_URL] = $urls[$i];
            curl_setopt_array($ch,$options);
            curl_multi_add_handle($master, $ch);
        }
        $retInfo = array();
        do {
            while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
            if($execrun != CURLM_OK)
                break;
            // a request was just completed -- find out which one
            while($done = curl_multi_info_read($master)) {
                $info = curl_getinfo($done['handle']);
                if ($info['http_code'] == 200)  {
                    $output = curl_multi_getcontent($done['handle']);
                    if(0 == $output)
                        continue;
                    //$retInfo[] = $output;
                    // request successful.  process output using the callback function.

                    // start a new request (it's important to do this before removing the old one)
                    $ch = curl_init();
                    $options[CURLOPT_URL] = $urls[$i++];  // increment i
                    curl_setopt_array($ch,$options);
                    curl_multi_add_handle($master, $ch);

                    // remove the curl handle that just completed
                    curl_multi_remove_handle($master, $done['handle']);
                } else {
                    // request failed.  add error handling.
                }
            }
        } while ($running);

        curl_multi_close($master);
        return $retInfo;
    } 

    /**
     * 验证邮箱地址的格式
     * Enter description here ...
     * @param unknown_type $email
     */
    public static function validateEmail($email){
        $exp = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
        if(preg_match($exp,$email)){ //先用正则表达式验证email格式的有效性
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 验证手机号码格式
     * Enter description here ...
     * @param unknown_type $email
     */
    public static function validatePhone($phone){
        $exp = "/^1[34578][0-9]{9}$/";
        if(preg_match($exp,$phone)){
            return true;
        }else{
            return false;
        }
    }    
    
    
    /**
     * 加密解密代码
     * $string 待加密解密字符窜
     * $operation 准备动作DECODE解密ENCODE加密
     * $key 密钥
     * $a = authcode('abc', 'ENCODE', 'key');
     * $b = authcode($a, 'DECODE', 'key');  // $b(abc)
     * author:caozhong
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        
        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length)
        : substr(md5(microtime()), -$ckey_length)) : '';
        
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length))
        : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        
        $result = '';
        $box = range(0, 255);
        
        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        
        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0
                || substr($result, 0, 10) - time() > 0)
              && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
              return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
    /*
    * 截取字符串到指定长度，中文算二个字符,此方法不能完全保证正确
    * @param string $str
    * @param int $len
    * @param string $dot
    * @return string
    */
    public static function cutstr($str, $len = 12, $dot = '...'){
        //长度判断，中文算两个，英文算一个
        $temp_len = strlen($str);
        $temp_mblen = mb_strlen($str,'utf-8');
        $temp_zh = ($temp_len - $temp_mblen)/2;
        $temp_en = $temp_mblen - $temp_zh;
        if(($temp_en+$temp_zh*2) <= $len){
            return $str;
        }
        
        //字串截取,中文算两个，英文算一个
        $return = '';
        for($i=0,$j=0; $j < $len ; $i++){
            if(ord(substr($str,$i,1)) >= 0xa0){
                $return .= mb_substr($str,$i,1,"utf-8");
                $j+=2;
                continue;
              }else{
                  $return .= mb_substr($str,$i,1,"utf-8");
                  $j++;
                  continue;
              }
        }
        return $return.$dot;
    }


    /**
     * 取HTML,并自动补全闭合
     * param $html
     * param $length
     * param $end
    */
    public static function subHtml($html, $length=50, $more="...") {
        $result = '';
        $tagStack = array();
        $len = 0;
        $contents = preg_split("~(<[^>]+?>)~si", $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach($contents as $tag) {
            if (trim($tag) == "") continue;
            if (preg_match("~<([a-z0-9]+)[^/>]*?/>~si", $tag)) {
                $result .= $tag;
            } else if (preg_match("~</([a-z0-9]+)[^/>]*?>~si", $tag, $match)) {
                if ($tagStack[count($tagStack)-1] == $match[1]) {
                    array_pop($tagStack);
                    $result .= $tag;
                }
            } else if (preg_match("~<([a-z0-9]+)[^/>]*?>~si", $tag, $match)) {
                array_push($tagStack, $match[1]);
                $result .= $tag;
            } else if (preg_match("~<!--.*?-->~si", $tag)) {
                $result .= $tag;
            } else {
                if ($len + self::mstrlen($tag) < $length) {
                    $result .= $tag;
                    $len += self::mstrlen($tag);
                } else {
                    $str = self::msubstr($tag, 0, $length - $len + 1);
                    $result .= $str;
                    break;
                }
            }
            } while (!empty($tagStack)) {
            $result .= '</' . array_pop($tagStack) . '>';
        }
        return $result.$more;
    }
    /**
     * 取中文字符串
     * 
     * param $string 字符串
     * param $start 起始位
     * param $length 长度
     * param $charset 编码
     * param $dot 附加字串
    */
    public static function msubstr($string, $start, $length, $dot = '', $charset = 'UTF-8') {
        $string = str_replace(array('&', '"', '<', '>', ' '), array('&', '"', '<', '>', ' '), $string);
        if (strlen($string) <= $length) {
            return $string;
        }
        if (strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                } else {
                    $n++;
                }
                $noc++;
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            for($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            }
        }
        return $strcut . $dot;
    }
    
    /**
    * 得字符串的长度，包括中英文。
    */
    public static function mstrlen($str, $charset = 'UTF-8') {
        if (function_exists('mb_substr')) {
            $length = mb_strlen($str, $charset);
        } elseif (function_exists('iconv_substr')) {
            $length = iconv_strlen($str, $charset);
        } else {
            preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-f][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $ar);
            $length = count($ar[0]);
        }
            return $length;
        }
    /**
     * 在指定数据范围内实现随机字符组合
     *
     * @param int $length
     * @param int $type
     *
     * @return string
     */
    public static function randVar($length = 0, $type = 0) {
        $range = array(0    =>  '0123456789',
                       1    =>  'abcdefghijklmnopqrstuvwxyz',
                       2    =>  'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                       3    =>  '0123456789abcdefghijklmnopqrstuvwxyz',
                       4    =>  '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                       5    =>  'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                       6    =>  '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                       7    =>  '3456789abcdefghijkmnpqrstuvwxyABCDEFGHJKLMNPQRSTUVWXY');
        if (false === array_key_exists($type, $range)) {
            $type = 6;
        }
        $character = '';
        $maxLength = strlen($range[$type])-1;
        for ($i = 0; $i < $length; ++$i) {
            $character .= $range[$type][mt_rand(0, $maxLength)];
        }
        return $character;
    }
    
    /**
      +----------------------------------------------------------
     * 将一个字符串部分字符用*替代隐藏
      +----------------------------------------------------------
     * @param string    $string   待转换的字符串
     * @param int       $bengin   起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int       $len      需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int       $type     转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
     * @param string    $glue     分割符
      +----------------------------------------------------------
     * @return string   处理后的字符串
      +----------------------------------------------------------
     */
    public static function hideStr($string, $bengin=0, $len = 4, $type = 0, $glue = "@") {
        if (empty($string))
            return false;
        $array = array();
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }
        if ($type == 0) {
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", $array);
        }else if ($type == 1) {
            $array = array_reverse($array);
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", array_reverse($array));
        }else if ($type == 2) {
            $array = explode($glue, $string);
            $array[0] = hideStr($array[0], $bengin, $len, 1);
            $string = implode($glue, $array);
        } else if ($type == 3) {
            $array = explode($glue, $string);
            $array[1] = hideStr($array[1], $bengin, $len, 0);
            $string = implode($glue, $array);
        } else if ($type == 4) {
            $left = $bengin;
            $right = $len;
            $tem = array();
            for ($i = 0; $i < ($length - $right); $i++) {
                if (isset($array[$i]))
                    $tem[] = $i >= $left ? "*" : $array[$i];
            }
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; $i++) {
                $tem[] = $array[$i];
            }
            $string = implode("", $tem);
        }
        return $string;
    }
    
    /**
     * 替换文字内字符
     * Enter description here ...
     * @param string $str
     */
    public static function replaceSpecialWord($str){
        if($str==""){
            return $str;
        }
        $str  = strtolower($str);
        $wordArr = Yii::app()->params['special_symbols'];
        foreach ($wordArr as $row){
            $str = strtr($str,array($row=>''));
        }
        return $str;
    }
    
    

    //过滤html片段里面的js和css标签
    public static  function  ClearHtml($content) {
    $content = preg_replace("/<a[^>]*>/i", "", $content);
    $content = preg_replace("/<\/a>/i", "", $content);
    $content = preg_replace("/<div[^>]*>/i", "", $content);
    $content = preg_replace("/<\/div>/i", "", $content);
    $content = preg_replace("/<!--[^>]*-->/i", "", $content);//注释内容  
    $content = preg_replace("/style=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/class=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/id=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/lang=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/width=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/height=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/border=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/face=.+?['|\"]/i",'',$content);//去除样式
    $content = preg_replace("/face=.+?['|\"]/",'',$content);//去除样式 只允许小写 正则匹配没有带 i 参数
    return $content;
}

    //获取完整头像地址
    public static function getHeadPicUrl($dir){
        $img_url = 'http://statics.hivilla.com/';
        if($dir){
            return $img_url.$dir;
        }
        return false;
    }
   
}
