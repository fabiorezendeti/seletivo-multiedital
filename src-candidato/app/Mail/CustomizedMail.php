<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Repository\ParametersRepository;

class CustomizedMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $title;
    public $content;
    public $number;
    public $total;
    public $actionText = 'Clique aqui para acompanhar sua inscrição';    

    public function __construct($subject,$content, $number, $total)
    {
        $this->subject = $subject;
        $this->content = $content;        
        $this->number = $number;
        $this->total = $total;
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
            ->markdown('mails.customized');
    }
}
