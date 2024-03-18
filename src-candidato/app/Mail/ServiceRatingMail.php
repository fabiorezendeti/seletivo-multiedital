<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Repository\ParametersRepository;

class ServiceRatingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $greeting;
    public $content;
    public $link;
    public $actionText = 'Clique aqui para avaliar nosso serviço de inscrição';

    public function __construct($subject, $content, $name, $link)
    {
        $this->greeting =  "Olá, " . ucwords(strtolower($name)) . "!";
        $this->content = $content;
        $this->subject = $subject;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $parameters = new ParametersRepository();

        return $this
            ->from($parameters->getValueByName('email_instituicao'),$parameters->getValueByName('sigla_instituicao'))
            ->markdown('mails.service-rating');
    }
}
