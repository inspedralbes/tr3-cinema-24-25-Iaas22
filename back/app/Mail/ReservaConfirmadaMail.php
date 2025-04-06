<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ReservaConfirmadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $apellidos;
    public $seats;
    public $total;
    public $sessionId;
    public $movieTitle;
    public $sessionDate;
    public $sessionTime;
    public $pdfPath;

    public function __construct($name, $apellidos, $seats, $total, $sessionId, $movieTitle, $sessionDate, $sessionTime, $pdfPath)
    {
        $this->name = $name;
        $this->apellidos = $apellidos;
        $this->seats = $seats;
        $this->total = $total;
        $this->sessionId = $sessionId;
        $this->movieTitle = $movieTitle;
        $this->sessionDate = $sessionDate;
        $this->sessionTime = $sessionTime;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Reserva Confirmada - '.$this->movieTitle)
                    ->view('emails.reserva')
                    ->attach($this->pdfPath, [
                        'as' => 'reserva_confirmada.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}