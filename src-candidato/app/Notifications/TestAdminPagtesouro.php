<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestAdminPagtesouro extends Notification
{
    use Queueable;

    public $userName;
    public $idPagamento;
    public $situacaoPagamento;
    public $tipoPagamento;
    public $valorPagamento;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        string $userName, string $idPagamento, string $situacaoPagamento, 
        string $tipoPagamento, string $valorPagamento)
    {
        $this->userName = $userName;
        $this->idPagamento = $idPagamento;
        $this->situacaoPagamento = $situacaoPagamento;
        $this->tipoPagamento = $tipoPagamento;
        $this->valorPagamento = $valorPagamento;
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
                ->greeting("Olá, {$this->userName}!")
                ->subject("Retorno de Teste de pagamento")
                ->line("O Pagamento {$this->idPagamento} retornou o status: {$this->situacaoPagamento}")
                ->line("Forma de pagamento: {$this->tipoPagamento}")
                ->line("Valor: {$this->valorPagamento}");
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
