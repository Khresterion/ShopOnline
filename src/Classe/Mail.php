<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{

    private $api_key = "2f3c17de7c460618850ea9e50a362f68";
    private $api_key_secret = "dbca98ca074ba0fdd33411219d6dee0e";

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret);
        $body = [
            'FromEmail' => "axios.ludis@gmail.com",
            'FromName' => "Dark Room",
            'Subject' => $subject,
            'MJ-TemplateID' => 2972431,
            'MJ-TemplateLanguage' => true,
            // 'Text-part' => 'Dear passenger, welcome to Mailjet! On this {{var:day:"monday"}}, may the delivery force be with you! {{var:personalmessage:""}}',
            // 'Html-part' => '<h3>Dear passenger, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!<br /> On this {{var:day:"monday"}}, may the delivery force be with you! {{var:personalmessage:""}}',
            'Recipients' => [
                ['Email' => $to_email, 'Name' => $to_name, 'Vars' => ['content' => $content]]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
