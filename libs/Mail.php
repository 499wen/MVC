<?php
namespace libs;

class Mail
{
    public $mailer;
    public function __construct()
    {
        // 设置邮件服务器账号
        $transport = (new \Swift_SmtpTransport('smtp.126.com', 25))  // 邮件服务器IP地址和端口号
        ->setUsername('wen2237380164@126.com')       // 发邮件账号
        ->setPassword('6496623322hwx');      // 授权码
        // 创建发邮件对象
        $this->mailer = new \Swift_Mailer($transport);
    }

    /*
    $to:['邮箱地址'，'姓名']
    */
    public function send($to = ["wen2237380164@126.com","asd"],$title="MVC - 网站", $content="恭喜您注册成功！")
    {
        // 创建邮件消息
        $message = new \Swift_Message();
        
        $message->setSubject($title)   // 标题
                ->setFrom(['wen2237380164@126.com' => '全栈1班'])   // 发件人
                ->setTo([
                    $to[0], 
                    $to[0] => $to[1]
                ])   // 收件人
                ->setBody($to[2], 'text/html');     // 邮件内容及邮件内容类型

        // 发送邮件
        return $this->mailer->send($message);
    }
}