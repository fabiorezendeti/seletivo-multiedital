<?php

namespace App\Notifications;

use App\Models\Process\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Subscribe extends Notification
{
    use Queueable;



    public $course;
    public $campus;
    public $noticeNumber;
    public $affirmativeAction;
    public $selectionCriteria;
    public $userName;
    public $candidateAdditionalInstructions;

    public function __construct(
        string $campus,
        string $course,
        string $noticeNumber,
        string $affirmativeAction,
        string $selectionCriteria,
        string $userName,
        string $candidateAdditionalInstructions
    ) {
        $this->campus = $campus;
        $this->course = $course;
        $this->noticeNumber = $noticeNumber;
        $this->affirmativeAction = $affirmativeAction;
        $this->selectionCriteria = $selectionCriteria;
        $this->userName = $userName;
        $this->candidateAdditionalInstructions = $candidateAdditionalInstructions;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("Olá, " . ucwords(strtolower($this->userName)) . "!")
            ->subject("Inscrição no Edital {$this->noticeNumber}")
            ->line("Sua inscrição para o Edital {$this->noticeNumber} foi realizada com sucesso!")
            ->line("Você selecionou:")
            ->line("Curso: {$this->course} em {$this->campus}")
            ->line("Ação Afirmativa: {$this->affirmativeAction}!")
            ->line("Método de Seleção: {$this->selectionCriteria}")
            ->line("Mantenha seus dados pessoais e de contato sempre atualizados!")
            ->line(new \Illuminate\Support\HtmlString(nl2br($this->converteToLink($this->candidateAdditionalInstructions))))
            ->action('Clique aqui para acompanhar sua inscrição', url('/'))
            ->salutation('Obrigado!');
    }

    private function converteToLink($text)
    {
        $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
        $string = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $text);
        return $string;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
