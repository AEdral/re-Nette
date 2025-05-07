<?php
 
namespace App\Classes;
 
use Nette;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;
use Latte\Engine;
use Nette\Http;
 
class MailManager {
    use Nette\SmartObject;
 
    private $from = APPNAME.' <'.APPEMAIL.'>';
    private $request;
    private $parameters=[];
    private $body;

    public function __construct($mail) {
        $this->parameters = $mail;
        $this->body = "";
    }
 
    public function sendMail(array $to, string $subject, string $template, array $data, array $bcc = [], array $attachments = []):void {

        //Preparo l'engine ed il messaggio
        $latte = new Engine;
        $message = new Message;

        //Dati di default da passare al template
        $data["appUrl"] = APPURL;
        $data["appName"] = APPNAME;

        //Inizio a settare il messaggio
        $message->setFrom($this->from);
        foreach ($to as $value) $message->addTo($value);
        foreach ($bcc as $value) $message->addBcc($value);
        //if(DEBUG) $message->addBcc('fabiano@juicenet.it');
        //if(DEBUG) $message->addBcc('laup.97@gmail.com');
        $message->setSubject($subject);

        /*
        //Da sistemare per rendere la classe modulare vedere se va bene
            if( strlen($template) ){
                $pathTemplate = is_file($template)?$template:__DIR__."/Layout/$template";
                $this->body = $latte->renderToString($pathTemplate, $data);
                $message->setHtmlBody($this->body);
            }
        */
        if( strlen($template) && is_file(__DIR__."/Layout/$template.latte")){
            $this->body = $latte->renderToString(__DIR__."/Layout/$template.latte", $data);
            $message->setHtmlBody($this->body);
        }
        else {
            foreach($data AS $key => $value)
                $this->body .= "$key : $value \n";
            $message->setBody($this->body);
        }

        if($attachments){
            foreach($attachments AS $attachment){
                $message->addAttachment($attachment);
            }
        }

        //Eseguo l'inoltro in base alle impostazioni del framework
        if($this->parameters['smtp']){
            //(string $host, string $username, string $password, ?int $port = null, ?string $encryption = null, bool $persistent = false, int $timeout = 20, ?string $clientHost = null, ?array $streamOptions = null)
            $mailer = new SmtpMailer($this->parameters['host'],$this->parameters['username'],$this->parameters['password'],$this->parameters['port'],$this->parameters['secure']);
            $mailer->send($message);
        } else {
            $mailer = new SendmailMailer;
            $mailer->send($message);
        }

    }

}