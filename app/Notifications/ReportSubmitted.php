<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportSubmitted extends Notification
{
    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Novo email OPV')
            ->greeting('Nova denúncia recebida')
            ->line('Denúncia #' . $this->report->id)
            ->line('Tipo: ' . $this->report->type)
            ->line('Utilizador denunciante: ' . $this->report->user_id)
            ->line('Conteúdo denunciado: ' . $this->report->report_id)
            ->line('Motivo: ' . $this->report->reason)
            ->line('Mensagem: ' . ($this->report->message ?: 'Não informada'));

        if ($this->report->proof_of_complaint) {
            $proofPath = public_path($this->report->proof_of_complaint);

            if (is_file($proofPath)) {
                $mail->attach($proofPath);
            }
        }

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
