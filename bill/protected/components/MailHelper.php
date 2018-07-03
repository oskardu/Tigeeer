<?php
class MailHelper
{
    const HOSTNAME = 'smtp.exmail.qq.com';
    const PORT = 25;
    const USERNAME = 'hello@tigeeer.com';
    const PASSWORD = 'Hellotigeeer123';
    const CCUSERMAIL = '2292552585@qq.com';
    const CCUSERNAME = 'dch';
    private static $_mailer;

    /**
     * 获取邮件发送器
     * @return object Mailer
     */
    public static function getMailer()
    {
        if (!self::$_mailer) {
            $exception = false;
            Yii::import('ext.mailer.*');
            $mailer = new Mailer($exception);
            $mailer->IsSMTP();
            $mailer->SMTPAuth = true;
            $mailer->SMTPKeepAlive = true;
            $mailer->Port = 25;
            $mailer->Host = self::HOSTNAME;
            $mailer->Username = self::USERNAME;
            $mailer->Password = self::PASSWORD;
            #@TODO Xxx应该改为正式公司名称
            $mailer->SetFrom(self::USERNAME, 'Tigeeer');
            $mailer->AddReplyTo(self::USERNAME, 'tigeeer');
            self::$_mailer = $mailer;
        }
        return self::$_mailer;
    }

    public static function send(array $data)
    {
        $mailer = self::getMailer();

        if (is_array($data['address'])) {
            foreach ($data['address'] as $address => $name) {
                $mailer->AddAddress($address, $name);
            }
        }else{
            $mailer->AddAddress($data['address'], '');
        }

        if($data['cc']){
                if (is_array($data['cc'])) {
                    foreach ($data['cc'] as $address => $name) {
                        $mailer->AddCC($address, $name);
                    }
                }else{
                    $mailer->AddCC($data['cc'], '');
                }
        }
        if($data['bcc']){
                if (is_array($data['bcc'])) {
                    foreach ($data['bcc'] as $address => $name) {
                        $mailer->AddBCC($address, $name);
                    }
                }else{
                    $mailer->AddBCC($data['bcc'], '');
                }
        }

        #$mailer->WordWrap = 50;
        $mailer->Subject = $data['subject'];
        $mailer->Body = $data['body'];
        $mailer->IsHTML(true);

        if (is_string($data['attachment'])) {
            $data['attachment'] = array($data['attachment']);
        }
        if (is_array($data['attachment'])) {
            foreach ($data['attachment'] as $attachment) {
                $mailer->AddAttachment($attachment);
            }
        }

        try {
            $mailer->send();
            $mailer->ClearAllRecipients();
            return true;
        } catch (MailerException $e) {
            return false;
        }
    }

    /**
     * 程序中的bug report
     * @param mixed $bugInfo 数组或字符串
     * @return boolean
     */
    public static function noticeBug($bugInfo)
    {
        if (is_string($bugInfo)) {
            $bugInfo = array($bugInfo);
        }
        $bugInfo[] = '用户：' . Yii::app()->user->id
            . '|' . Yii::app()->user->name;
        $bugInfo[] = Yii::app()->request->getHostInfo()
            . Yii::app()->request->getQueryString();
        $bugInfo[] = '时间：' . date('Y-m-d H:i:s');
        $bugInfo = implode('<br />', $bugInfo);
        $mailer = self::getMailer();
        $mailer->AddAddress(self::CCUSERMAIL, self::CCUSERNAME);
        $mailer->WordWrap = 50;
        $mailer->Subject = "bug";
        $mailer->Body = $bugInfo;
        $mailer->IsHTML(true);
        try {
            $mailer->send();
            return true;
        } catch (MailerException $e) {
            return false;
        }
    }
}
